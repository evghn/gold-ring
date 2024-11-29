<?php

namespace app\modules\account\controllers;

use app\models\Edges;
use app\models\Point;
use app\models\Route;
use app\models\UserInfo;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;

/**
 * RouteController implements the CRUD actions for Route model.
 */
class RouteController extends Controller
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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Route models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Route::find(),
            
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            
        ]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'useriInfo' => UserInfo::getUserInfo(Yii::$app->user->id)
        ]);
    }

    /**
     * Displays a single Route model.
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
     * Creates a new Route model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Route();

        match ($model->step) {
            1 => $model->scenario = Route::SCENARIO_STEP1,
            2 => $model->scenario = Route::SCENARIO_STEP2,
            3 => $model->scenario = Route::SCENARIO_STEP3
        };

        $startPoints = Point::getStartPoints();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                if ($model->validate()) {
                    if ($model->step == 3) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    } 

                    $model->step++;

                    switch ($model->step) {                        
                        case 2:
                            
                            $model->scenario = Route::SCENARIO_STEP2;
                            break;
                        case 3:
                            $model->scenario = Route::SCENARIO_STEP3;
                    };
                }
            }
        }
        // } else {
        //     $model->loadDefaultValues();
        // }

        
        return $this->render('create', [
            'model' => $model,
            'startPoints' => $startPoints,
        ]);
    }


    public function actionEndPoints($id)
    {
        $items = !empty($id) 
            ? Point::getEndPoints($id)
            : [];
        $result = '<option>Выберете конечный пункт</option>';
        // VarDumper::dump($items, 10, true); die;

        $result .= implode(
            array_map(fn($val) => "<option value='$val->id'>" . $val->title . "</option>",
            $items
        ));
        
        return $result;
    }


    public function actionTest()
    {
        Edges::graphGo(9, 10);
    }

    
    /**
     * Deletes an existing Route model.
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
     * Finds the Route model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Route the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Route::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
