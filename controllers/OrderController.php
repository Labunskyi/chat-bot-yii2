<?php

namespace app\controllers;

use app\models\forms\ProductForm;
use app\models\Order;
use app\models\Product;
use app\models\search\OrderSearch;
use Yii;
use app\models\search\ProductSearch;
use app\models\Base;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class OrderController
 *
 * @package app\controllers
 */
class OrderController extends Controller
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
                        'actions'      => [ 'check-new-order', 'list', 'edit', 'delete' ],
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
            $searchModel = new OrderSearch();
            $dataProvider = $searchModel->search( Yii::$app->request->queryParams, $base_id );


            return $this->render( 'list', compact( 'base_id', 'searchModel', 'dataProvider' ) );
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
        $order = Order::findOne( $id );

        if ( $order && Base::isOwner( $base_id ) ) {

            if ( Yii::$app->request->isPost && $order->load( Yii::$app->request->post() ) ) {
                $order->save();
            }
            $products = $order->getProducts();

            return $this->render( 'edit', compact( 'order', 'products' ) );
        }

        return $this->redirect( [ 'order/list', 'base_id' => $base_id ] );
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post( 'id' );
        $base_id = Yii::$app->request->post( 'base_id' );
        $order = Order::findOne( $id );

        if ( $order && Base::isOwner( $base_id ) ) {
            $order->delete();

            return true;
        }

        return false;
    }

    /**
     * @param $base_id
     *
     * @return int
     */
    public function actionCheckNewOrder($base_id)
    {
        $outJson = 0;
        if ( Base::isOwner( $base_id ) ) {
            $orders = Order::findAll( [ 'base_id' => $base_id, 'status' => Order::STATUS_NEW ] );

            foreach ($orders as $order){
                $order->status = Order::STATUS_NEW_VIEWED;
                $order->save();
                $outJson++;
            }
        }

        return $outJson;
    }

}
