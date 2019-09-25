<?php
/**
 * @var $settings     \app\models\Bots
 * @var $base_id integer
 */


$this->title = Yii::t( 'app', 'General settings' );
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="content-detached content-left">
    <div class="content-body block-table">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <?php $form = \yii\bootstrap\ActiveForm::begin();
                            $admin_email = \app\models\Setting::getSetting($base_id, 'admin_email');
                            ?>
                            <?= $form->field( $settings, 'base_id', ['options' => ['class' => 'hidden']] )->textInput(['value' => $base_id]) ?>
                            <?= $form->field( $settings, 'admin_email' )->label( Yii::t( 'app', 'admin_email' ) )->textInput(['value' => $admin_email]) ?>
                            <?= \yii\helpers\Html::submitButton( '<i class="ft-save"></i> ' . Yii::t( 'app', 'Save Settings' ),
                                [ 'class' => 'btn btn-success full-width', 'name' => 'save' ] ) ?>
                            <?php \yii\bootstrap\ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>