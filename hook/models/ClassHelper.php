<?php /** @noinspection ALL */

namespace app\hook\models;


use app\models\Bots;

class ClassHelper
{
    /**
     * @param $json_func
     *
     * @return mixed
     */
    protected static function checkClassMethodExist( $json_func, $platform )
    {
        $object = json_decode( $json_func );

        if ( $object &&
            class_exists( self::replaceClassName($object->class, $platform) ) &&
            method_exists( self::replaceClassName($object->class, $platform), $object->method ) ) {
            return true;
        }

        return false;
    }

    /**
     * @param      $json_func
     * @param      $whData
     * @param Bots $bot
     * @param null $menu
     *
     * @return null
     */
    public static function callClassMethod( $json_func, $whData, $bot, $menu = null  ){
        $object = json_decode( $json_func );
        if($object) {
            $class = self::replaceClassName($object->class, $bot->platform);

            if(self::checkClassMethodExist($json_func, $bot->platform)){
                return $class::{$object->method}( $whData, $bot, $menu );
            }
        }
        return null;
    }

    /**
     * @param $class
     * @param $platform
     * @return mixed
     */
    public static function replaceClassName($class, $platform)
    {
        return str_replace("{{platform}}", $platform, $class);
    }

}