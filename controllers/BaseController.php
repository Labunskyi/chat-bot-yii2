<?php

namespace app\controllers;


use app\models\Auth;
use app\models\Base;
use app\models\Bots;
use app\models\forms\AddTelegramBotForm;
use app\models\forms\AddFacebookPageForm;
use app\models\forms\AddViberBotForm;
use app\models\forms\SettingsForm;
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


class BaseController extends Controller
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
                        'actions'      => [ 'setting' ],
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
     * @return string|Response
     */
    public function actionSetting( $base_id )
    {
        if ( Base::isOwner( $base_id )  ) {
            $settings = new SettingsForm();

            if ( Yii::$app->request->isPost && $settings->load( Yii::$app->request->post() ) ) {
                if ( !$settings->change()) {
                    Yii::$app->session->setFlash( 'error', Yii::t( 'app', 'Error while saving' ) );
                } else {
                    Yii::$app->session->setFlash( 'success', Yii::t( 'app', 'Settings success saved' ) );
                }
            }

            return $this->render( 'setting', compact( 'settings', 'base_id' ) );
        }

        return $this->redirect( [ 'bots/index' ] );
    }
}
