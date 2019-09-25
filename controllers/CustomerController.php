<?php

namespace app\controllers;


use app\models\Bots;
use app\models\Customer;
use app\models\Base;
use app\models\search\CustomerSearch;
use app\models\search\ReplySearch;
use app\models\forms\ReplyForm;
use app\widgets\HandlerHook;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class CustomerController
 *
 * @package app\controllers
 */
class CustomerController extends Controller
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
                        'actions'      => [ 'list', 'edit', 'link-profiles', 'un-link-profiles' ],
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
        if ( $bot = Bots::find()->where( [ 'base_id' => $base_id, 'user_id' => Yii::$app->user->identity->getId() ] )->one() ) {

            $searchModel = new CustomerSearch();

            $dataProvider = $searchModel->search( Yii::$app->request->queryParams, $bot->base_id );
            $tags = $searchModel->countTags($base_id);
            $platforms = $searchModel->countPlatforms($base_id);
            $bots = $searchModel->countBots($base_id);

            return $this->render( 'list', compact('bot','bots', 'searchModel', 'dataProvider', 'tags', 'platforms') );
        }


        return $this->redirect( [ 'bots/bot-view', 'base_id' => $base_id ] );
    }

    /**
     * @param $base_id
     * @param $id
     *
     * @return string|Response
     */
    public function actionEdit( $base_id, $id )
    {

        if ( ( $customers = Customer::find()->where( [ 'base_id' => $base_id, 'relation' => $id ] )->all() ) && Base::isOwner( $base_id ) ) {

            if ( Yii::$app->request->isPost ) {
                foreach ( $customers as $customer ) {
                    ;
                    if ( Yii::$app->request->post()['Customer']['id'] == $customer->id && $customer->load( Yii::$app->request->post() ) && $customer->validate() ) {
                        if ( $customer->save() ) {
                            Yii::$app->session->setFlash( 'success', Yii::t( 'app', 'Profile success edited' ) );
                            return $this->redirect( [ 'customer/list', 'base_id' => $base_id ] );
                        } else {
                            Yii::$app->session->setFlash( 'error', Yii::t( 'app', 'Error while saving' ) );
                        }
                    }
                }
            }


            return $this->render( 'edit', compact( 'customers', 'base_id' ) );
        }

        return $this->redirect( [ 'customer/list', 'base_id' => $base_id ] );
    }

    /**
     * @param $base_id
     *
     * @return string|Response
     */
    public function actionLinkProfiles( $base_id )
    {
        if ( Base::isOwner( $base_id ) && Yii::$app->request->isPost ) {
            $relationId = '';


            foreach ( json_decode( Yii::$app->request->getRawBody() ) as $id ) {
                if ( $customer = Customer::findOne( $id ) ) {
                    if ( !$relationId ) {
                        $relationId = $customer->genRelationId();
                    }

                    foreach ( Customer::findAll( [ 'relation' => $customer->relation ] ) as $item ) {
                        $item->relation = $relationId;
                        $item->save();
                    }

                    $customer->relation = $relationId;
                    $customer->save();
                }
            }
        }

        return 'false';
    }

    /**
     * @param        $base_id
     * @param int    $id
     * @param string $url
     *
     * @return string|Response
     */
    public function actionUnLinkProfiles( $base_id, $id = 0, $url = '' )
    {
        if ( Base::isOwner( $base_id ) && $id  ) {
            if ( $customer = Customer::findOne( $id ) ) {
                $customer->relation = $customer->id;
                $customer->save();
                return $this->redirect(Yii::$app->request->getReferrer());
            }
        }

        if ( Base::isOwner( $base_id ) && Yii::$app->request->isPost ) {
            foreach ( json_decode( Yii::$app->request->getRawBody() ) as $id ) {
                if($c = Customer::findOne($id)){
                    foreach ( Customer::findAll( [ 'relation' => $c->relation ] ) as $item ) {
                        $item->relation = $item->id;
                        $item->save();
                    }
                }
            }
        }

        return false;
    }

}
