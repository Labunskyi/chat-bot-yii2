<?php

namespace app\widgets;

use Yii;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Alert extends \yii\bootstrap\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - key: the name of the session flash variable
     * - value: the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning',
    ];
    /**
     * @var array the options for rendering the close button tag.
     * Array will be passed to [[\yii\bootstrap\Alert::closeButton]].
     */
    public $closeButton = [];


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        echo \lavrentiev\widgets\toastr\NotificationFlash::widget( [
            'options' => [
                "closeButton"       => true,
                "debug"             => true,
                "newestOnTop"       => false,
                "progressBar"       => true,
                "positionClass"     => \lavrentiev\widgets\toastr\NotificationFlash::POSITION_BOTTOM_RIGHT,
                "preventDuplicates" => false,
                "onclick"           => null,
                "showDuration"      => "100",
                "hideDuration"      => "500",
                "timeOut"           => "10000",
                "extendedTimeOut"   => "5000",
                "showEasing"        => "swing",
                "hideEasing"        => "linear",
                "showMethod"        => "fadeIn",
                "hideMethod"        => "fadeOut",
            ],
        ] );
    }
}
