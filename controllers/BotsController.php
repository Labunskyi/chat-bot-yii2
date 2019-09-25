<?php

namespace app\controllers;


use app\models\Auth;
use app\models\Base;
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


class BotsController extends Controller
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
                        'actions'      => ['add', 'add-telegram', 'add-viber', 'setting', 'bot-view', 'edit', 'set-baseid' ],
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
     * @return string
     */
    public function actionAdd()
    {
        $baseAvailable = Base::find()->where( [ 'user_id' => Yii::$app->user->getId() ] )->count();
        return $this->render( 'addBots', compact('baseAvailable') );
    }

    /**
     * @param $base_id
     * @param $bot_id
     *
     * @return string|Response
     */
    public function actionSetting( $base_id, $bot_id )
    {
        if ( $bot = Bots::findOne( [ 'base_id' => $base_id, 'id' => $bot_id ] ) ) {
            if ( Yii::$app->request->isPost && $bot->load( Yii::$app->request->post() ) ) {
                $bot->setNewToken();
                if ( !$bot->errors ) {
                    Yii::$app->session->setFlash( 'success', Yii::t( 'app', 'Token success changed' ) );
                }
            }

            return $this->render( 'setting', compact( 'bot', 'base_id' ) );
        }

        return $this->redirect( [ 'bots/index' ] );
    }

    /**
     * @param $base_id
     *
     * @return string
     */
    public function actionBotView($base_id)
    {
        if($bots = Bots::findAll(['base_id' => $base_id, 'user_id' => Yii::$app->user->getId()])){
            return $this->render( 'index', compact('bots', 'base_id') );
        }

        return $this->redirect(['bots/index']);
    }

    /**
     * @return string|Response
     * @throws \yii\base\Exception
     */
    public function actionAddTelegram()
    {
        $model = new AddTelegramBotForm();

        if ( Yii::$app->request->isPost && $model->load( Yii::$app->request->post() ) ) {
            if ( $bot = $model->add() ) {
                Yii::$app->session->setFlash( 'success', Yii::t( 'app', 'Changes saved' ) );

                Yii::$app->session->set('base_id', $bot->base_id);
                return $this->redirect( [ 'reply/list', 'base_id' => $bot->base_id, 'hash' => Bots::idEncode($bot->id) ]  );
            } else {
                Yii::$app->session->setFlash( 'error', Yii::t( 'app', 'Error while saving' ) );
            }
        }

        return $this->render( 'addTelegram', compact( 'model' ) );
    }

    /**
     * @return string|Response
     * @throws \yii\base\Exception
     */
    public function actionAddViber()
    {
        $model = new AddViberBotForm();

        if ( Yii::$app->request->isPost && $model->load( Yii::$app->request->post() ) ) {
            if ( $bot = $model->add() ) {
                Yii::$app->session->setFlash( 'success', Yii::t( 'app', 'Changes saved' ) );

                Yii::$app->session->set('base_id', $bot->base_id);
                return $this->redirect( [ 'reply/list', 'base_id' => $bot->base_id, 'hash' => Bots::idEncode($bot->id) ]  );
            } else {
                Yii::$app->session->setFlash( 'error', Yii::t( 'app', 'Error while saving' ) );
            }
        }

        return $this->render( 'addViber', compact( 'model' ) );
    }

    /**
     * @param $id
     *
     * @return Response
     */
    public function actionSetBaseid($id)
    {
        if (!Base::isOwner($id)){
            $id = Base::findOne(['user_id' => Yii::$app->user->getId()])->id;
        }

        Yii::$app->session->set('base_id', $id);

        return $this->redirect(['bots/bot-view', 'base_id' => $id]);
    }
}
