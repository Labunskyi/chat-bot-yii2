<?php
/**
 * @var $order \app\models\Order
 */
?>
У Вас новый заказ с бота!\n
Имя:  <?= $order->name; ?>\n
Телефон: <?= $order->phone; ?>\n
Email:  <?= $order->email; ?>\n
Квартира №:  <?= $order->apartment; ?>\n
Секция №:  <?= $order->section; ?>\n
Счет:  <?= \app\models\Helper::getScore($order->score); ?>\n
Форма передачи: <?= \app\models\Helper::getTransferForm($order->transfer_form); ?>\n
Комментарий: <?= $order->addition_info; ?>

