<?php
/**
 * @var $base_id         integer
 * @var $this            \yii\web\View
 * @var $searchModel     \app\models\Order
 * @var $model           \app\models\search\OrderSearch
 */

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Order;
use app\models\Category;
use app\models\Helper;
use yii\helpers\ArrayHelper;

$this->title = Yii::t( 'app', 'Orders' );
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs( '
$(\'body\').on(\'click\',\'a.del-order\',function(e){
    e.preventDefault();
    
    var warning_text = "' . Yii::t( 'app', 'Are you sure you want to delete this order?' ) . '";

    var conf = confirm( warning_text );
    if(conf) {
        var id = $(this).attr(\'href\');
        var base_id = $(this).attr(\'data-base-id\');
        $.ajax({
            url: "' . \yii\helpers\Url::toRoute( [ 'order/delete' ], true ) . '",
            type: \'post\',
            data: {
                \'id\': id,
                \'base_id\': base_id
            },
            success: function (result) {
                if (result) {
                    $(\'tr[data-key = \' + id + \']\').detach();
                } else {
                    alert(\'error change status\');
                }
            }
        });
    }

});


setInterval(function() {
        $.ajax({
            url: "' . \yii\helpers\Url::toRoute( [ 'order/check-new-order', 'base_id' => $base_id ], true ) . '",
            type: \'post\',
            success: function (result) {
                if (result !== "0") {
                    var audio = new Audio();
                    audio.preload = \'auto\';
                    audio.src = "'.Url::to('@web/sound/notify.mp3').'";
                    audio.play();
                    swal("'.Yii::t('app', 'New order').'","'. Yii::t('app', "You have new order").'"+result ,"success").then(e => location.reload())
                } 
            }
        });


}, 5000);



' );

?>

<?php Pjax::begin(); ?>

    <div class="content-detached content-left">
        <div class="content-body block-table">
            <section class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <!-- Task List table -->
                                <?php $pageSizeWidget = \nterms\pagesize\PageSize::widget( [
                                    'options'                          => [ 'class' => 'selectBox' ], 'label' => Yii::t( 'app',
                                        'on page' ), 'defaultPageSize' => 10, 'sizes' => [ 10 => 10, 50 => 50, 200 => 200, 500 => 500 ],
                                ] ); ?>

                                <?= GridView::widget( [
                                    'dataProvider'     => $dataProvider,
                                    'layout'           => '<div class="row mb-1"><div class="col-lg-2">' . $pageSizeWidget . '</div><div class="col-lg-4">{summary}</div><div class="col-lg-6 text-right">{pager}</div></div>{items}<div class="row"><div class="col-lg-6">{summary}</div><div class="col-lg-6 text-right">{pager}</div></div>',
                                    'filterSelector'   => 'select[name="per-page"]',
                                    'headerRowOptions' => [ 'class' => 'row-column-middle' ],
                                    'rowOptions'       => [ 'class' => 'row-column-middle' ],
                                    'columns'          =>
                                        [
                                            [
                                                'label'     => '#',
                                                'format'    => 'integer',
                                                'attribute' => 'id',
                                            ],
                                            [
                                                'label'  => Yii::t( 'app', 'Name' ) . ' + ' . Yii::t( 'app', 'Phone' ),
                                                'format' => 'raw',
                                                'value'  => function ( $searchModel ) {
                                                    $text = $searchModel->name . "<br>";
                                                    $text .= $searchModel->phone;

                                                    return $text;
                                                },
                                            ],
                                            [
                                                'label'  => Yii::t( 'app', 'Sum' ),
                                                'format' => 'raw',
                                                'value'  => function ( $searchModel ) {
                                                    return $searchModel->calcOrder()['totalSumm'];
                                                },
                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Delivery Method' ),
                                                'format'    => 'raw',
                                                'attribute' => 'delivery_method_id',

                                                'value' => function ( $searchModel ) {
                                                    return $searchModel->getDeliveryMethodName();
                                                },
                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Delivery Point' ),
                                                'format'    => 'raw',
                                                'attribute' => 'delivery_point_id',
                                                'value'     => function ( $searchModel ) {
                                                    return $searchModel->getDeliveryPoint();
                                                },
                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Payment Method' ),
                                                'format'    => 'raw',
                                                'attribute' => 'payment_method_id',

                                                'value' => function ( $searchModel ) {
                                                    return $searchModel->getPaymentMethodName();
                                                },
                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Gift Cart' ),
                                                'format'    => 'raw',
                                                'attribute' => 'gift_cart',

                                                'value' => function ( $searchModel ) {
                                                   return Order::getGiftCartStatus()[$searchModel->gift_cart];
                                                },
                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Status' ),
                                                'format'    => 'raw',
                                                'attribute' => 'status',
                                                'value'     => function ( $searchModel ) {
                                                    return $searchModel->getStatus();
                                                },
                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Time order' ) . " + " . Yii::t( 'app', 'Hover time in processing' ),
                                                'format'    => 'raw',
                                                'attribute' => 'create_at',
                                                'value'     => function ( $searchModel ) {
                                                    $response = date('d-m-Y H:i:s', $searchModel->create_at);
                                                    if( in_array($searchModel->status, [Order::STATUS_NEW, Order::STATUS_NEW_VIEWED]) ) {
                                                        $datetime1 = new DateTime('NOW');
                                                        $datetime2 = new DateTime(date('Y-m-d H:i:s', $searchModel->create_at));
                                                        $interval = $datetime1->diff($datetime2);
                                                        $response .= "<br/>" . $interval->format(Yii::t( 'app', '%ad %Hh %im %ss'));
                                                    }else {
                                                        $response .= "<br/>" . "-";
                                                    }
                                                    return $response;
                                                },
                                                'contentOptions' => ['style' => 'width: 20%;'],
                                            ],
                                            [
                                                'label'  => Yii::t( 'app', 'Actions' ),
                                                'format' => 'raw',
                                                'value'  => function ( $searchModel ) {
                                                    $buttons = '';
                                                    if ( $searchModel->id != 0 ) {
                                                        $buttons = '<div class="form-group text-center"><div class="btn-group btn-group-sm" role="group" aria-label="Basic example">';

                                                         $buttons .= Html::a( '<i class="la la-edit" aria-hidden="true"></i>',
                                                           Url::to( [ 'order/edit', 'base_id' => $searchModel->base_id, 'id' => $searchModel->id ] ),
                                                           [ 'class' => 'btn btn-info' ] );

                                                        $buttons .= Html::a( '<i class="la la-trash" aria-hidden="true"></i>',
                                                            $searchModel->id,
                                                            [ 'class' => 'btn btn-danger del-order', 'data-base-id' => $searchModel->base_id ] );

                                                        $buttons .= '</div></div>';
                                                    }


                                                    return $buttons;
                                                },

                                            ],
                                        ],

                                ] );

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="sidebar-detached sidebar-right">
        <div class="sidebar">
            <div class="bug-list-sidebar-content">
                <!-- Predefined Views -->
                <div class="card fix-form">
                    <div class="card-header">
                        <h4 class="card-title"><?=Yii::t('app' , 'Filter')?></h4>
                    </div>
                    <div class="card-body border-top-blue-grey border-top-lighten-5">
                        <div class="bug-list-search">
                            <div class="bug-list-search-content">
                                <?php $form = \yii\bootstrap\ActiveForm::begin( [
                                    'method'  => 'get',
                                    'options' => [ 'enctype' => 'multipart/form-data' ],
                                    'id'      => 'search-form',
                                    'action'  => Url::to( [ 'order/list', 'base_id' => $base_id ] ),
                                ] ); ?>
                                <?= $form->field( $searchModel, 'q' )->label( Yii::t('app', 'phone_name_address') )->textInput() ?>
                                <?= $form->field( $searchModel, 'delivery_method' )
                                    ->widget( \kartik\select2\Select2::classname(), [
                                        'data'         => \app\models\DeliveryMethod::getArrayFroBase( $base_id ),
                                        'id'            => 'delivery_method',
                                        'options' => ['multiple' => true, 'placeholder' => Yii::t( 'app', 'Delivery method...' )]
                                    ] ); ?>
                                <?= $form->field( $searchModel, 'delivery_point' )
                                    ->widget( \kartik\select2\Select2::className(), [
                                        'data'         => \app\models\DeliveryPoint::getArrayFroBase( $base_id ),
                                        'id'            => 'delivery_point_id',
                                        'options' => ['multiple' => true, 'placeholder' => Yii::t( 'app', 'Delivery point...' ),

                                        ],
                                    ] ); ?>
                                <?= $form->field( $searchModel, 'statu' )
                                    ->widget(\kartik\select2\Select2::className(), [
                                        'data'         => \app\models\Order::getStatusNames(Yii::$app->language),
                                        'id'            => 'status',
                                        'options' => ['multiple' => true, 'placeholder' => Yii::t( 'app', 'Status...' ),

                                        ],
                                    ] ); ?>
                                <?= Html::submitButton( '<i class="la la-filter"></i> ' . Yii::t( 'app', 'Filter' ),
                                    [ 'class' => 'btn btn-outline-info full-width' ] ) ?>
                                <a class="btn btn-outline-warning full-width mt-1" href="<?=Url::to(['order/list' , 'base_id' => $base_id])?>"><?=Yii::t( 'app', 'Clean filter' )?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php \yii\bootstrap\ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>