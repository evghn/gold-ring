<?php

namespace app\models;

use Symfony\Component\VarDumper\VarDumper as VarDumperVarDumper;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "edges".
 *
 * @property int $id
 * @property int $source_id
 * @property int $target_id
 * @property string $time
 *
 * @property Point $source
 * @property Point $target
 */
class Edges extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'edges';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['source_id', 'target_id', 'time'], 'required'],
            [['source_id', 'target_id'], 'integer'],
            [['time'], 'safe'],
            [['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => Point::class, 'targetAttribute' => ['source_id' => 'id']],
            [['target_id'], 'exist', 'skipOnError' => true, 'targetClass' => Point::class, 'targetAttribute' => ['target_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'source_id' => 'Source ID',
            'target_id' => 'Target ID',
            'time' => 'Time',
        ];
    }

    /**
     * Gets query for [[Source]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(Point::class, ['id' => 'source_id']);
    }

    /**
     * Gets query for [[Target]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTarget()
    {
        return $this->hasOne(Point::class, ['id' => 'target_id']);
    }


    private static function getRawRing(): ActiveQueryInterface
    {
        // возврат raw кольца
        return  self::find()
            ->select([
                'edges.*',
                's.title as source_title',
                't.title as target_title',
                new Expression("'00:00' as pause"),
            ])
            ->joinWith(['source s', 'target t'])
        ;
    }

    
    public static function secondsToTime($seconds)
    {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        $dtF->diff($dtT)->format('%a д., %h ч., %i мин.');
        if ($dtF->diff($dtT)->format('%a')) {
            return $dtF->diff($dtT)->format('%a д., %h ч., %i мин.');
        }
        return $dtF->diff($dtT)->format('%h ч., %i мин.');
    }

    public static function isMoning($seconds)
    {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return  (int)$dtF->diff($dtT)->format('%h') < 6;
    }



    public static function traceGo($id_start, $id_end)
    {
        $result = [];

        // получаем все кольцо как массив объектов
        $ring = self::getRawRing()
            ->where(['t.end_point' => 0])
            ->orderBy('source_id')
            ->indexBy('source_id')
            ->asArray()
            ->all()
            ;
        

        $end_point = 0;
        // поиск что конечная точка - тупик
        $end_route = self::getRawRing()
            ->where(['target_id' => $id_end])
            ->one();

        if ($end_route->target->end_point) {
            // конец маршрута - это точно тупик :)
            
            if ($end_route->source_id == $id_start) {
                // маршрут всего 2 города
                $sql = self::getRawRing()
                    ->where([
                        'source_id' => $id_start,
                        'target_id' => $id_end,
                    ])
                    ->one();

                $result[] = [
                    'points' => [$sql],
                    'time_all' => $sql->time
                ];

                return $result;
            }
            
            // есть альтернативный маршрут 
            $end_point = null;              
            // поднятие конечной точки временно на кольцо  
            $id_end = $end_route->source_id;
        }    

            
            
            $ring_keys = array_keys($ring);
            $_ring_keys = array_reverse($ring_keys);

            $route1 = [...$ring_keys, ...$ring_keys];
            $route2 = [...$_ring_keys, ...$_ring_keys];

            // обрезаем до желтого
            array_splice($route1, 0, array_search($id_start, $route1));
            // обрезаем после желтого
            array_splice($route1, array_search($id_end, $route1)+1);
            
            // ----забирем с желотого и далее
            // $route2 = array_splice($route2, 0, array_search($id_start, $route2));
            
            //обрезаем до желтого
            array_splice($route2, 0, array_search($id_start, $route2));
            
            // обрезаем после желтого
            array_splice($route2, array_search($id_end, $route2)+1);

            
            if (isset($end_ring)) {
                // надо добавить тупик если он есть
                $route1 = [...$route1, ...$end_ring ];
                $route2 = [...$route2, ...$end_ring ];
            }

            $rout1_sql = self::getRawRing()
                ->where(['source_id' => $route1])
                ->andFilterWhere(['t.end_point' => $end_point])
                ->orderBy(new Expression("field(source_id, " . implode(",", $route1) . ")"))
                ; 
            
            $rout2_sql = self::getRawRing()
                ->where(['source_id' => $route2])
                ->andFilterWhere(['t.end_point' => $end_point])
                ->orderBy(new Expression("field(source_id, " . implode(",", $route2) . ")"))
                ;

            $time1 = (clone $rout1_sql)  
                ->sum(new Expression('TIME_TO_SEC(time)'))
                ;

            $time2 = (clone $rout2_sql)
                ->sum(new Expression('TIME_TO_SEC(time)'))
                ;
                      
            
                $result[] = [
                    'points' => $rout1_sql->asArray()->all(),
                    'time_all' => $time1,
                    'min_time' => $time1 < $time2,

                ];
                
                $result[] = [
                    'points' => $rout2_sql->asArray()->all(),
                    'time_all' => $time2,
                    'min_time' => $time2 < $time1,
                ];
               
                                    
                return $result;
            }

}
