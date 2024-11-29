<?php

namespace app\models;

use Yii;
use yii\db\Expression;
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

    private static function getPoints($id)
    {
        return  self::find()
                    ->select([
                        'target_id',
                        'time',
                        's.id as source_id',
                        's.title as source_title',                        
                        't.title as target_title',
                        't.end_point as end_point',

                        new Expression("'00:00' as pause"),
                        ])
                    ->joinWith(['source s', 'target t'])
                    ->where(['source_id' => $id])
                    ->asArray()                    
                    ->all()
                    // ->createCommand()->rawSql
                    ;
    }

    

    private static function sumTime($t1, $t2)
    {
        return date('H:i:s', strtotime($t1) 
            + strtotime($t2)
            - strtotime("00:00:00"));
    }

    public static function graphGo($id_start, $id_end)
    {
        $result = [            
            [
                'points' => [],
                'all_time' => '00:00:00',
            ]
        ];
        $query_points = self::getPoints($id_start);
        //  VarDumper::dump($query_points, 10, true); die;

        // поиск что конечная точка - конца графа
        $find_end_point = array_values(array_filter($query_points, 
            fn($val) => $val['end_point'] == 1 && $val['target_id'] == $id_end
        ));

        if ($find_end_point) {
            // конец маршрута - это ближайшая конечная вершина - альтернативного пути нет
            $result[0]['all_time'] = self::sumTime($result[0]['all_time'], $find_end_point[0]['time']);
        }  else {
            // есть альтернативный путь

            $result = [
                [
                    'points' => [
                        // скорей всего вариант конечной структуры
                        // [                            
                        //     'id' => $query_points['source_id'], // source _id
                        //     'title' => $query_points['source_title'],
                        //     'pause' => $query_points['pause'], 
                        //     'time' => $query_points['time'], 
                        // ],

                        [                            
                            'id' => 9,
                            'title' => 'Иваново',
                            'pause' => '00:00',
                            'time' => '1:00:00' 
                        ],
                        [                            
                            'id' => 1,
                            'title' => 'Москва',
                            'pause' => '00:00',
                            'time' => '1:00:00' 
                        ],
                        [                            
                            'id' => 2,
                            'title' => 'Сергиев Пасад',
                            'pause' => '00:00',
                            'time' => '1:00:00' 
                        ],
                        
                    ],
                    'all_time' => '00:00:00'
                ],
                [
                    'points' => [
                        [                            
                            'id' => 9,
                            'title' => 'Владимир',
                            'pause' => '00:00',
                            'time' => '1:00:00' 
                        ],
                        [                            
                            'id' => 1,
                            'title' => 'Москва',
                            'pause' => '00:00',
                            'time' => '1:00:00' 
                        ],
                        [                            
                            'id' => 2,
                            'title' => 'Сергиев Пасад',
                            'pause' => '00:00',
                            'time' => '1:00:00' 
                        ],
                        
                    ],
                    'all_time' => '00:00:00'
                ],

            ];
        }
        
        
        return $result; 
    }
}
