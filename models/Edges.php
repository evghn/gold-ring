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
                's.title as sourse_title',
                't.title as target_title',
                new Expression("'00:00' as pause"),
            ])
            ->joinWith(['source s', 'target t'])
        ;
    }


    public static function traceGo($id_start, $id_end)
    {
        $result = [
            // [
            //     'points' => [],
            //     'all_time' => '00:00:00',
            // ]
        ];

        $ring = self::getRawRing()
            ->where(['t.end_point' => 0])
            ->orderBy('source_id');
        // $sqlDown = self::getRawRing()
        //     // ->where(['t.end_point' => 0])
        //     ->orderBy('target_id DESC');


        // поиск что конечная точка - конца графа
        $end_route = self::getRawRing()
            ->where(['target_id' => $id_end])
            ->one();

        if ($end_route->target->end_point) {
            // конец маршрута - это тупик

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
                    'all_time' => $sql->time
                ];

                return $result;
            }
            
                // есть альтернативный маршрут 
                $end_ring = $id_end;                
                $id_end = $end_route->source_id;
        }    

            // получаем все кольцо как массив объектов
            $ring = $ring
                ->indexBy('source_id')
                ->asArray()
                ->all()
                ;            

            $ring_keys = array_keys($ring);
            $_ring_keys = array_reverse($ring_keys);

            $route1 = [...$ring_keys, ...$ring_keys];
            $route2 = [...$_ring_keys, ...$_ring_keys];

            array_splice($route1, 0, array_search($id_start, $route1));
            array_splice($route1, array_search($id_end, $route1)+1);
            
            $route2 = array_splice($route2, array_search($id_start, $route2));
            array_splice($route2, array_search($id_end, $route2)+1);

            
            // VarDumperVarDumper::dump($ring_key1);
            // VarDumperVarDumper::dump($ring_key2);
            VarDumperVarDumper::dump($id_start);
            VarDumperVarDumper::dump($id_end);            

                      
            
            VarDumperVarDumper::dump($route1);
            VarDumperVarDumper::dump($route2);
            die;

                $sqlUp
                    ->where(['>=', 'source_id', $id_start])
                    ->andWhere(['<=', 'target_id', $end_ring])
                    ;
                $sqlUp->union();
                
                $time = (clone $sqlUp)
                    ->addSelect('SEC_TO_TIME(sum(TIME_TO_SEC(time))) as `all_time`')
                    ->asArray()
                    ->one()
                    ;
                $sqlDown
                    ->where(['>=', 'source_id', $id_start])
                    ->andWhere(['<=', 'target_id', $end_ring])                    
                    ;
                $time = (clone $sqlUp)
                    ->addSelect('SEC_TO_TIME(sum(TIME_TO_SEC(time))) as `all_time`')
                    ->asArray()
                    ->one()
                    ;


                $result[] = [
                    'points' => [
                        $sqlUp->asArray()->all(),
                    'all_time' => $time['all_time']
                    ]
                ];

                return $result;
            }



            // $result[0]['all_time'] = $result[0]['all_time'], $find_end_point[0]['time']);

       
            // есть альтернативный путь

            // когда 2 соседних города



        //     $result = [
        //         [
        //             'points' => [
        //                 // скорей всего вариант конечной структуры
        //                 // [                            
        //                 //     'id' => $query_points['source_id'], // source _id
        //                 //     'title' => $query_points['source_title'],
        //                 //     'pause' => $query_points['pause'], 
        //                 //     'time' => $query_points['time'], 
        //                 // ],

        //                 [
        //                     'id' => 9,
        //                     'title' => 'Иваново',
        //                     'pause' => '00:00',
        //                     'time' => '1:00:00'
        //                 ],
        //                 [
        //                     'id' => 1,
        //                     'title' => 'Москва',
        //                     'pause' => '00:00',
        //                     'time' => '1:00:00'
        //                 ],
        //                 [
        //                     'id' => 2,
        //                     'title' => 'Сергиев Пасад',
        //                     'pause' => '00:00',
        //                     'time' => '1:00:00'
        //                 ],

        //             ],
        //             'all_time' => '00:00:00'
        //         ],
        //         [
        //             'points' => [
        //                 [
        //                     'id' => 9,
        //                     'title' => 'Владимир',
        //                     'pause' => '00:00',
        //                     'time' => '1:00:00'
        //                 ],
        //                 [
        //                     'id' => 1,
        //                     'title' => 'Москва',
        //                     'pause' => '00:00',
        //                     'time' => '1:00:00'
        //                 ],
        //                 [
        //                     'id' => 2,
        //                     'title' => 'Сергиев Пасад',
        //                     'pause' => '00:00',
        //                     'time' => '1:00:00'
        //                 ],

        //             ],
        //             'all_time' => '00:00:00'
        //         ],

        //     ];
        // }


    //     return $result;
    // }
}
