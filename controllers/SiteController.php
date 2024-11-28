<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Role;
use app\models\User;
use app\models\UserInfo;
use yii\helpers\VarDumper;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
           
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->isManager) {
                return $this->redirect('/manager');
            } else {
                return $this->redirect('/account');
            }            
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }


    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->isManager) {
                return $this->redirect('/manager');
            } else {
                return $this->redirect('/account');
            }            
        }

        $model = new UserInfo();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $user = new User();
                $user->load(Yii::$app->request->post(), 'UserInfo');
                $user->password = Yii::$app->security->generatePasswordHash($model->password);
                $user->auth_key = Yii::$app->security->generateRandomString();
                $user->role_id = Role::findOne(['title' => 'ul'])->id;
                
                if ($user->validate()) {
                    $user->save();
                    $model->user_id = $user->id;                    
                    if ($model->save(false)) {
                        Yii::$app->user->login($user);
                        return $this->redirect('/account');
                    } else {
                        
                        // VarDumper::dump($model->errors, 10, true); die;
                    }
                } else {
                     // контрольная точка для отладки
                    // VarDumper::dump($model->inn, 10, true); 
                    // VarDumper::dump($model->attributes, 10, true); 
                    // VarDumper::dump($user->attributes, 10, true); 
                    // VarDumper::dump($user->errors, 10, true); die;
                }
            } else {
                 // контрольная точка для отладки
                // VarDumper::dump($model->errors, 10, true); die;
            }

        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        if ($user = User::findByUsername(Yii::$app->user->identity->inn))  {
            Yii::$app->user->logout();
            $user->auth_key = null;
            $user->save();
        }

        return $this->goHome();
    }

    
}
