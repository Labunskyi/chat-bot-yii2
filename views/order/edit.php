<?php
/**
 * @var $this       \yii\web\View
 * @var $form       ActiveForm
 * @var $base_id    integer
 * @var $order      \app\models\Order
 * @var $product    \app\models\OrderProduct
 * @var $products   \app\models\OrderProduct
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Category;
use app\models\Product;
use app\models\Helper;

$this->title = Yii::t( 'app', 'Order' ) . ' #' . $order->id;
$this->params['breadcrumbs'][] = $this->title;

?>

    <style>
        .nav.nav-tabs.nav-underline .nav-item.active a.nav-link:before {
            -webkit-transform: translate3d(0, 0, 0);
            -moz-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
        }

        input[type="file"] {
            display: block;
        }

        img {
            max-height: 150px;
            max-width: 150px;
        }
    </style>


    <div class="sidebar-detached sidebar-left">
        <div class="sidebar">
            <div class="bug-list-sidebar-content">
                <div class="card fix-form">
                    <div class="card-body border-top-blue-grey border-top-lighten-5 p-0">
                        <div class="bug-list-search">
                            <div class="bug-list-search-content">
                                <div class="row p-0 m-0">
                                    <div class="col-12 p-0 m-0">
                                        <table class="table table-striped table-bordered m-0">
                                            <tr>
                                                <td><b><?= Yii::t( 'app', 'Name' ) ?></b></td>
                                                <td><?= $order->name ?></td>
                                            </tr>
                                            <tr>
                                                <td><b><?= Yii::t( 'app', 'Phone' ) ?></b></td>
                                                <td><?= $order->phone ?></td>
                                            </tr>
                                            <tr>
                                                <td><b><?= Yii::t( 'app', 'Delivery Method' ) ?></b></td>
                                                <td><?= $order->getDeliveryMethodName() ?></td>
                                            </tr>
                                            <tr>
                                                <td><b><?= Yii::t( 'app', 'Delivery Point' ) ?></b></td>
                                                <td><?= $order->getDeliveryPoint() ?></td>
                                            </tr>
                                            <tr>
                                                <td><b><?= Yii::t( 'app', 'Delivery Address' ) ?></b></td>
                                                <td><?= $order->delivery_address ?></td>
                                            </tr>
                                            <tr>
                                                <td><b><?= Yii::t( 'app', 'Payment Method' ) ?></b></td>
                                                <td><?= $order->getPaymentMethodName() ?></td>
                                            </tr>
                                            <tr>
                                                <td><b><?= Yii::t( 'app', 'Gift Cart' ) ?></b></td>
                                                <td><?= \app\models\Order::getGiftCartStatus()[$order->gift_cart] ?></td>
                                            </tr>
                                            <tr>
                                                <td><b><?= Yii::t( 'app', 'Status' ) ?></b></td>
                                                <td>
                                                    <?php $form = ActiveForm::begin(); ?>
                                                    <?= $order->getStatus(); ?>
                                                    <?= $form->field($order,'status')->dropDownList(\app\models\Order::getStatusNames())->label('',['class' => 'hidden'])?>
                                                    <?= Html::submitButton( '<i class="la la-save"></i> ' . Yii::t( 'app', 'Save' ),
                                                        [ 'class' => 'btn btn-success full-width' ] ) ?>

                                                    <?php ActiveForm::end(); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h3><?=Yii::t('app', 'Products')?></h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th><?= Yii::t( 'app', 'id' ) ?></th>
                                        <th><?= Yii::t( 'app', 'Nameprod' ) ?></th>
                                        <th><?= Yii::t( 'app', 'Quantity' ) ?></th>
                                        <th><?= Yii::t( 'app', 'Price' ) ?></th>
                                        <th><?= Yii::t( 'app', 'Sum' ) ?></th>
                                    </tr>
                                    <?php foreach ( $products as $product ) { ?>
                                        <tr>
                                            <td><?= $product->id ?></td>
                                            <td><?= $product->product_name ?></td>
                                            <td><?= $product->quantity ?></td>
                                            <td><?= $product->price ?></td>
                                            <td><?= $product->getTotalPrice() ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2" class="text-right"><b><?=Yii::t('app', 'Total Quantity')?></b></td>
                                        <td><b><?= $order->calcOrder()['totalQuantity'] ?></b></td>
                                        <td><b><?=Yii::t('app', 'Total Sum')?></b></td>
                                        <td><b><?= $order->calcOrder()['totalSumm'] ?></b></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

