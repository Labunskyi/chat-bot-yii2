<?php
/**
 * @var $base_id         integer
 * @var $this            \yii\web\View
 * @var $searchModel     \app\models\Menu
 */

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Category;
use app\models\Helper;

$this->title = Yii::t( 'app', 'Command list' );
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs( '
$( ".search_form input, .search_form select" ).change(function() {
    $("#search-form").submit();
});
$(\'body\').on(\'click\',\'a.del-command\',function(e){
    e.preventDefault();
    
    var warning_text = "' . Yii::t( 'app', 'Are you sure you want to delete a command item? All items associated with it will be deleted.' ) . '";

    var conf = confirm( warning_text );
    if(conf) {
        var id = $(this).attr(\'href\');
        var base_id = $(this).attr(\'data-base-id\');
        $.ajax({
            url: "' . \yii\helpers\Url::toRoute(['command/delete'], true) . '",
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
' );

?>

<style>
    img{
         max-height: 150px;
         max-width: 150px;
     }
</style>

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
                                    <a href="<?= Url::to( [ 'command/add', 'base_id' => $base_id ] ) ?>"
                                       class="btn btn-success btn-min-width btn-sm box-shadow-3 mr-1 mb-1"><i
                                                class="fa fa-plus"></i> <?= Yii::t( 'app', 'Add Command' ) ?></a>
                                </div>

                            </div>
                        </div>
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
                                                'label'     => Yii::t( 'app', 'Command' ),
                                                'format'    => 'raw',
                                                'attribute' => 'command',
                                                'value'     => function ( $searchModel ) {
                                                    return $searchModel->command;
                                                }
                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Text' ),
                                                'format'    => 'raw',
                                                'attribute' => 'text',
                                                'value'     => function ( $searchModel ) {
                                                    return $searchModel->text;
                                                }
                                            ],
                                            [
                                                'label'  => Yii::t( 'app', 'Actions' ),
                                                'format' => 'raw',
                                                'value'  => function ( $searchModel ) {
                                                    $buttons = '';
                                                    $buttons = '<div class="form-group text-center"><div class="btn-group btn-group-sm">';

                                                    $buttons .= Html::a( '<i class="la la-edit" aria-hidden="true"></i>',
                                                            Url::to( [ 'command/edit', 'base_id' => $searchModel->base_id, 'id' => $searchModel->id ] ),
                                                            [ 'class' => 'btn btn-info' ] );
                                                    if($searchModel->command != '/start') {
                                                         $buttons .= Html::a( '<i class="la la-trash" aria-hidden="true"></i>',
                                                             $searchModel->id,
                                                             [ 'class' => 'btn btn-danger del-command', 'data-base-id' => $searchModel->base_id ] );
                                                    }
                                                    $buttons .= '</div></div>';

                                                    return $buttons;
                                                },

                                            ],
                                        ],

                                ] ); ?>
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
                                    'action' => Url::to( [ 'command/list', 'base_id' => $base_id ] ),
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
                                    'placeholder' => Yii::t( 'app', 'Search command...' )
                                ] ) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php \yii\bootstrap\ActiveForm::end(); ?>
                <!--/ Predefined Views -->
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>