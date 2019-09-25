<?php

namespace app\controllers;

use Yii;
use app\models\forms\NewsletterForm;
use app\models\Newsletter;
use app\models\Base;
use app\models\search\CustomerSearch;
use app\models\search\NewsletterSearch;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class NewsletterController
 *
 * @package app\controllers
 */
class NewsletterController extends Controller
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
                        'actions'      => [ 'list', 'add', 'update-send-user', 'edit', 'delete' ],
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
    public function actionList( $base_id )
    {
        if ( Base::isOwner( $base_id ) ) {

            $searchModel = new NewsletterSearch();
            $dataProvider = $searchModel->search( Yii::$app->request->queryParams, $base_id );
            $data = new CustomerSearch();
            $tags = $data->countTags($base_id);
            $platforms = $data->countPlatforms($base_id);
            $bots = $data->countBots($base_id);

            return $this->render( 'list', compact( 'base_id','data', 'bots','platforms','tags', 'searchModel', 'dataProvider' ) );

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
            $model = new NewsletterForm();

            if ( Yii::$app->request->isPost && $model->load( Yii::$app->request->post() ) ) {
                if ( $model->add( $base_id ) ) {
                    Yii::$app->session->setFlash( 'success', Yii::t( 'app', 'Newsletter success added' ) );

                    return $this->redirect( [ 'newsletter/list', 'base_id' => $base_id ] );
                } else {
                    Yii::$app->session->setFlash( 'error', Yii::t( 'app', 'Error while saving' ) );
                }
            }

            return $this->render( 'edit', compact( 'model', 'base_id' ) );
        }

        return $this->redirect( Url::to( 'bots/index' ) );
    }

    /**
     * @param $base_id
     * @param $id
     * @return string|Response
     */
    public function actionEdit( $base_id, $id )
    {
        $newsletter = Newsletter::find()->where( [ 'id' => $id ] )->one();

        if ( $newsletter && Base::isOwner( $base_id ) ) {
            $model = new NewsletterForm();
            $model->loadModel( $newsletter );

            if ( Yii::$app->request->isPost && $model->load( Yii::$app->request->post() ) ) {
                if ( $model->edit( $newsletter ) ) {
                    Yii::$app->session->setFlash( 'success', Yii::t( 'app', 'Newsletter success edited' ) );

                    return $this->redirect( [ 'newsletter/list', 'base_id' => $base_id ] );
                } else {
                    Yii::$app->session->setFlash( 'error', Yii::t( 'app', 'Error while saving' ) );
                }
            }

            return $this->render( 'edit', compact( 'model', 'base_id' ) );
        }

        return $this->redirect( [ 'newsletter/list', 'base_id' => $base_id ] );
    }

    /**
     * @param $base_id
     * @param $id
     *
     * @return Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete( $base_id, $id )
    {
        $newsletter = Newsletter::find()->where( [ 'id' => $id ] )->one();

        if ( $newsletter && Base::isOwner( $base_id ) ) {
            if ( $newsletter->delete() ) {
                Yii::$app->session->setFlash( 'success', Yii::t( 'app', 'Newsletter success deleted' ) );
            } else {
                Yii::$app->session->setFlash( 'error', Yii::t( 'app', 'Error while delete' ) );
            }
        }

        return $this->redirect( [ 'newsletter/list', 'base_id' => $base_id ] );
    }

    /**
     * @param $base_id
     *
     * @return bool|int|string
     */
    public function actionUpdateSendUser( $base_id )
    {

        if(Base::isOwner($base_id)){

            $model = new NewsletterForm();
            $model->load(Yii::$app->request->post());
            $model->message = '1';
            $model->base_id = $base_id;
            $model->settings = [
                'platforms' => $model->platforms,
                'bots'      => $model->bots,
                'tags'      => $model->tags
            ];
            if($model->validate()){
                $count = $model->getFilterDataNewsLetter($model);
                return $count->count();
            }else{
                return 0;
            }
        }

        return false;
    }

}
