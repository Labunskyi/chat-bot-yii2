<?php

namespace app\hook\models\telegram;


use app\models\Menu;

class Response
{
    public static function getListCheckers()
    {
        return [
            [ 'app\hook\models\telegram\BasePhrases', 'checkClickMenu'],
            [ 'app\hook\models\telegram\BasePhrases', 'checkCommands'],
            [ 'app\hook\models\telegram\BasePhrases', 'checkWaitingRecord'],
        ];
    }

    public static function mainMenuGenerate($base_id){
        $menu = Menu::find()->where(['base_id' => $base_id])->orderBy(['sort' => SORT_ASC])->all();

        $keyboard = [];
        $menu_line_items = \Yii::$app->params['bot_config']['menu_line_items'];
        $line = 0;
        $col = 0;
        foreach ($menu as $item){
            $col++;
            $keyboard['keyboard'][$line][] =  [ "text" => $item->name ];

            if($col == $menu_line_items){
                $line++;
                $col = 0;
            }

        }
        $keyboard['resize_keyboard'] = true;
        $keyboard['one_time_keyboard'] = true;

        return $keyboard;
    }

    /**
     * @return mixed
     */
    public static function removeMenu() {
        $keyboard['remove_keyboard'] = true;
        return $keyboard;
    }
}