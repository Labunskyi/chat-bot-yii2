<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;



class SiteController extends Controller
{
    public function actionIndex()
    {
        $this->redirect(['user/index']);
        return $this->render( 'index' );
    }

    public function actionError(){
        $this->redirect(['user/index']);
    }
}
