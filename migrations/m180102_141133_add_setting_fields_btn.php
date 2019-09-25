<?php

use app\db\Migration;

/**
 * Class m180102_141132_add_setting_fields
 */
class m180102_141133_add_setting_fields_btn extends Migration
{

    public static function newFields()
    {
        return [
            'button_score_1'          => 'Плановый',
            'button_score_2'          => 'Досрочно',
            'button_score_3'          => 'Каникулы',
            'button_score_4'          => 'Индивидуальный',
            'button_score_5'          => 'Не выбрано',
            'button_transfer_form_1'  => 'E-mail',
            'button_transfer_form_2'  => 'Лично в руки в отделе продаж',
            'button_transfer_form_3'  => 'Не выбрано'
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
