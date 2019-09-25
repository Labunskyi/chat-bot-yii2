<?php
/**
 * @var $base_id         integer
 * @var $this            \yii\web\View
 * @var $searchModel     \app\models\search\NewsletterSearch
 */

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Bots;
use app\controllers\CronController;

$this->title = Yii::t( 'app', 'Newsletter list' );
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs( '
$( "#newslettersearch-q" ).change(function() {
    $("#search-form").submit();
});
$( ".checktag input" ).change(function() {
    $("#search-form").submit();
});
' );

?>
<?php Pjax::begin(); ?>

    <div class="content-detached content-left">
        <div class="content-body block-table">
            <section class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-head">
                            <div class="card-header">
                                <h4 class="card-title"><?= $this->title ?></h4>

                                <div class="heading-elements">
                                    <?php if ( \app\models\Customer::find()->where( [ 'base_id' => $base_id ] )->one() ) { ?>
                                        <a href="<?= Url::to( [ 'newsletter/add', 'base_id' => $base_id ] ) ?>"
                                           class="btn btn-success btn-min-width btn-sm box-shadow-3 mr-1 mb-1">
                                            <i class="fa fa-plus"></i> <?= Yii::t( 'app', 'Add Newsletter' ) ?></a>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <!-- Task List table -->
                                <?php $pageSizeWidget = \nterms\pagesize\PageSize::widget( [
                                    'options'                          => [ 'class' => 'selectBox' ], 'label' => Yii::t( 'app',
                                        'on page' ), 'defaultPageSize' => 5, 'sizes' => [ 5 => 5, 50 => 50, 200 => 200, 500 => 500 ],
                                ] ); ?>

                                <?= GridView::widget( [
                                    'dataProvider'     => $dataProvider,
                                    'layout'           => '<div class="row mb-1"><div class="col-lg-2">' . $pageSizeWidget . '</div><div class="col-lg-4">{summary}</div><div class="col-lg-6 text-right">{pager}</div></div>{items}<div class="row"><div class="col-lg-6">{summary}</div><div class="col-lg-6 text-right">{pager}</div></div>',
                                    'filterSelector'   => 'select[name="per-page"]',
                                    'headerRowOptions' => [ 'class' => 'row-column-middle' ],
                                    'rowOptions'       => [ 'class' => 'row-column-middle' ],
                                    'columns'          =>
                                        [

                                            //'id:integer:#',
                                            [
                                                'label'  => Yii::t( 'app', 'Message for newsletter' ),
                                                'format' => 'raw',
                                                'value'  => function ( $searchModel ) {
                                                    return '<div style="word-wrap: break-word; max-width: 300px; ">' . mb_strimwidth( $searchModel->message,
                                                            0, 300, '...' ) . '</div>';
                                                },
                                            ],
                                            [
                                                'label'  => Yii::t( 'app', 'Filter settings' ),
                                                'format' => 'raw',
                                                //'attribute' => 'settings',
                                                'value'  => function ( $searchModel ) {
                                                    $settings = json_decode( $searchModel->settings );
                                                    $data = [];
                                                    $i = 0;
                                                    foreach ( $settings as $key => $setting ) {
                                                        $data[ $i ]['text'] = '<b>' . Yii::t( 'app', $key ) . '</b>';
                                                        foreach ( $setting as $item ) {
                                                            if ( $key == 'bots' ) {
                                                                $data[ $i ]['nodes'][]['text'] = Bots::findOne( $item )->first_name;
                                                            } else {
                                                                $data[ $i ]['nodes'][]['text'] = $item;
                                                            }
                                                        }
                                                        $i++;
                                                    }

                                                    $html = \execut\widget\TreeView::widget( [
                                                        'data'          => $data,
                                                        'size'          => \execut\widget\TreeView::SIZE_SMALL,
                                                        'template'      => '<div class="tree-view-wrapper text-left">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    {tree}
                                                                </div>
                                                            </div>
                                                        </div>',
                                                        'clientOptions' => [
                                                            'showBorder'        => false,
                                                            'backColor'         => 'transparent',
                                                            'onhoverColor'      => 'transparent',
                                                            'selectedBackColor' => 'transparent',
                                                            'selectedColor'     => 'black',
                                                            'levels'            => 1,
                                                            'expandIcon'        => 'la la-plus',
                                                            'collapseIcon'      => 'la la-minus',
                                                        ],

                                                    ] );

                                                    return $html;
                                                },

                                            ],
                                            [
                                                'label'  => Yii::t( 'app', 'M / D' ),
                                                'format' => 'raw',
                                                'value'  => function ( $searchModel ) {
                                                    $count_u = \app\models\NewsletterMessages::find()->where( [ 'newsletter_id' => $searchModel->id ] )->count();
                                                    $count_s = \app\models\NewsletterMessages::find()->where( [ 'newsletter_id' => $searchModel->id, 'status' => CronController::STATUS_SENDED ] )->count();

                                                    return $count_u . ' / ' . $count_s;
                                                },

                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Status' ),
                                                'format'    => 'raw',
                                                'attribute' => 'status',
                                                'value'     => function ( $searchModel ) {
                                                    $count_u = \app\models\NewsletterMessages::find()->where( [ 'newsletter_id' => $searchModel->id ] )->count();
                                                    $count_s = \app\models\NewsletterMessages::find()->where( [ 'newsletter_id' => $searchModel->id, 'status' => CronController::STATUS_SENDED ] )->count();
                                                    if ( $searchModel->status == 0 ) {
                                                        return Yii::t( 'app', 'Draft' );
                                                    } elseif ( $searchModel->status == 1 && $count_s == $count_u ) {
                                                        return Yii::t( 'app', 'Sended' );
                                                    } elseif ( $searchModel->status == 1 && !$count_s ) {
                                                        return Yii::t( 'app', 'In line for sending' );
                                                    } elseif ( $searchModel->status == 1 && $count_s ) {
                                                        return Yii::t( 'app', 'Sending in progress' );
                                                    }

                                                    return null;
                                                },

                                            ],
                                            [
                                                'label'  => Yii::t( 'app', 'Actions' ),
                                                'format' => 'raw',
                                                'value'  => function ( $searchModel ) {
                                                    $buttons = '';
                                                    if ( $searchModel->id != 0 ) {
                                                        $buttons = '<div class="form-group text-center"><div class="btn-group btn-group-sm" role="group" aria-label="Basic example">';

                                                        if ( $searchModel->status != 1 ) {
                                                            $buttons .= Html::a( '<i class="la la-edit" aria-hidden="true"></i>',
                                                                Url::to( [ 'newsletter/edit', 'base_id' => $searchModel->base_id, 'id' => $searchModel->id ] ),
                                                                [ 'class' => 'btn btn-info' ] );

                                                        }

                                                        $buttons .= Html::a( '<i class="la la-trash" aria-hidden="true"></i>',
                                                            Url::to( [ 'newsletter/delete', 'base_id' => $searchModel->base_id, 'id' => $searchModel->id ] ),
                                                            [ 'class' => 'btn btn-danger' ] );

                                                        $buttons .= '</div></div>';
                                                    }


                                                    return $buttons;
                                                },

                                            ],
                                        ],

                                ] ) ?>


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
                    <!-- contacts search -->

                    <div class="card-body border-top-blue-grey border-top-lighten-5">
                        <div class="bug-list-search">
                            <div class="bug-list-search-content">
                                <?php $form = \yii\bootstrap\ActiveForm::begin( [
                                    'method' => 'get',
                                    'id'     => 'search-form',
                                    //'options' => [ 'data-pjax' => '', ],
                                    'action' => Url::to( [ 'newsletter/list', 'base_id' => $base_id ] ),
                                ] ); ?>
                                <?= $form->field( $searchModel, 'q', [
                                    'template' => "
                                        <div class=\"position-relative\">
                                        {input}
                                        <div class=\"form-control-position\">
                                            <i class=\"la la-search text-size-base text-muted\"></i>
                                        </div>
                                    </div>",

                                ] )->label( '' )->textInput( [
                                    'placeholder' => Yii::t( 'app', 'Search newsletter...' ),
                                ] ) ?>
                            </div>
                        </div>
                    </div>
                    <!-- /contacts search -->
                    <!-- platforms-->

                    <!--div class="card-body pt-0">
                        <p class="lead"><?= Yii::t( 'app', 'Statuses' ) ?></p>
                        <?= $form->field( $searchModel, 'status',
                        [ 'template' => "<div class=\"col-12 checktag\">{input}</div>\n" ] )
                        ->checkboxList( $searchModel->statuses(), [ 'class' => 'checktag' ] ) ?>
                    </div-->
                    <!--/ platforms-->
                    <?php if ( count( $platforms ) > 1 ) { ?>
                        <!-- platforms-->

                        <div class="card-body pb-0 pt-0">
                            <p class="lead"><?= Yii::t( 'app', 'Platforms' ) ?></p>
                            <?php foreach ( $platforms as $key => $value ) { ?>
                                <?= $form->field( $searchModel, 'platf[' . $key . ']' )
                                    ->checkbox( [ 'template' => "<div class=\"col-12 checktag block-element\">{input}{label}</div>\n" ] )
                                    ->label( $key ) ?>
                            <?php } ?>
                        </div>
                        <!--/ platforms-->
                    <?php }
                    if ( count( $bots ) > 1 ) { ?>
                        <!-- platforms-->

                        <div class="card-body pb-0 pt-0">
                            <p class="lead"><?= Yii::t( 'app', 'Bots' ) ?></p>
                            <?php foreach ( $bots as $key => $value ) { ?>
                                <?= $form->field( $searchModel, 'bots[' . $key . ']' )
                                    ->checkbox( [ 'template' => "<div class=\"col-12 checktag block-element\">{input}{label}</div>\n" ] )
                                    ->label( Bots::findOne( $key )->first_name ) ?>
                            <?php } ?>
                        </div>
                        <!--/ platforms-->
                    <?php }
                    if ( count( $tags ) >= 1 ) { ?>
                        <!-- tags-->
                        <div class="card-body pt-0">
                            <p class="lead"><?= Yii::t( 'app', 'Tags' ) ?></p>
                            <?php foreach ( $tags as $key => $value ) { ?>
                                <?= $form->field( $searchModel, 'tag[' . $key . ']' )
                                    ->checkbox( [ 'template' => "<div class=\"col-12 checktag block-element\">{input}{label}</div>\n" ] )
                                    ->label( $key ) ?>
                            <?php } ?>
                        </div>
                        <!--/ tags-->
                    <?php } ?>
                </div>
                <?php \yii\bootstrap\ActiveForm::end(); ?>
                <!--/ Predefined Views -->
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>