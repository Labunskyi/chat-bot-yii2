<?php
/**
 * @var $bot     \app\models\Bots
 * @var $base_id integer
 */


$this->title = Yii::t( 'app', 'Setting bot' ) . ' ' . $bot->first_name . ' (' . $bot->getPlatformName() . ')';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="content-detached content-left">
    <div class="content-body block-table">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <h4>
                                <?= Yii::t( 'app', 'Info for bot' ) ?>
                            </h4>
                            <dl class="row mt-2">
                                <dt class="col-sm-3"><?= Yii::t( 'app', 'Name' ) ?></dt>
                                <dd class="col-sm-9"><?= $bot->first_name ?></dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3"><?= Yii::t( 'app', 'Username' ) ?></dt>
                                <dd class="col-sm-9"><a href="<?=$bot->genLinkBot()?>" target="_blank">@<?=$bot->username?></a></dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3"><?= Yii::t( 'app', 'Platform' ) ?></dt>
                                <dd class="col-sm-9"><?= $bot->getPlatformName() ?></dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3"><?= Yii::t( 'app', 'ID' ) ?></dt>
                                <dd class="col-sm-9"><?= $bot->platform_id ?></dd>
                            </dl>
                            <?php if ( $bot->platform == \app\models\Bots::PLATFORM_TELEGRAM && ( $webhook = $bot->getWebHookInfo() ) ) { ?>
                                <dl class="row">
                                    <dt class="col-sm-3"><?= Yii::t( 'app', 'Last Error Text')?></dt>
                                    <dd class="col-sm-9"><?= $webhook->getLastErrorMessage() ?? 'none' ?></dd>
                                </dl>
                                <dl class="row">
                                    <dt class="col-sm-3"><?= Yii::t( 'app', 'Last Error Date')?></dt>
                                    <dd class="col-sm-9"><?= $webhook->getLastErrorDate() ? date( 'Y/m/d H:i:s',
                                            $webhook->getLastErrorDate() ) : 'none' ?></dd>
                                </dl>
                                <dl class="row">
                                    <dt class="col-sm-3"><?= Yii::t( 'app', 'Max Connections per 1 moment')?></dt>
                                    <dd class="col-sm-9"><?= $webhook->getMaxConnections() ?></dd>
                                </dl>
                                <dl class="row">
                                    <dt class="col-sm-3"><?= Yii::t( 'app', 'Pending Update Count')?></dt>
                                    <dd class="col-sm-9"><?= $webhook->getPendingUpdateCount() ?></dd>
                                </dl>
                            <?php } ?>
                            <hr>
                            <h4><?= Yii::t( 'app', 'Change token bot' ) ?></h4>
                            <p><?= Yii::t( 'app',
                                    'If you want to update it in our service, otherwise your bot will not work.' ) ?></p>
                            <p><?= Yii::t( 'app', 'Enter a new token in the box below.' ) ?></p>
                            <?php $form = \yii\bootstrap\ActiveForm::begin() ?>
                            <?= $form->field( $bot, 'token' )->passwordInput() ?>
                            <?= \yii\helpers\Html::submitButton( '<i class="ft-save"></i> ' . Yii::t( 'app',
                                    'Set new token' ),
                                [ 'class' => 'btn btn-success full-width', 'name' => 'save' ] ) ?>
                            <?php \yii\bootstrap\ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>