<?php

use app\db\Migration;

/**
 * Class m180102_141132_add_setting_fields
 */
class m180102_141132_add_setting_fields extends Migration
{

    public static function newFields()
    {
        return [
            'message_email_format'      => 'Введите email в формате <b>email@email.com</b>',
            'message_phone_format'      => 'Введите телефон в формате +380999999999',
            'message_error_to_checkout' => 'Ошибка. Нажмите кнопку "Оформить заявку"',
            'message_email_title'       => 'Новая заявка с бота',
            'message_order_info'        => "Имя: {{name}}\nТелефон: {{phone}}\nКвартира №: {{apartment}}\nСекция №: {{section}}\nСчет: {{score}}\nФорма передачи: {{transfer_form}}\nEmail: {{email}}\nКомментарий: {{comment}}"
        ];
    }

    /**
     * @return bool|void
     */
    public function up()
    {
        $bases = \app\models\Base::find()->all();
        foreach ($bases as $base){
            foreach (self::newFields() as $key => $field) {
                $set = \app\models\Setting::findOne(['key' => $key, 'base_id' => $base->id]);
                if(!$set){
                    $new = new \app\models\Setting();
                    $new->base_id = $base->id;
                    $new->key = $key;
                    $new->value = $field;
                    $new->save();
                }
            }
        }
    }

    /**
     * @return bool|void
     * @throws Exception
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function down()
    {
        $bases = \app\models\Base::find()->all();
        foreach ($bases as $base){
            foreach (self::newFields() as $key => $field) {
                $set = \app\models\Setting::findOne(['key' => $key, 'base_id' => $base->id]);
                if($set){
                    $set->delete();
                }
            }
        }
    }
}
