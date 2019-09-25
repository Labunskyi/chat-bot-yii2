<?php
/**
 * @var $order \app\models\Order
 */
?>
    У Вас новый заказ с бота!<br>
    Имя:  <?= $order->name; ?><br>
    Телефон: <?= $order->phone; ?><br>
    Email:  <?= $order->email; ?><br>
    Квартира №:  <?= $order->apartment; ?><br>
    Секция №:  <?= $order->section; ?><br>
    Счет:  <?= \app\models\Helper::getScore($order->score); ?><br>
    Форма передачи: <?= \app\models\Helper::getTransferForm($order->transfer_form); ?><br>
    Комментарий: <?= $order->addition_info; ?>
