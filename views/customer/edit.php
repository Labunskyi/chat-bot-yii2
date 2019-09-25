<?php
/**
 * @var $model        \app\models\Reply
 * @var $this         \yii\web\View
 * @var $form         ActiveForm
 * @var $customers    \app\models\Customer
 * @var $customer     \app\models\Customer
 * @var $base_id      string
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t( 'app', 'Profile' ) . ' ' . $customers[0]->first_name . ' ' . $customers[0]->last_name;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t( 'app', 'Customers' ), 'url' => [ 'customer/list', 'base_id' => $base_id ],
];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php foreach ( $customers as $customer ) { ?>

    <div id="user-profile">
        <div class="row">
            <div class="col-12">
                <div class="card profile-with-cover">
                    <div class="card-img-top img-fluid bg-cover height-300"
                         style="background: url('<?= \app\models\Bots::getBackgroundPlatform( $customer->platform ) ?>') 50%;"></div>
                    <div class="media profil-cover-details w-100">
                        <div class="media-left pl-2 pt-2">
                            <a href="#" class="profile-image">
                                <img src="<?= $customer->getAvatar() ?>" class="rounded-circle img-border height-100"
                                     alt="Card image">
                            </a>
                        </div>
                        <div class="media-body pt-3 px-2">
                            <div class="row">
                                <div class="col">
                                    <h3 class="card-title"><?= $customer->first_name . ' ' . $customer->last_name ?></h3>
                                </div>
                                <!--div class="col text-right">
                                    <div class="btn-group d-none d-md-block float-right ml-2" role="group"
                                         aria-label="Basic example">
                                        <button type="button" class="btn btn-success"><i class="la la-dashcube"></i>
                                            Message
                                        </button>
                                    </div>
                                </div-->
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3 pb-2">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-content">
                                        <?php $form = ActiveForm::begin( [
                                            'id'          => 'form-edit',
                                            'options'     => [ 'enctype' => 'multipart/form-data' ],
                                            'fieldConfig' => [ 'options' => [ 'class' => 'form-group col-lg-4' ] ],
                                        ] ); ?>

                                        <div class="row">
                                            <?= $form->field( $customer,
                                                'id',
                                                [ 'options' => [ 'style' => 'display:none;' ] ] )->hiddenInput() ?>

                                            <?= $form->field( $customer,
                                                'first_name' )->textInput( [ 'disabled' => 'disabled' ] ) ?>
                                            <?= $form->field( $customer,
                                                'last_name' )->textInput( [ 'disabled' => 'disabled' ] ) ?>
                                            <?= $form->field( $customer,
                                                'username' )->textInput( [ 'disabled' => 'disabled' ] ) ?>
                                            <?= $form->field( $customer, 'phone' )->textInput() ?>
                                            <?= $form->field( $customer, 'email' )->textInput() ?>
                                            <?= $form->field( $customer, 'tags',
                                                [ 'options' => [ 'class' => 'form-group col-lg-4 tags_' . $customer->id ] ] )
                                                ->widget( \yii2mod\selectize\Selectize::className(), [
                                                    'options'       => [ 'id' => $customer->id ],
                                                    'pluginOptions' => [
                                                        'plugins'      => [ 'remove_button' ],
                                                        'persist'      => false,
                                                        'createOnBlur' => true,
                                                        'create'       => true,
                                                    ],
                                                ] ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-content">
                                        <?= Html::submitButton( "<i class=\"la la-save\"></i> " . Yii::t( 'app',
                                                'Save' ),
                                            [ 'class' => 'btn btn-success col-12', 'name' => 'save-edit' ] ) ?>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php } ?>