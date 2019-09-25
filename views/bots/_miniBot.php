<?php
/**
 * @var $model \app\models\Bots
 */
use yii\helpers\Html;
use api\base\API;
$avatar = is_file('bots/'.$model->platform_id.'/avatar.png')?'/web/bots/'.$model->platform_id.'/avatar.png':'/web/img/avatar/placeholder.png';
?>



<div class="col-xl-3 col-md-6 col-sm-12">
    <div class="card" style="height: 462.125px;">
        <div class="card-content">
            <div class="card-body">
                <img class="card-img img-fluid mb-1" src="<?=\yii\helpers\Url::to($avatar)?>">
                <h4 class="card-title"><?=$model->first_name?></h4>
                <p class="card-text">
                <i class="fa fa-meh-o"></i> <strong><?=Yii::t('app', 'UserName')?></strong> <a href="https://t.me/<?=$model->username?>" target="_blank">@<?=$model->username?></a>
                <i class="fa fa-sort-numeric-asc"></i> <strong><?=Yii::t('app', 'Telegram ID')?></strong> <?=$model->platform_id?>
                <i class="fa fa-clock-o"></i> <strong><?=Yii::t('app', 'Created')?></strong> <?=Yii::$app->formatter->asDatetime($model->created_at);?>
                <i class="fa fa-clock-o"></i> <strong><?=Yii::t('app', 'Status')?></strong> <?=$model->status?'<i class="fa fa-check" style="color: green;"></i>':'<i class="fa fa-times-circle" style="color: red;"></i>' ?>
                </p>
                <a href="<?=\yii\helpers\Url::to(['reply/list', 'bot_id' => $model->id])?>" class="btn btn-outline-teal">Answers</a>
            </div>
        </div>
    </div>
</div>