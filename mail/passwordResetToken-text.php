<?php
/**
 * @var $user \app\models\User
 */
?>
<?= Yii::t( 'app', 'Hello, ' ) ?> <?= $user->name ?>.
<?= Yii::t( 'app', 'Go to the password recovery page' ) ?>
<?= Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]) ?>

