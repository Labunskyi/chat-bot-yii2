<?php
/**
 * @var $this     \yii\web\View
 * @var $form     ActiveForm
 * @var $base_id  integer
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Category;
use app\models\Helper;

$this->title = Yii::t( 'app', 'Add menu item' );
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
    .nav.nav-tabs.nav-underline .nav-item.active a.nav-link:before {
        -webkit-transform: translate3d(0, 0, 0);
        -moz-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
    }
    input[type="file"] {
        display: block;
    }
</style>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?= $this->title; ?></h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis font-medium-3"></i></a>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">
                    <div class="row">
                        <?= $form->field( $model, 'base_id' )->hiddenInput( [ 'value' => $base_id ] )->label( false ); ?>
                        <?= $form->field( $model, 'name', [ 'options' => [ 'class' => 'form-group col-12 ' ] ] )
                            ->label(Yii::t( 'app', 'Name button' )); ?>
                        <?= $form->field( $model, 'text', [ 'options' => [ 'class' => 'form-group col-12 ' ] ] )->textarea(['rows'=>10])
                            ->label(Yii::t( 'app', 'Text after click' )); ?>
                    </div>
                    <hr/>
                    <div class="row">
                        <?= $form->field( $model, 'sort', [ 'options' => [ 'class' => 'form-group col-12' ] ] )
                            ->textInput( ['type' => 'number'] ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-content collapse show">
                <div class="card-body">
                    <div class="col-12">
                        <?= Html::submitButton( '<i class="ft-save"></i> ' . Yii::t( 'app', 'Save' ),
                            [ 'class' => 'btn btn-success full-width', 'name' => 'save' ] ) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>