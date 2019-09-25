<?php
/**
 * @var $bots \app\models\Bots|array|object
 * @var $bot \app\models\Bots
 * @var $base_id integer
 */


$this->title = Yii::t( 'app', 'Dashboard' );
$this->params['breadcrumbs'] = false;

?>
<div id="crypto-stats-3" class="row">
    <?php
    $cBots = count($bots);
    if(count($bots) > 3){
        $col = 4;
    }else{
        $col = 12 / count($bots);
    }
    //exit;
    foreach ($bots as $bot){ ?>
        <div class="col-lg-<?=$col?> col-12">
            <div class="card crypto-card-3 pull-up">
                <div class="card-content">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-2">
                                <img src="<?=\app\models\Bots::getBotAvatar( $bot->id )?>" class="rounded-circle img-border full-width box-shadow-2">
                            </div>
                            <div class="col-5 pl-2">
                                <h4><?=$bot->first_name?></h4>
                                <img src="<?=\app\models\Bots::getIconPlatform( $bot->platform )?>" class="rounded-circle img-border height-50">

                            </div>
                            <div class="col-5 text-right">
                                <h4><?=\Yii::t('app', 'Customers')?></h4>
                                <h6 class="success darken-4"><?=\app\models\Customer::find()->where(['bot_id' => $bot->id])->count()?> <i class="la la-user"></i></h6>
                            </div>
                        </div>
                    </div>
                    <div class="row p-1">
                        <div class="col-12 text-center">
                            <?=\Yii::t('app', 'Go to bot:')?> <a href="<?=$bot->genLinkBot()?>" target="_blank">@<?=$bot->username?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

</div>