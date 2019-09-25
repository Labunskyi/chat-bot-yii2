<?php

namespace app\controllers;

use app\models\forms\MenuForm;
use app\models\forms\SettingsForm;
use app\models\Menu;
use app\models\search\MenuSearch;
use app\models\Setting;
use Yii;
use app\models\Category;
use app\models\forms\CategoryForm;
use app\models\Base;
use app\models\search\CategorySearch;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class MenuController
 *
 * @package app\controllers
 */
class TempController extends Controller
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
                        'actions'      => [ 'index'],
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
     * @return string|Response
     * @throws \yii\base\Exception
     */
    public function actionIndex( $base_id )
    {
        if ( Base::isOwner( $base_id ) ) {

            $buttonsList = SettingsForm::getButtonsList();
            $messagesList = SettingsForm::getMessagesList();

            $model = new SettingsForm(['base_id' => $base_id]);
            if ( Yii::$app->request->isPost && $model->load( Yii::$app->request->post() ) ) {
                if ( !$model->save() ) {
                    Yii::$app->session->setFlash( 'error', Yii::t( 'app', 'Error while saving' ) );
                } else {
                    Yii::$app->session->setFlash( 'success', Yii::t( 'app', 'Settings success saved' ) );
                }
            }

            return $this->render( 'index',
                compact( 'base_id', 'model',
                    'buttonsList', 'messagesList'
                ) );
        }

        return $this->redirect( Url::to( 'bots/index' ) );
    }
}
