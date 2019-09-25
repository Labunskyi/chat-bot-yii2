<?php

namespace app\controllers;


use app\models\Auth;
use app\models\BaseReply;
use app\models\Bots;
use app\models\forms\AddTelegramBotForm;
use app\models\forms\AddFacebookPageForm;
use app\models\forms\AddViberBotForm;
use app\widgets\FacebookToken;
use Facebook\FacebookClient;
use Yii;
use yii\authclient\clients\Facebook;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\forms\EditForm;
use app\models\forms\UploadAvatarForm;
use yii\authclient\AuthAction;


class ChatController extends Controller
{
    public $layout = '@app/views/layouts/inner.php';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions'      => [ 'list' ],
                        'allow'        => true,
                        'roles'        => [ '@' ],
                        'denyCallback' => function ( $rule, $action ) {
                            return $action->controller->redirect( [ 'user/login' ] );
                        },
                    ],
                ],

            ],
        ];
    }

    /**
     * @param $base_id
     *
     * @return string
     */
    public function actionList($base_id)
    {
        if($bots = Bots::findAll(['base_id' => $base_id, 'user_id' => Yii::$app->user->getId()])){
            return $this->render( 'index', compact('bots') );
        }

        //return $this->redirect(['bots/index']);
    }
}
