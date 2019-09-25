<?php
/**
 * @var $base_id         integer
 * @var $this            \yii\web\View
 * @var $searchModel     \app\models\Menu
 * @var $settings     \app\models\forms\SettingsForm
 * @var $dataProvider     \yii\data\ActiveDataProvider
 */

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Category;
use app\models\Helper;

$this->title = Yii::t( 'app', 'Menu list' );
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs( '
$( ".search_form input, .search_form select" ).change(function() {
    $("#search-form").submit();
});
$(\'body\').on(\'click\',\'a.del-menu\',function(e){
    e.preventDefault();
    
    var warning_text = "' . Yii::t( 'app', 'Are you sure you want to delete a menu item? All items associated with it will be deleted.' ) . '";

    var conf = confirm( warning_text );
    if(conf) {
        var id = $(this).attr(\'href\');
        var base_id = $(this).attr(\'data-base-id\');
        $.ajax({
            url: "' . \yii\helpers\Url::toRoute(['menu/delete'], true) . '",
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
$menu_line_items = \app\models\Setting::getSetting($base_id, 'menu_line_items');

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
                                    <a href="<?= Url::to( [ 'menu/add', 'base_id' => $base_id ] ) ?>"
                                       class="btn btn-success btn-min-width btn-sm box-shadow-3 mr-1 mb-1"><i
                                                class="fa fa-plus"></i> <?= Yii::t( 'app', 'Add Menu' ) ?></a>
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
                                                'label'     => Yii::t( 'app', 'Sort' ),
                                                'format'    => 'integer',
                                                'attribute' => 'sort',
                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Button' ),
                                                'format'    => 'raw',
                                                'attribute' => 'name',
                                                'value'     => function ( $searchModel ) {
                                                    return $searchModel->name;
                                                }
                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Text' ),
                                                'format'    => 'raw',
                                                'attribute' => 'name',
                                                'value'     => function ( $searchModel ) {
                                                        $text = $searchModel->text;
                                                        if($searchModel->function){
                                                            $text .=  "<div class='bs-callout-info p-1'><p>"
                                                                .Yii::t( 'app', 'This button use program function' ).
                                                                "</p></div>";
                                                        }
                                                    return $text;
                                                }
                                            ],
                                            [
                                                'label'  => Yii::t( 'app', 'Actions' ),
                                                'format' => 'raw',
                                                'value'  => function ( $searchModel ) {
                                                    $buttons = '';
                                                    $buttons = '<div class="form-group text-center"><div class="btn-group btn-group-sm">';

                                                    $buttons .= Html::a( '<i class="la la-edit" aria-hidden="true"></i>',
                                                            Url::to( [ 'menu/edit', 'base_id' => $searchModel->base_id, 'id' => $searchModel->id ] ),
                                                            [ 'class' => 'btn btn-info' ] );
                                                    if(! $searchModel->function) {
                                                         $buttons .= Html::a( '<i class="la la-trash" aria-hidden="true"></i>',
                                                             $searchModel->id,
                                                             [ 'class' => 'btn btn-danger del-menu', 'data-base-id' => $searchModel->base_id ] );
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
                <div class="card fix-form">
                    <div class="card-body border-top-blue-grey border-top-lighten-5">
                        <div class="bug-list-search">
                            <div class="bug-list-search-content">
                                <?php $form = \yii\bootstrap\ActiveForm::begin( [
                                    'method' => 'get',
                                    'id'     => 'search-form',
                                    'action' => Url::to( [ 'menu/list', 'base_id' => $base_id ] ),
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
                                    'placeholder' => Yii::t( 'app', 'Search menu...' )
                                ] ) ?>
                                <?php \yii\bootstrap\ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Predefined Views -->
                <div class="card fix-form">
                    <!-- contacts search -->

                    <div class="card-body border-top-blue-grey border-top-lighten-5">
                        <div class="bug-list-search">
                            <div class="form-group row">
                                <h4><?=Yii::t( 'app', 'Example your menu in bot' )?></h4>
                                <?=\app\widgets\MenuButtons::gen($dataProvider->models,$menu_line_items)?>
                            </div>
                            <div class="mt-1">
                                <?php $form = \yii\bootstrap\ActiveForm::begin( [
                                    'method' => 'post',
                                    'action' => Url::to( [ 'menu/list', 'base_id' => $base_id ] ),
                                ] );
                                $menu_line_items = \app\models\Setting::getSetting($base_id, 'menu_line_items')
                                ?>
                                <?= $form->field( $settings, 'base_id', ['options' => ['class' => 'hidden']] )->textInput(['value' => $base_id]) ?>
                                <?= $form->field( $settings, 'menu_line_items' )->label( Yii::t( 'app', 'Buttons in line' ) )->textInput(['type' => 'number', 'value' => $menu_line_items]) ?>
                                <?= Html::submitButton( '<i class="ft-save"></i> ' . Yii::t( 'app', 'Save Settings' ),
                                    [ 'class' => 'btn btn-success full-width', 'name' => 'save' ] ) ?>
                                <?php \yii\bootstrap\ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>

                </div>
                <!--/ Predefined Views -->
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>