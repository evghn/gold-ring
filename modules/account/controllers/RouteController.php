<?php

namespace app\modules\account\controllers;

use app\models\Edges;
use app\models\Point;
use app\models\Route;
use app\models\RouteItem;
use app\models\UserInfo;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
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
                    'class' => VerbFilter::class,
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
        $dataProvider = new ArrayDataProvider();

        match ($model->step) {
            1 => $model->scenario = Route::SCENARIO_STEP1,
            2 => $model->scenario = Route::SCENARIO_STEP2,
            3 => $model->scenario = Route::SCENARIO_STEP3
        };

        

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // VarDumper::dump($model->route_items, 10, true); die;
                
                if ($model->validate()) {
                    if ($model->step == 3) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    } 

                    $model->step++;

                    switch ($model->step) {                        
                        case 2:                            
                            $dataProvider = new ArrayDataProvider();
                            $routes = Edges::traceGo(
                                $model->point_start_id, 
                                $model->point_end_id
                            );
                            $model->route_items = json_encode($routes);
                            $dataProvider->allModels = $routes;
                            $model->scenario = Route::SCENARIO_STEP2;
                            break;
                        case 3:
                            $routes = json_decode($model->route_items, true);
                            $routes = isset($_POST['route-1'])
                                ? $routes[1]
                                : $routes[0];
                            $model->time_all = $routes['time_all'];
                            $model->route_items = json_encode($routes);
                            

                            array_pop($routes['points']);
                            array_shift($routes['points']);

                            $model->stop_points = [];
                            foreach ($routes['points'] as $item) {
                                $rI = new RouteItem();
                                $rI->point_id = $item['source_id'];
                                $model->stop_points[] = $rI;
                            }
                            $dataProvider->allModels = $routes['points'];

                            // VarDumper::dump($routes, 10, true); 
                            // VarDumper::dump($model->stop_points, 10, true); 
                            // die;
                            $model->scenario = Route::SCENARIO_STEP3;
                    };
                }
            }
        }
        

        
        return $this->render('create', [
            'model' => $model,            
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCalcPause()
    {
        $model = new Route();
        if ($this->request->isPost && $model->load($this->request->post()) ) {
            $routes = json_decode($model->route_items, true);
            array_pop($routes['points']);
            array_shift($routes['points']);
            $model->stop_points = [];
            $model->time_all = $routes['time_all'];
            foreach ($routes['points'] as $item) {                
                $model->stop_points[] = new RouteItem();
            }
            Model::loadMultiple($model->stop_points, Yii::$app->request->post());
            if (Model::validateMultiple($model->stop_points)) {                
                foreach ($model->stop_points as $item) {
                    if ($item->time_pause) {
                        $t = (int)substr($item->time_pause, 0, 2) * 60 * 60 +
                            (int)substr($item->time_pause, 3, 2)*60;
                        $model->time_all += $t;
                    }
                }               
            } 
            
            return $this->renderAjax('pause', compact('model'));
        }
    }


    // public function actionEndPoints($id)
    // {
    //     $items = !empty($id) 
    //         ? Point::getEndPoints($id)
    //         : [];
    //     $result = '<option>Выберете конечный пункт</option>';
    //     // VarDumper::dump($items, 10, true); die;

    //     $result .= implode(
    //         array_map(fn($val) => "<option value='$val->id'>" . $val->title . "</option>",
    //         $items
    //     ));
        
    //     return $result;
    // }


    // public function actionTest()
    // {
    //     VarDumper::dump(Edges::traceGo(1, 8), 10, true);
    // }

    
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
