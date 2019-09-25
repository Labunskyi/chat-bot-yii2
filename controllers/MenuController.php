<?php

namespace app\controllers;

use app\models\forms\MenuForm;
use app\models\forms\SettingsForm;
use app\models\Menu;
use app\models\search\MenuSearch;
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
class MenuController extends Controller
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
                        'actions'      => [ 'list', 'add', 'edit', 'delete' ],
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
     */
    public function actionList( $base_id )
    {
        if ( Base::isOwner( $base_id ) ) {

            $settings = new SettingsForm();
            if ( Yii::$app->request->isPost && $settings->load( Yii::$app->request->post() ) ) {
                if ( !$settings->save() || count($settings->errors)  ) {
                    Yii::$app->session->setFlash( 'error', Yii::t( 'app', 'Error while saving' ) );
                } else {
                    Yii::$app->session->setFlash( 'success', Yii::t( 'app', 'Settings success saved' ) );
                }
            }

            $searchModel = new MenuSearch();
            $dataProvider = $searchModel->search( Yii::$app->request->queryParams, $base_id );

            return $this->render( 'list', compact( 'base_id', 'searchModel', 'dataProvider', 'settings' ) );
        }

        return $this->redirect( Url::to( 'bots/index' ) );
    }

    /**
     * @param $base_id
     *
     * @return string|Response
     */
    public function actionAdd( $base_id )
    {
        if ( Base::isOwner( $base_id ) ) {

            $model = new MenuForm();
            if ( Yii::$app->request->isPost && $model->load( Yii::$app->request->post() ) ) {
                if ( $model->save() ) {
                    Yii::$app->session->setFlash( 'success', Yii::t( 'app', 'Menu success saved' ) );
                    return $this->redirect( [ 'menu/list', 'base_id' => $base_id ] );
                } else {
                    Yii::$app->session->setFlash( 'error', Yii::t( 'app', 'Error while saving' ) );
                }
            }

            return $this->render( 'add', compact( 'model', 'base_id' ) );
        }

        return $this->redirect( Url::to( 'bots/index' ) );
    }

    /**
     * @param $base_id
     * @param $id
     *
     * @return string|Response
     */
    public function actionEdit( $base_id, $id )
    {
        $menu = Menu::findOne($id);

        if ( $menu && Base::isOwner( $base_id ) ) {

            $model = new MenuForm();
            if ( Yii::$app->request->isPost && $model->load( Yii::$app->request->post() ) ) {
                if ( $model->save() ) {
                    Yii::$app->session->setFlash( 'success', Yii::t( 'app', 'Menu success saved' ) );
                    return $this->redirect( [ 'menu/list', 'base_id' => $base_id ] );
                } else {
                    Yii::$app->session->setFlash( 'error', Yii::t( 'app', 'Error while saving' ) );
                }
            }

            return $this->render( 'edit', compact( 'model', 'menu' ) );
        }

        return $this->redirect( [ 'menu/list', 'base_id' => $base_id ] );
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $base_id = Yii::$app->request->post('base_id');
        $menu = Menu::findOne($id);

        if ( $menu && Base::isOwner( $base_id ) ) {
            $menu->delete();
            return true;
        }

        return false;
    }

}
