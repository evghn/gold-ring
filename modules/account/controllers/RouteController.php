<?php

namespace app\modules\account\controllers;

use app\models\Edge;
use app\models\Point;
use app\models\Route;
use app\models\RouteItem;
use app\models\UserInfo;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Expression;
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
        $dataProviderOn = new ActiveDataProvider([
            'query' => Route::find()
                ->with(['pointStart', 'pointEnd'])
                ->where(['user_id' => Yii::$app->user->id])
                ->andWhere(['>=', 'date_start', new Expression('CURDATE()')])
                ,
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            
        ]);
        
        $dataProviderOff = new ActiveDataProvider([
            'query' => Route::find()
                ->with(['pointStart', 'pointEnd'])
                ->where(['user_id' => Yii::$app->user->id])
                ->andWhere(['<', 'date_start', new Expression('CURDATE()')])
                // ->andWhere(['<', 'time_start', new Expression('CURTIME()')])
                ,
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            
        ]);

        // VarDumper::dump($dataProviderOn->query->createCommand()->rawSql, 10, true); die;
        
        return $this->render('index', [
            'dataProviderOn' => $dataProviderOn,
            'dataProviderOff' => $dataProviderOff,
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
        $model->user_id = Yii::$app->user->id;


        $dataProvider = new ArrayDataProvider();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->validate()) {
                    if ($model->step == 3) {
                        // сохранение маршрута
                        if ($model->save()) {
                            $routes = json_decode($model->route_items, true);            
                            $model->stop_points = [];
                            $model->time_all = $routes['time_all'];
                            foreach ($routes['points'] as $item) {
                                $model->stop_points[] = new RouteItem();
                            }
                            
                            Model::loadMultiple($model->stop_points, Yii::$app->request->post());
                            if (Model::validateMultiple($model->stop_points)) {                                   
                                $time_route = 0;
                                foreach ($routes['points'] as $key => $item) {
                                    $item_point = $model->stop_points[$key];
                                    $item_point->route_id = $model->id;
                                    $item_point->point_id = $item['source_id'];

                                    $item_point->time_route_sec = $item['time_sec'];
                                    $item_point->time_route = $item['time'];

                                    $time_route += $item_point->time_route_sec;

                                    $item_point->time_visit = $time_route;

                                    if ($item_point->time_pause) {
                                        $item_point->time_pause_sec =  Edge::timeToSec($item_point->time_pause);
                                        $time_route += $item_point->time_pause_sec;                                
                                    }
                                    $item_point->time_out = $time_route;

                                    if (! $item_point->save()) {
                                        VarDumper::dump($item_point->errors,10, true);
                                        die;
                                    }
                                }

                                $model->time_end = $time_route;
                                $model->save();
                            }
                        } else {
                            VarDumper::dump($model->errors, 10, true);                            
                        }
                        

                        return $this->redirect(['index']);
                    } 

                    $model->step++;

                    switch ($model->step) {                        
                        case 2:                            
                            $dataProvider = new ArrayDataProvider();
                            $routes = Edge::traceGo(
                                $model->point_start_id, 
                                $model->point_end_id
                            );
                            $model->route_items = json_encode($routes);
                            $dataProvider->allModels = $routes;
                           
                            break;
                        case 3:
                            $routes = json_decode($model->route_items, true);
                            $routes = isset($_POST['route-1'])
                                ? $routes[1]
                                : $routes[0];
                            $model->time_all = $routes['time_all'];
                            $model->route_items = json_encode($routes);
                            $model->stop_points = [];
                            foreach ($routes['points'] as $item) {
                                $rI = new RouteItem();
                                $rI->point_id = $item['source_id'];
                                $model->stop_points[] = $rI;
                            }
                            $dataProvider->allModels = $routes['points'];
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
            
            return $this->renderAjax('step3', compact('model'));
        }
    }


    


    public function actionTest()
    {
        VarDumper::dump(Edge::traceGo(1, 8), 10, true);
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
