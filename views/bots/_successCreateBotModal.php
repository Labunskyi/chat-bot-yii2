<?php
/**
 * @var $sBot \app\models\Bots
 */


use yii\helpers\Html;
use api\base\API;

$this->registerJs( '
    $("#success").modal({
        show: true
    });
' );
?>

<div class="modal fade text-left" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel9"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title white" id="myModalLabel9"><i class="la la-check"></i> <?=Yii::t('app', 'Bot successfully created')?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h5><i class="la la-cog"></i> <?=Yii::t('app', 'You can test it right now.')?></h5>
                <p><?=Yii::t('app', 'This is a short link that you can use on your website, sms, email, etc.')?></p>
                <h2 class="text-center"><a href="<?= $sBot->genLinkBot() ?>" target="_blank"><?= $sBot->genLinkBot() ?></a></h2>
                <hr>
                <h5><i class="la la-lightbulb-o"></i> <?=Yii::t('app', 'You can test it right now.')?></h5>
                <p><?=Yii::t('app', 'Fill in the base of answers and train the bot with new phrases!')?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-success" data-dismiss="modal"><?=Yii::t('app', 'Go to filling')?></button>
            </div>
        </div>
    </div>
</div>
