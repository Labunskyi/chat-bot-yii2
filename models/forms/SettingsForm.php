<?php

namespace app\models\forms;

use app\models\Base;
use app\models\Setting;
use Yii;
use yii\base\Model;

/**
 * Class ProductForm
 *
 * @package app\models\forms
 */
class SettingsForm extends Model
{

    public $base_id;
    public $menu_line_items;
    public
        $button_send_order,
        $button_edit_order_full,
        $button_edit_order_apartment,
        $button_send_phone_number;
    public
        $button_score_1,
        $button_score_2,
        $button_score_3,
        $button_score_4,
        $button_score_5,
        $button_transfer_form_1,
        $button_transfer_form_2,
        $button_transfer_form_3;
    public
        $message_write_name,
        $message_write_phone_number,
        $message_write_phone,
        $message_success_order,
        $message_write_apartment,
        $message_write_section,
        $message_write_score,
        $message_write_transfer_form,
        $message_write_email,
        $message_write_comment,
        $message_email_format,
        $message_phone_format,
        $message_error_to_checkout,
        $message_email_title,
        $message_order_info;
    public
        $admin_email;


    public function __construct( array $config = [] )
    {
        if ( isset( $config['base_id'] ) ) {
            $items = Setting::getArraySettings( $config['base_id'] );
            foreach ( $items as $key => $item ) {
                $this->{$key} = $item;
            }
        }


        parent::__construct( $config );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'menu_line_items' ], 'integer', 'min' => 0, 'max' => 5 ],
            [ [ 'admin_email' ], 'string' ],
            [ self::getButtonsList(), 'string', 'min' => 1, 'max' => 255 ],
            [ self::getMessagesList(), 'string', 'min' => 1, 'max' => 500 ],
            [ 'base_id', 'required' ]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $arrayProperties = get_object_vars( $this );
        $array = [];
        foreach ( $arrayProperties as $key => $value ) {
            $array[ $key ] = Yii::t( 'app', $key );
        }

        return $array;
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ( !$this->validate() && !Base::isOwner( $this->base_id ) ) {
            return false;
        }

        $arrayProperties = get_object_vars( $this );
        unset( $arrayProperties['base_id'] );

        foreach ( $arrayProperties as $key => $value ) {
            if ( $value ) {
                Setting::setSetting( $this->base_id, $key, $value );
            } else {
                $this->addError( $key, Yii::t( 'app', 'Cannot be empty ' ) );
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function change()
    {
        if ( !$this->validate() && !Base::isOwner( $this->base_id ) ) {
            return false;
        }

        $arrayProperties = get_object_vars( $this );
        unset( $arrayProperties['base_id'] );
        foreach ( $arrayProperties as $key => $value ) {
            if ( $value ) {
                if( is_array($value) ) {
                    $value = json_encode($value);
                }
                Setting::setSetting( $this->base_id, $key, $value );
            } else {
                $this->addError( $key, Yii::t( 'app', 'Cannot be empty ' ) );
            }
        }

        return true;
    }


    /**
     * for generate form in adminpanel
     *
     * @return array
     */
    public static function getButtonsList()
    {
        return [
            'button_send_order',
            'button_edit_order_full',
            'button_edit_order_apartment',
            'button_send_phone_number',

            'button_score_1',
            'button_score_2',
            'button_score_3',
            'button_score_4',
            'button_score_5',

            'button_transfer_form_1',
            'button_transfer_form_2',
            'button_transfer_form_3'
        ];
    }

    /**
     * for generate form in adminpanel
     *
     * @return array
     */
    public static function getMessagesList()
    {
        return [
            'message_write_name',
            'message_write_phone_number',
            'message_write_phone',
            'message_write_apartment',
            'message_write_section',
            'message_write_score',
            'message_write_transfer_form',
            'message_write_email',
            'message_write_comment',
            'message_success_order',
            'message_email_format',
            'message_phone_format',
            'message_error_to_checkout',
            'message_email_title',
            'message_order_info'
        ];
    }

    public static $default_settings = [
        [
            'key'   => 'menu_line_items',
            'value' => [
                1 => '2',
                2 => '2',
            ],
        ],
        [
            'key'   => 'button_apply_cart',
            'value' => [
                1 => "✅ Apply",
                2 => "✅ Подтвердить",
            ],
        ],
        [
            'key'   => 'message_write_name',
            'value' => [
                1 => "Write your name:",
                2 => "Напишите ваше имя:",
            ],
        ],
        [
            'key'   => 'message_write_phone_number',
            'value' => [
                1 => "Click the bottom button to send a phone number. \nDo not enter manually!",
                2 => "Нажмите кнопку снизу, что бы отправить номер телефона.\nНе вводите вручную!",
            ],
        ],
        [ 'key' => 'button_send_phone_number', 'value' => [ 1 => "☎️ Send phone", 2 => "☎️ Отправить телефон" ] ],
        [ 'key' => 'button_edit_order_data', 'value' => [ 1 => "✏️ Edit order data", 2 => "✏️ Редактировать данные", ] ],
        [ 'key' => 'button_send_order', 'value' => [ 1 => "✅ Apply and send", 2 => "✅ Подтвердить и отправить" ] ],
        [
            'key'   => 'message_checkout_total',
            'value' => [
                1 => "Your Order:\n{{items}}Total Quantity: {{totalQuantity}}\nTotal Sum: {{totalSumm}} $\n\nDelivery info:\n{{info}}",
                2 => "Ваш заказ:\n{{items}}\nКоличество товаров: {{totalQuantity}} шт.\nИтоговая сумма: {{totalSumm}} руб\n\nДанные для доставки:\n{{info}}",
            ],
        ],
        [ 'key' => 'message_name', 'value' => [ 1 => "Name: ", 2 => "Имя: ", ], ],
        [ 'key' => 'message_phone', 'value' => [ 1 => "Phone: ", 2 => "Телефон: ", ], ],
        [
            'key' => 'message_success_order',
            'value' => [
                1 => "Thank you, {{name}}.\nNumber your order: {{order_id}}.",
                2 => "Спасибо за заказ, {{name}}.\nНомер Вашего заказа: {{order_id}}.\nОн будет доставлен в течение часа. Если у вас будут вопросы или вы хотите отменить заказ, то позвоните по номеру телефона: +1..........",
            ],
        ],
        [ 'key' => 'message_write_phone',
            'value' => [
                1 => "Write your phone in the format +38 (099) 999-99-99:",
                2 => "Напишите Ваш телефон в формате +38(099)999-99-99:"
            ]
        ],
        [
            'key'   => 'admin_email',
            'value' => [
                1 => "admin@admin.net",
                2 => "admin@admin.net",
            ],
        ],
    ];
}