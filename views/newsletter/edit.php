<?php
/**
 * @var $model     \app\models\forms\NewsletterForm
 * @var $this      \yii\web\View
 * @var $form      ActiveForm
 * @var $reply     \app\models\Reply
 * @var $base_id   string
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = !isset( $model->id ) ? Yii::t( 'app', 'Add Newsletter' ) : Yii::t( 'app', 'Edit Newsletter' );
$this->params['breadcrumbs'][] = [
    'label' => Yii::t( 'app', 'Newsletter list' ), 'url' => [ 'newsletter/list', 'base_id' => $base_id ],
];
$this->params['breadcrumbs'][] = $this->title;
$update_send_user = \yii\helpers\Url::toRoute(['newsletter/update-send-user', 'base_id' => $base_id]);
$this->registerJs( '
$( ".checkSettings" ).change(function() {
    var data = $("#form-edit").serialize()
    $.ajax({
            url: "' . $update_send_user . '",             
            type: "POST",
            data:  data,
            success: function (data) { 
                $("span.update-send-user").html(data);
                if(data === "0"){
                    $("button.btn").attr("disabled", "true");
                }else{
                    $("button.btn").removeAttr("disabled");
                }
            }               
            });
});
' );

?>


<section id="description" class="card">
    <div class="card-content">
        <div class="card-body">
            <div class="card-text">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                <?php $form = ActiveForm::begin( [
                                    'id'      => 'form-edit',
                                    'options' => [ 'enctype' => 'multipart/form-data' ],
                                ] ); ?>

                                <div class="row">
                                    <?= $form->field( $model, 'message',
                                        [ 'options' => [ 'class' => 'form-group col-lg-12' ] ] )
                                        ->textarea( [ 'rows' => 10 ] ); ?>


                                    <?php
                                    $t_list = \app\models\forms\NewsletterForm::tagsArray( $base_id );
                                    $p_list = \app\models\forms\NewsletterForm::userPlatformsArray( $base_id );
                                    $b_list = \app\models\forms\NewsletterForm::botsArray( $base_id );
                                    
                                    if ( !$model->tags ) {
                                        $t_checked = [];
                                        foreach ( $t_list as $key => $value ) {
                                            $t_checked[] = $key;
                                        }
                                        $model->tags = $t_checked;
                                    }
                                    if ( !$model->platforms ) {
                                        foreach ( $p_list as $key => $value ) {
                                            $p_checked[] = $key;
                                        }
                                        $model->platforms = $p_checked;
                                    }
                                    if ( !$model->bots ) {
                                        foreach ( $b_list as $key => $value ) {
                                            $b_checked[] = $key;
                                        }
                                        $model->bots = $b_checked;
                                    }

                                    $model->base_id = $base_id;
                                    if(!$model->settings){
                                        $model->settings = [
                                            'platforms' => $model->platforms,
                                            'bots'      => $model->bots,
                                            'tags'      => $model->tags,
                                        ];
                                    }

                                    ?>
                                    <div class="col-12 text-center">
                                        <button type="button" class="btn btn-info"><?= Yii::t( 'app',
                                                'Will be sent' ) ?> <span class="update-send-user"><?= $model->getFilterDataNewsLetter( $model )->count() ?></span> <i class="la la-user"></i></button>
                                    </div>

                                    <?= $form->field( $model, 'tags',
                                        [ 'options' => [ 'class' => 'form-group col-lg-3 checkSettings' ] ] )
                                        ->checkboxList( $t_list ); ?>

                                    <?= $form->field( $model, 'platforms',
                                        [ 'options' => [ 'class' => 'form-group col-lg-3 checkSettings' ] ] )
                                        ->checkboxList( $p_list ); ?>

                                    <?= $form->field( $model, 'bots',
                                        [ 'options' => [ 'class' => 'form-group col-lg-3 checkSettings' ] ] )
                                        ->checkboxList( $b_list ); ?>

                                    <?= $form->field( $model, 'status',
                                        [ 'options' => [ 'class' => 'form-group col-lg-3' ] ] )
                                        ->dropDownList( \app\models\forms\NewsletterForm::statuses() ) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group" style="margin: 0;">
                            <?= Html::submitButton( isset( $model->id ) ? '<i class="la la-save"></i> ' . Yii::t( 'app',
                                    'Save Newsletter' ) : '<i class="la la-plus-circle"></i> ' . Yii::t( 'app',
                                    'Add Newsletter' ),
                                [ 'class' => 'btn btn-success full-width', 'name' => 'save-edit' ] ) ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>