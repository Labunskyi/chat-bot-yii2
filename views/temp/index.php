<?php
/**
 * @var $base_id                 integer
 * @var $this                    \yii\web\View
 * @var $buttonsList             array
 * @var $buttonsTelegramList     array
 * @var $messagesTelegramList    array
 * @var $buttonsViberList        array
 * @var $messagesViberList       array
 * @var $messagesList            array
 * @var $imagesList              array
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Setting;

$this->title = Yii::t( 'app', 'Templates' );
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    @media (min-width: 991px) {
        .tab-pane.active {
            display: flex;
        }
    }

    .nav.nav-tabs.nav-underline .nav-item.active a.nav-link:before {
        -webkit-transform: translate3d(0, 0, 0);
        -moz-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
    }
    .nav-vertical .nav-left.nav-tabs li.nav-item a.nav-link {
        width: 100%;
    }
    .nav-vertical .nav-left.nav-tabs li.nav-item.active a.nav-link {
        border: 1px solid #DDD;
        border-right: 0;
        border-radius: .25rem 0 0 .25rem;
        color: #4E5154;
    }
    input[type='file'] {
        display: block;
    }
    #tabImages img {
        width: 150px;
        height: auto;
    }
</style>
<div class="content-detached">
    <div class="content-body block-table">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field( $model, 'base_id', [ 'options' => [ 'class' => 'hidden' ] ] )->textInput(['value' => $base_id]); ?>
                    <div class="card-head">
                        <div class="card-header">
                            <h4 class="card-title"><?= $this->title ?></h4>
                            <div class="heading-elements">
                                <?= Html::submitButton( '<i class="ft-save"></i> ' . Yii::t( 'app', 'Save' ),
                                    [ 'class' => 'btn btn-success btn-min-width btn-sm mr-1 mb-1', 'type' => 'submit', 'name' => 'save' ] ) ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <!-- Common templates -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="nav-vertical">
                                                <ul class="nav nav-tabs nav-left">
                                                    <li class="nav-item active">
                                                        <a class="nav-link" id="tabButtons-tab" data-toggle="tab"
                                                           href="#tabButtons" aria-controls="tabButtons" aria-expanded="true"><i
                                                                    class="la la-keyboard-o"></i> <?= Yii::t( 'app', 'Buttons' ) ?>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="tabMessages-tab" data-toggle="tab"
                                                           href="#tabMessages" aria-controls="tabMessages" aria-expanded="false"><i
                                                                    class="la la-comments-o"></i> <?= Yii::t( 'app', 'Messages' ) ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content px-1">
                                                    <div role="tabpanel" class="tab-pane active" id="tabButtons"
                                                         aria-expanded="true" aria-labelledby="tabButtons-tab">
                                                        <div class="row col-12">
                                                            <?php foreach ( $buttonsList as $button ) { ?>
                                                                <div class="col-lg-4 col-xs-12">
                                                                    <?= $form->field( $model, $button,
                                                                        [ 'options' => [ 'class' => 'form-group col-12 m-0' ] ] )->textInput(); ?>
                                                                    <p class="col-12"><?=Yii::t('app', $button.'_desc')?></p>
                                                                    <hr>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div role="tabpanel" class="tab-pane" id="tabMessages" aria-expanded="false"
                                                         aria-labelledby="tabMessages-tab">
                                                        <div class="row col-12">
                                                            <?php $a = []; foreach ( $messagesList as $button ) { $a[$button] = '0';$a[$button.'_desc'] = '0';?>
                                                                <div class="col-lg-6 col-xs-12">
                                                                    <?= $form->field( $model, $button,
                                                                        [ 'options' => [ 'class' => 'form-group col-12' ] ] )->textarea(['rows' => 7]); ?>
                                                                    <p class="col-12"><?=Yii::t('app', $button.'_desc')?></p>
                                                                    <hr>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Common templates END -->
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </section>
    </div>
</div>