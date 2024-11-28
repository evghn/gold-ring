<?php

namespace app\modules\account\controllers;

use app\models\User;
use app\models\UserInfo;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for UserInfo model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

   
    /**
     * Displays a single UserInfo model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Updates an existing UserInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!$model->inn) {
            $model->inn = $model->user->inn;
        }


        if ($this->request->isPost && $model->load($this->request->post())) {

            if ($model->validate()) {
                $user = User::findOne(['id' => $model->user_id]);
                $user->load(Yii::$app->request->post(), 'UserInfo');
                $user->password = Yii::$app->security->generatePasswordHash($model->password);
                if ($user->validate()) {
                    $user->save();                    
                    if ($model->save(false)) {                        
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
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing UserInfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return UserInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserInfo::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
