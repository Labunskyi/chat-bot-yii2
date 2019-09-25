<?php
/**
 * @var $user \app\models\User
 */
?>

<table style="background-color: #f6f6f6;width: 100%;" xmlns:text-decoration="http://www.w3.org/1999/xhtml"
       xmlns:color="http://www.w3.org/1999/xhtml">
    <tr>
        <td></td>
        <td style="display: block !important;max-width: 600px !important;vertical-align: top;margin: 0 auto !important;clear: both !important;"
            width="600">
            <div style="max-width: 600px;margin: 0 auto;display: block;padding: 20px;">
                <table style="background: #fff;border: 1px solid #e9e9e9;border-radius: 3px;" width="100%"
                       cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding: 20px;">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0 0 20px;">
                                        <h3><?= Yii::t( 'app', 'Hello, ' ) ?> <?= $user->name ?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 0 20px;">
                                        <?= Yii::t( 'app', 'Go to the password recovery page' ) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 0 20px;text-align: center;">
                                        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl( [ 'user/reset-password', 'token' => $user->password_reset_token ] ) ?>"
                                           style="
                                           text-decoration: none;
                                           color: #FFF;
                                           background-color: #1ab394;
                                           border: solid #1ab394;
                                           border-width: 5px 10px;
                                           line-height: 2;
                                           font-weight: bold;
                                           text-align: center;
                                           cursor: pointer;
                                           display: inline-block;
                                           border-radius: 5px;
                                           text-transform: capitalize;
                                        "><?= Yii::t( 'app', 'Restore password' ) ?></a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td></td>
    </tr>
</table>