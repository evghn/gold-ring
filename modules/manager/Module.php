<?php

namespace app\modules\manager;

use Yii;
use yii\filters\AccessControl;

/**
 * manager module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\manager\controllers';


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,                
                'rules' => [
                    [                        
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => fn() => Yii::$app->user->identity->isManager
                    ],
                ],

                'denyCallback' => fn() => Yii::$app->response->redirect('/'),
            ],
           
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
