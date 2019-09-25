<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\imagine\Image;

/**
 * Class UploadAvatarForm
 *
 * @package app\models\forms
 * @var $imageFile
 */
class UploadAvatarForm extends Model
{

    public $avatar;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [ 'avatar' ],
                'image',
                'skipOnEmpty' => true,
                'extensions'  => 'png, jpg',
                'minWidth'    => 100,
                'minHeight'   => 100,
                'maxWidth'    => 10000,
                'maxHeight'   => 10000,
                'maxSize'     => 1024 * 1024 * 3,
            ],
        ];
    }

    /**
     * @return bool|null
     * @throws \yii\base\Exception
     */
    public function save()
    {
        if ( !$this->validate() ) {
            return null;
        }
        $imageFile = $this->avatar;

        $directory = Yii::getAlias( 'img/avatar/' );

        if ( !is_dir( $directory ) ) {
            FileHelper::createDirectory( $directory );
        }

        if ( $imageFile ) {
            $fileName = Yii::$app->user->identity->id . '_' . time() . '.' . $imageFile->extension;
            $filePath = $directory . $fileName;

            if ( $imageFile->saveAs( $filePath ) ) {
                Image::thumbnail( $filePath, 200, 200 )->save( $filePath, [ 'jpeg_quality' => 100 ] );

                $user = Yii::$app->user->identity;
                $user->avatar = $fileName;
                $user->save();

                return true;
            }
        }

        return null;
    }

}