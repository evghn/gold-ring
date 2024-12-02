<?php

namespace app\modules\manager\controllers;

use app\models\Edge;
use app\models\Route;
use app\models\RouteItem;
use app\modules\manager\models\RouteSearchNew;
use app\modules\manager\models\RouteSearchOld;
use Yii;
use yii\base\Model;
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
        $searchModelNew = new RouteSearchNew();
        $searchModelOld = new RouteSearchOld();

        $dataProviderOn = $searchModelNew->search($this->request->queryParams);
        $dataProviderOff = $searchModelOld->search($this->request->queryParams);
                
        return $this->render('index', [
            'dataProviderOn' => $dataProviderOn,
            'dataProviderOff' => $dataProviderOff,            
        ]);
        
    }
    /**
     * Displays a single Route model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTraceView($id)
    {
        return $this->renderAjax('trace-route-view', [
            'model' => $this->findModel($id),
            'modelItem' => RouteItem::findAll(['route_id' => $id]),
        ]);
    }




    public function actionCalcPause($id)
    {
        $model = $this->findModel($id);
        if ($this->request->isPost) {
            $stopItems = RouteItem::find()
            ->where(['route_id' => $id])
            ->indexBy('id')
            ->all();
            $no_save = false;
            foreach($stopItems as &$item) {
                $item->scenario = RouteItem::SCENARIO_UPDATE;
            }

            if (Model::loadMultiple($stopItems, Yii::$app->request->post())) {
                
                if (Model::validateMultiple($stopItems)) {                
                    // VarDumper::dump($stopItems, 10, true); die;
                    
                    $moning = date("H", $model->time_end) < 6; // было
    
                    $time_all = $model->after_start + $model->after_start; 
    
                    foreach ($stopItems as $item) {
                        $time_all += $item->time_route_sec;
                        if ($item->time_pause) {
                            $time_all += Edge::timeToSec($item->time_pause);                        
                        }
                    }
    
                    if (!$moning && Edge::isMoning($model->time_start, $time_all)) {
                        $no_save = true;
                    }
    
                } 
            }
            
            return $this->renderAjax('form-update', compact('model', 'no_save', 'stopItems'));
        }
    }

    
    /**
     * Updates an existing Route model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        date_default_timezone_set('UTC');
        $model = $this->findModel($id);
        
        // $model->route_items = json_encode($routes);
        $stopItems = RouteItem::find()
        ->where(['route_id' => $id])
        ->indexBy('id')
        ->all();

        
        if ($this->request->isPost && $model->load($this->request->post())) {
            
            die;
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        return $this->render('update', [
            'model' => $model,
            'stopItems' => $stopItems,
        ]);
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
