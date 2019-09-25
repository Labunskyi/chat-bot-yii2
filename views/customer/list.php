<?php
/**
 * @var $bot               \app\models\Bots
 * @var $searchModel       \app\models\search\CustomerSearch
 * @var $tags              array
 * @var $bots              array
 * @var $platforms         array
 * @var $this              \yii\web\View
 */

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Bots;

$this->title = Yii::t( 'app', 'Customers' );
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin( [ 'id' => 'customers' ] );
$link_ajax_url = Url::toRoute( [ 'customer/link-profiles', 'base_id' => $bot->base_id ] );
$unlink_ajax_url = Url::toRoute( [ 'customer/un-link-profiles', 'base_id' => $bot->base_id ] );
$this->registerJs( '
$( "#customersearch-q" ).change(function() {
    $("#search-form").submit();
});

$( ".checktag input" ).change(function() {
    $("#search-form").submit();
});

$( "#link_ptofile" ).click(function() {
     var selected_keys = $("#customers").yiiGridView("getSelectedRows");
     if(selected_keys.length > 1){
          $.ajax({
            url: "' . $link_ajax_url . '",             
            type: "POST",
            data:  JSON.stringify(selected_keys),
            success: function (data) { 
                $.pjax.reload({container:"#customers"});
            }               
            });
     }else{
     	swal("' . Yii::t( 'app', 'Warning!' ) . '", "' . Yii::t( 'app', 'You must select at least 2 users' ) . '", "warning");
     }
});

$( "#un_link_ptofile" ).click(function() {
     var selected_keys = $("#customers").yiiGridView("getSelectedRows");
     if(selected_keys.length >= 1){
          $.ajax({
            url: "' . $unlink_ajax_url . '",             
            type: "POST",
            data:  JSON.stringify(selected_keys),
            success: function (data) { 
                $.pjax.reload({container:"#customers"});
            }               
        });
     }else{
     	swal("' . Yii::t( 'app', 'Warning!' ) . '", "' . Yii::t( 'app', 'You must select at least 1 rows' ) . '", "warning");
     }
});

$(\'.skin-flat input\').iCheck({
        checkboxClass: \'icheckbox_flat-green\',
        radioClass: \'iradio_flat-green\'
    });
    $(\'.skin-square input\').iCheck({
        checkboxClass: \'icheckbox_square-blue\',
        radioClass: \'iradio_square-blue\',
    });
    
' );


?>

    <div class="content-detached content-left">
        <div class="content-body block-table">
            <section class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-head">
                            <div class="card-header">
                                <h4 class="card-title"><?= Yii::t( 'app', 'Customers' ) ?></h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <span class="dropdown">
                                          <button id="btnSearchDrop2" type="button" data-toggle="dropdown"
                                                  aria-haspopup="true" aria-expanded="true"
                                                  class="btn btn-info btn-sm dropdown-toggle dropdown-menu-right"><i
                                                      class="ft-settings"></i></button>
                                          <span aria-labelledby="btnSearchDrop2"
                                                class="dropdown-menu mt-1 dropdown-menu-right">
                                                 <a href="#" id="link_ptofile" class="dropdown-item green"><i
                                                             class="fa fa-link " aria-hidden="true"></i>
                                                     <?= Yii::t( 'app',
                                                         'Link profiles' ) ?></a>
                                              <a href="#" id="un_link_ptofile" class="dropdown-item red"><i
                                                          class="fa fa-chain-broken" aria-hidden="true"></i>
                                                  <?= Yii::t( 'app',
                                                      'Unlink profiles' ) ?></a>
                                          </span>
                                    </span>
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
                                    'id'               => 'customers',
                                    'filterSelector'   => 'select[name="per-page"]',
                                    'headerRowOptions' => [ 'class' => 'row-column-middle' ],
                                    'rowOptions'       => [ 'class' => 'row-column-middle' ],
                                    'layout'           => '<div class="row mb-1"><div class="col-lg-2">' . $pageSizeWidget . '</div><div class="col-lg-4">{summary}</div><div class="col-lg-6 text-right">{pager}</div></div>{items}<div class="row"><div class="col-lg-6">{summary}</div><div class="col-lg-6 text-right">{pager}</div></div>',
                                    'columns'          =>
                                        [
                                            [
                                                'class'           => 'yii\grid\CheckboxColumn',
                                                'multiple'        => false,
                                                'checkboxOptions' => [ 'id' => 'customers' ],
                                                'contentOptions'  => [ 'class' => 'text-left tdCheckbox skin skin-flat hover ' ],
                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Name' ),
                                                'attribute' => 'first_name',
                                                'format'    => 'raw',
                                                'value'     => function ( $searchModel ) {
                                                    if ( $searchModel->relation ) {
                                                        $customers = \app\models\Customer::findAll( [ 'relation' => $searchModel->relation ] );
                                                    } else {
                                                        $customers = \app\models\Customer::findAll( $searchModel );
                                                    }

                                                    $count = 0;
                                                    $media = '';
                                                    foreach ( $customers as $customer ) {
                                                        $names = $customer->first_name . ' ' . $customer->last_name . '<br>';
                                                        $avatars = '<img src="' . $customer->getAvatar() . '" alt="avatar"><br>';
                                                        $media .= '
                                                    <div class="media">
                                                        <div class="media-left pr-1">
                                                            <span class="avatar avatar-sm avatar-busy rounded-circle">
                                                                ' . $avatars . '
                                                            </span>
                                                        </div>
                                                        <div class="media-body media-middle">
                                                            <a href="' . Url::to( [ 'customer/edit', 'base_id' => $customer->base_id, 'id' => $customer->relation ] ) . '" class="media-heading">' . $names . '</a>
                                                        </div>
                                                      </div>
                                                    ';
                                                        $count++;
                                                        if ( count( $customers ) != $count ) {
                                                            $media .= '<hr>';
                                                        }
                                                    }

                                                    return $media;
                                                },

                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Chats' ),
                                                'attribute' => 'platform',
                                                'format'    => 'raw',
                                                'value'     => function ( $searchModel ) {

                                                    if ( $searchModel->relation ) {
                                                        $customers = \app\models\Customer::findAll( [ 'relation' => $searchModel->relation ] );
                                                    } else {
                                                        $customers = \app\models\Customer::findAll( $searchModel );
                                                    }
                                                    $media = '';
                                                    $count = 0;
                                                    foreach ( $customers as $customer ) {
                                                        $media .= '
                                                        
                                                        <ul class="list-unstyled users-list m-0">
                                                            <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="' . \app\models\Customer::platfomrs()[ $customer->platform ] . '" class="avatar avatar-sm pull-up">
                                                              <img class="media-object rounded-circle" src="' . Bots::getIconPlatform( $customer->platform ) . '" alt="Avatar">
                                                            </li>
                                                            <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="' . Bots::findOne( $customer->bot_id )->first_name . '" class="avatar avatar-sm pull-up" aria-describedby="tooltip972801">
                                                              <img class="media-object rounded-circle" src="' . Bots::getBotAvatar( $customer->bot_id ) . '" alt="Avatar">
                                                            </li>
                                                          </ul>';
                                                        $count++;
                                                        if ( count( $customers ) != $count ) {
                                                            $media .= '<hr>';
                                                        }
                                                    }


                                                    return $media;
                                                },

                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Email' ),
                                                'attribute' => 'email',
                                                'format'    => 'raw',
                                                'value'     => function ( $searchModel ) {

                                                    if ( $searchModel->relation ) {
                                                        $customers = \app\models\Customer::findAll( [ 'relation' => $searchModel->relation ] );
                                                    } else {
                                                        $customers = \app\models\Customer::findAll( $searchModel );
                                                    }
                                                    $media = '';
                                                    $count = 0;
                                                    foreach ( $customers as $customer ) {
                                                        $media .= !empty( $customer->email ) ? $customer->email : Yii::t( 'app',
                                                            'Empty' );

                                                        $count++;
                                                        if ( count( $customers ) != $count ) {
                                                            $media .= '<hr>';
                                                        }
                                                    }


                                                    return $media;
                                                },

                                            ],
                                            [
                                                'label'     => Yii::t( 'app', 'Phone' ),
                                                'attribute' => 'phone',
                                                'format'    => 'raw',
                                                'value'     => function ( $searchModel ) {
                                                    if ( $searchModel->relation ) {
                                                        $customers = \app\models\Customer::findAll( [ 'relation' => $searchModel->relation ] );
                                                    } else {
                                                        $customers = \app\models\Customer::findAll( $searchModel );
                                                    }
                                                    $media = '';
                                                    $count = 0;
                                                    foreach ( $customers as $customer ) {
                                                        $media .= !empty( $customer->phone ) ? $customer->phone : Yii::t( 'app',
                                                            'Empty' );

                                                        $count++;
                                                        if ( count( $customers ) != $count ) {
                                                            $media .= '<hr>';
                                                        }
                                                    }


                                                    return $media;
                                                },

                                            ],
                                            [
                                                'label'  => Yii::t( 'app', 'Actions' ),
                                                'format' => 'raw',
                                                'value'  => function ( $searchModel ) {
                                                    $unlinks = '';

                                                    foreach ( $customers = \app\models\Customer::findAll( [ 'relation' => $searchModel->relation ] ) as $item ) {
                                                        if ( count( $customers ) > 1 ) {
                                                            $unlinks .= '<a href="' . Url::to( [ 'customer/un-link-profiles', 'base_id' => $item->base_id, 'id' => $item->id, 'url' => '' ],
                                                                    true ) . '" class="dropdown-item red"><i class="fa fa-chain-broken" aria-hidden="true"></i> ' . Yii::t( 'app',
                                                                    'Unlink' ) . ' ' . $item->first_name . ' <img class="media-object rounded-circle avatar-sm" src="' . Bots::getIconPlatform( $item->platform ) . '" alt="Avatar"></a>';
                                                        }
                                                    }
                                                    $media = '
                                                    <span class="dropdown">
                                                        <button id="btnSearchDrop2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="btn btn-info btn-sm dropdown-toggle dropdown-menu-left"><i class="ft-settings"></i></button>
                                                        <span aria-labelledby="btnSearchDrop2" class="dropdown-menu mt-1 dropdown-menu-left">
                                                          <a href="' . Url::to( [ 'customer/edit', 'base_id' => $searchModel->base_id, 'id' => $searchModel->relation ] ) . '" class="dropdown-item"><i class="ft-edit-2"></i> ' . Yii::t( 'app',
                                                            'Edit' ) . '</a>
                                                            ' . $unlinks . '
                                                        </span>
                                                    </span>
                                                    ';

                                                    return $media;
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
                                    'action' => Url::to( [ 'customer/list', 'base_id' => $bot->base_id ] ),
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
                                    'placeholder' => Yii::t( 'app', 'Search Customers...' ),
                                ] ) ?>
                            </div>
                        </div>
                    </div>
                    <?php if (($linked_p = $searchModel->countLinkedProfiles( $bot->base_id ))) { ?>
                        <div class="card-body pb-0 pt-0">
                            <?= $form->field( $searchModel, 'linked' )
                                ->checkbox( [ 'template' => "<div class=\"col-12 checktag block-element\">{input}{label}</div>\n" ] )
                                ->label( Yii::t( 'app',
                                        'Linked profiles' ) . " <span class=\"badge badge-dark  badge-pill float-right position-absolute\" style=\"right:10px; top:0;\">" . $linked_p . "</span>" ) ?>
                        </div>
                    <?php } ?>
                    <!-- /contacts search -->
                    <?php if ( count( $platforms ) > 1 ) { ?>
                        <!-- platforms-->

                        <div class="card-body pb-0 pt-0">
                            <p class="lead"><?= Yii::t( 'app', 'Platforms' ) ?></p>
                            <?php foreach ( $platforms as $key => $value ) { ?>
                                <?php if ( $value < 2 ) {
                                    $color = 'danger';
                                } elseif ( $value < 4 ) {
                                    $color = 'warning';
                                } elseif ( $value <=> 9 ) {
                                    $color = 'success';
                                } else {
                                    $color = 'primary';
                                }
                                ?>

                                <?= $form->field( $searchModel, 'platf[' . $key . ']' )
                                    ->checkbox( [ 'template' => "<div class=\"col-12 checktag block-element\">{input}{label}</div>\n" ] )
                                    ->label( $key . "<span class=\"badge badge-$color  badge-pill float-right position-absolute\" style=\"right:10px;\">$value</span>" ) ?>


                            <?php } ?>

                        </div>
                        <!--/ platforms-->
                    <?php }
                    if ( count( $bots ) > 1 ) { ?>
                        <!-- platforms-->

                        <div class="card-body pb-0 pt-0">
                            <p class="lead"><?= Yii::t( 'app', 'Bots' ) ?></p>
                            <?php foreach ( $bots as $key => $value ) { ?>
                                <?php if ( $value < 2 ) {
                                    $color = 'danger';
                                } elseif ( $value < 4 ) {
                                    $color = 'warning';
                                } elseif ( $value <=> 9 ) {
                                    $color = 'success';
                                } else {
                                    $color = 'primary';
                                }
                                ?>

                                <?= $form->field( $searchModel, 'bots[' . $key . ']' )
                                    ->checkbox( [ 'template' => "<div class=\"col-12 checktag block-element\">{input}{label}</div>\n" ] )
                                    ->label(  Bots::findOne($key)->first_name . "<span class=\"badge badge-$color  badge-pill float-right position-absolute\" style=\"right:10px;\">$value</span>" ) ?>


                            <?php } ?>

                        </div>
                        <!--/ platforms-->
                    <?php } ?>
                        <!-- tags-->
                        <div class="card-body pt-0">
                            <p class="lead"><?= Yii::t( 'app', 'Popular tags' ) ?></p>

                            <?php foreach ( $tags as $key => $value ) { ?>
                                <?php if ( $value < 2 ) {
                                    $color = 'danger';
                                } elseif ( $value < 4 ) {
                                    $color = 'warning';
                                } elseif ( $value <=> 9 ) {
                                    $color = 'success';
                                } else {
                                    $color = 'primary';
                                }
                                ?>
                                <?= $form->field( $searchModel, 'tag[' . $key . ']' )
                                    ->checkbox( [ 'template' => "<div class=\"col-12 checktag block-element\">{input}{label}</div>\n" ] )
                                    ->label( $key . "<span class=\"badge badge-$color  badge-pill float-right position-absolute\" style=\"right:10px;\">$value</span>" ) ?>

                            <?php } ?>

                        </div>
                        <!--/ tags-->
                </div>
                <?php \yii\bootstrap\ActiveForm::end(); ?>
                <!--/ Predefined Views -->
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>