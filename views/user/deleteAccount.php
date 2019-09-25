<?php
/**
 * @var $upModel  \app\models\forms\UploadAvatarForm
 * @var $model    \app\models\forms\EditForm
 * @var $this     \yii\web\View
 * @var $avatar   string
 * @var $form     ActiveForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\fileupload\FileUpload;
use yii\helpers\Url;
use app\models\User;

$this->title = Yii::t( 'app', 'Delete account' );

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Edit Profile'), 'url'=> ['user/edit']];
$this->params['breadcrumbs'][] = $this->title;



?>


<div class="row d-flex align-items-center justify-content-center">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="card ">
            <div class="card-content collapse show">
                <div class="card-body text-center">
                    <?php $form = ActiveForm::begin( [
                        'id'      => 'form-edit',
                        'options' => [ 'enctype' => 'multipart/form-data' ],
                    ] ); ?>
                    <div class="row">
                        <?= $form->field( $model, 'password', [ 'options' => [ 'class' => 'form-group col-12' ] ] )
                            ->passwordInput( ); ?>
                        <?= $form->field( $model, 'reason', [ 'options' => [ 'class' => 'form-group col-12' ] ] )
                            ->textarea( ); ?>
                    </div>

                    <div class="col-12 bs-callout-danger callout-border-left mt-1 p-1">
                        <strong><?= Yii::t( 'app', 'Attention!' ) ?></strong>
                        <p><?= Yii::t( 'app', 'Your account will be permanently deleted' ) ?><br>
                            <?= Yii::t( 'app', 'after click the button' )?> <b><?=Yii::t( 'app', 'Delete account' ) ?></b></p>
                        <?= Html::submitButton( Yii::t( 'app', 'Delete account' ),
                            [ 'class' => 'btn btn-danger', 'name' => 'save-edit' ] ) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>