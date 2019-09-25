<?php

namespace app\controllers;

use app\models\Bots;
use app\models\forms\DeleteAccountForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\forms\EditForm;
use app\models\forms\LoginForm;
use app\models\forms\UploadAvatarForm;
use app\models\forms\ResetPasswordForm;
use app\models\forms\PasswordResetRequestForm;
use yii\web\UploadedFile;
use app\models\User;
use app\models\forms\SignupForm;
use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use app\models\Auth;
use app\widgets\SelfHelper;
use app\models\Base;

class UserController extends Controller
{
    public $layout = '@app/views/layouts/inner.php';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [ 'edit', 'upload-avatar', 'auth', 'telegram-auth',  'add-new','delete-account', 'logout', 'index' ],
                        'allow'   => true,
                        'roles'   => [ '@' ],
                        'denyCallback' => function($rule, $action) {
                            return $action->controller->redirect(['user/login']);
                        },
                    ],
                    [
                        'allow' => false,
                        'actions' => ['login', 'signup', 'request-password-reset', 'reset-password', 'telegram-auth'],
                        'roles' => ['@'],
                        'denyCallback' => function($rule, $action) {
                            return $action->controller->redirect(['user/index']);
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login', 'auth', 'signup', 'request-password-reset', 'reset-password', 'telegram-auth'],
                        'roles' => ['?'],
                    ],

                ],

            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => [ 'post' ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'add-new' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthNew'],
            ],
        ];
    }

    /**
     * get user profile
     *
     * @return string
     */
    public function actionIndex()
    {
        if(!($base = Base::findOne(['user_id' => Yii::$app->user->getId()]))){
            return $this->redirect( ['bots/add'] );
        }else{
            return $this->redirect( ['bots/bot-view','base_id' => $base->id] );
        }


    }

    /**
     * Edit user profile by user
     *
     * @return string
     */
    public function actionEdit()
    {
        $upModel = new UploadAvatarForm();
        $model = new EditForm();

        if ( Yii::$app->request->isPost && $model->load( Yii::$app->request->post() ) ) {
            if ( $model->save() ) {
                Yii::$app->session->setFlash( 'success', Yii::t('app', 'Changes saved') );
            } else {
                Yii::$app->session->setFlash( 'error', Yii::t('app', 'Error while saving') );
            }
        }

        return $this->render( 'editAccount', compact( 'model', 'upModel' ) );
    }


    /**
     * @return bool|Response
     * @throws \yii\base\Exception
     */
    public function actionUploadAvatar()
    {
        $model = new UploadAvatarForm();
        if ( Yii::$app->request->isPost ) {
            $model->avatar = UploadedFile::getInstance( $model, 'avatar' );
            if ( $model->save() ) {
                Yii::$app->session->setFlash( 'success', Yii::t('app', 'Changes saved') );
                return $this->redirect( [ 'user/edit' ] );
            }

            foreach ( $model->errors['avatar'] as $item ) {
                Yii::$app->session->setFlash( 'error', $item );
            }

            return $this->redirect( [ 'user/edit' ] );

        }

        return false;
    }

    /**
     * @return bool|Response
     * @throws \yii\base\Exception
     */
    public function actionDeleteAccount()
    {
        $model = new DeleteAccountForm();
        if ( Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect( [ 'user/login' ] );
        }
        return $this->render( 'deleteAccount', compact('model') );

    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = '@app/views/layouts/auth.php';

        if ( !Yii::$app->user->isGuest ) {
            return Yii::$app->response->redirect( [ 'user/edit' ] );
        }

        $model = new LoginForm();
        if ( $model->load( Yii::$app->request->post() ) && $model->login() ) {
            return Yii::$app->response->redirect( [ 'user/edit' ] );
        }

        return $this->render( 'login', [
            'model' => $model,
        ] );
    }

    /**
     * @return $this|string
     * @throws \yii\base\Exception
     */
    public function actionSignup()
    {
        $this->layout = '@app/views/layouts/auth.php';
        $model = new SignupForm();

        if ( $model->load( Yii::$app->request->post() ) && $user = $model->signup() ) {
            Yii::$app->user->login( $user,  3600 * 24 * 30 );
            return Yii::$app->response->redirect( [ 'user/index' ] );

        }

        return $this->render( 'signup', compact( 'model' ) );
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return Yii::$app->response->redirect( [ 'user/login' ] );
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        $this->layout = '@app/views/layouts/auth.php';

        if ( $model->load( Yii::$app->request->post() ) && $model->validate() ) {
            if ( $model->sendEmail() ) {
                Yii::$app->session->setFlash( 'success', 'Check your email for further instructions.' );

                return Yii::$app->response->redirect( [ 'user/edit' ] );
            } else {
                Yii::$app->session->setFlash( 'error', 'Sorry, we are unable to reset password for email provided.' );
            }
        }

        return $this->render( 'requestPasswordResetToken', [
            'model' => $model,
        ] );
    }

    /**
     * @param $token
     *
     * @return string|Response
     */
    public function actionResetPassword( $token )
    {
        $this->layout = '@app/views/layouts/auth.php';

        try {
            $model = new ResetPasswordForm( $token );
        } catch ( InvalidParamException $e ) {
            Yii::$app->session->setFlash( 'fail', 'Wrong reset link' );
            return $this->redirect(['user/login']);
        }

        if ( $model->load( Yii::$app->request->post() ) && $model->validate() && $model->resetPassword() ) {
            Yii::$app->session->setFlash( 'success', 'New password was saved.' );

            return $this->redirect( [ 'user/login' ] );
        }

        return $this->render( 'resetPassword', [
            'model' => $model,
        ] );
    }

    /**
     * @param $client
     *
     * @return null|Response
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();

        /* @var $auth Auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
			
            if ($auth) { // авторизация
                $user = User::findOne($auth->user_id);
                Yii::$app->user->login($user);
				return $this->redirect(['user/index']);
            } else { // регистрация
                if (isset($attributes['email']) && User::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует, но с ним не связан. Для начала войдите на сайт использую электронную почту, для того, что бы связать её.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User([
                        'name' => explode(' ', $attributes['name'])[0],
                        'lastname' => explode(' ', $attributes['name'])[1],
                        'email' => $attributes['email'],
                        'password' => $password,
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();
                    $transaction = $user->getDb()->beginTransaction();
                    if ($user->save()) {
                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user);
                            return $this->redirect(['bots/add']);
                        } else {
                            print_r($auth->getErrors());
                        }
                    } else {
                        print_r($user->getErrors());
                    }
                }
            }
        } else { // Пользователь уже зарегистрирован
            if (!$auth) { // добавляем внешний сервис аутентификации
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();

                return $this->redirect([Yii::$app->session->get('rt')]);
            }
        }
        return null;
    }

    /**
     * @return Response
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionTelegramAuth()
    {

        $telegram_data = Yii::$app->request->get();

        if(!SelfHelper::checkTelegramAuthorization($telegram_data, getenv('BOT_TELEGRAM_TOKEN'))){
            return $this->redirect('/');
        }

        $auth = Auth::find()->where([
            'source' => Bots::PLATFORM_TELEGRAM,
            'source_id' => $telegram_data['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if($auth){ // авторизация
                $user = User::findOne($auth->user_id);
                Yii::$app->user->login($user);
                return $this->redirect(['user/index']);
            }else{ // регистрация
                $password = Yii::$app->security->generateRandomString(6);

                $user = new User([
                    'name'     => $telegram_data['first_name'],
                    'lastname' => isset($telegram_data['last_name']) ? $telegram_data['last_name'] : '',
                    'password' => $password,
                    'email'    => $telegram_data['id'] . '@joinchat.us'
                ]);
                $user->generateAuthKey();
                $user->generatePasswordResetToken();
                $transaction = $user->getDb()->beginTransaction();
                if ($user->save()) {

                    if(isset($telegram_data['photo_url']) && $telegram_data['photo_url']){ // загрузка картинки пользователя

                        $tmp = explode( '.', $telegram_data['photo_url'] );
                        $filename = $user->id . '_' . time() . '.' . end( $tmp );
                        $path = './img/avatar/' .  $filename;

                        file_put_contents($path, file_get_contents($telegram_data['photo_url']));

                        $user->avatar = $filename;
                        $user->update();
                    }

                    $auth = new Auth([
                        'user_id' => $user->id,
                        'source' => Bots::PLATFORM_TELEGRAM,
                        'source_id' => $telegram_data['id'],
                    ]);
                    if ($auth->save()) {
                        $transaction->commit();
                        Yii::$app->user->login($user);
                        return $this->redirect(['bots/add']);
                    } else {
                        print_r($auth->getErrors());
                    }
                } else {
                    print_r($user->getErrors());
                }
            }
        }else{ // Пользователь уже зарегистрирован
            if (!$auth) { // добавляем внешний сервис аутентификации
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => Bots::PLATFORM_TELEGRAM,
                    'source_id' => $telegram_data['id'],
                ]);
                $auth->save();
                return $this->redirect([Yii::$app->session->get('rt')]);
            }
        }
    }


}
