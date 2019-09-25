<?php

namespace app\widgets;

use Yii;
use yii\helpers\Url;


class AuthHead
{
    static public function authHead()
    {
        $html = '<div class="card-header border-0 row p-0 m-0">
                    <div class="col-6">
                        <a href="' . Url::home() . '"> < ' . Yii::t( 'app', 'Home' ) . '</a>
                    </div>
                    <div class="col-6">
                        <ul class="nav navbar-nav float-right">
                            <li class="dropdown dropdown-language nav-item">
                                <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"><i
                                    class="flag-icon flag-icon-' . ( ( Yii::$app->language == 'en' ) ? 'gb' : Yii::$app->language ) . '"></i><span
                                    class="selected-language"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-flag">
                                    <a href="' . Url::to( [
                str_replace( [ '/ru', '/en' ], '', Yii::$app->request->url ), 'language' => 'en',
            ] ) . '" class="dropdown-item"><i class="flag-icon flag-icon-gb"></i> English</a>
                                    <a href="' . Url::to( [
                str_replace( [ '/ru', '/en' ], '', Yii::$app->request->url ), 'language' => 'ru',
            ] ) . '" class="dropdown-item"><i class="flag-icon flag-icon-ru"></i> Русский</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                 </div>                    
                    
                    
                    
                    ';

        return $html;
    }

    static public function authButtons()
    {
        $html = '';
        if ( $tg_auth = getenv( 'FB_AVAILABLE' ) ) {
            $html .= '<a href="' . Url::to( [ 'user/auth', 'authclient' => 'facebook', 'enforce_https' => 1 ] ) . '" class="btn btn-social round btn-min-width mr-1 mb-1 btn-facebook"><i class="la la-facebook"></i> ' . Yii::t( 'app',
                    'Login with Facebook' ) . '</a>';
        }

        if ( $fb_auth = getenv( 'TG_AVAILABLE' ) ) {
            $html .= '<script async src = "https://telegram.org/js/telegram-widget.js?4"
                            data-telegram-login = "' . getenv( 'LOGIN_BOT_TELEGRAM' ) . '"
                            data-size = "large"
                            data-userpic = "false"
                            data-auth-url = "' . Url::toRoute( [ 'user/telegram-auth' ], true ) . '"
                            data-request-access = "write" >
                    </script >';
        }

        if ( !$tg_auth && !$fb_auth ) {
            $html .= '<p class="red">' . Yii::t( 'app', 'Currently unavailable' ) . '</p>';
        }

        return $html;
    }
}