<?php

namespace app\models;
ini_set('memory_limit', '1000M');
ini_set('max_execution_time', '300');

use Yii;

/**
 * This is the model class for table "cars".
 *
 * @property string $id
 * @property int $spot_id
 * @property string $number
 * @property int $type
 * @property string $model
 * @property string $description
 * @property int $year
 * @property double $x_pos
 * @property double $y_pos
 */
class Car extends \yii\db\ActiveRecord
{

    const MODELS = array(
        'Легковые ТС' => 0,
        'Грузовые ТС' => 1,
        'Автобусы' => 2,
        'Спецтехника' => 3
    );

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cars';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['type', 'year'], 'integer'],
            [['x_pos', 'y_pos'], 'number'],
            [['id', 'spot_id'], 'string', 'max' => 36],
            [['number'], 'string', 'max' => 15],
            [['model'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 512],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'spot_id' => 'Spot ID',
            'number' => 'Number',
            'type' => 'Type',
            'model' => 'Model',
            'description' => 'Description',
            'year' => 'Year',
            'x_pos' => 'X Pos',
            'y_pos' => 'Y Pos',
        ];
    }

    /**
     * @param $id
     * @return self|null|static
     */
    public static function getOrCreate($id)
    {
        $model = self::findOne($id);
        return $model === null ? new self : $model;
    }

    /**
     * @return array|self[]
     */
    public static function getActives()
    {
        return self::find()->where(['!=', 'x_pos', 0])->all();
    }

    private static function getSoapCars()
    {
        $client = new \SoapClient("http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl");
        $cars = json_decode($client->getCars()->return);
        $carsStatuses = json_decode($client->getGarsStatus()->return);
        $carsPositions = json_decode($client->getCarsPosition()->return);
        foreach ($cars as $car) {
            $resultArray[$car->ID] = [
                'number' => null,
                'spot_id' => null,
                'status' => null,
                'inline' => null,
                'type' => null,
                'model' => null,
                'description' => null,
                'year' => null,
                'x_pos' => null,
                'y_pos' => null
            ];
            foreach($carsStatuses as $carsStatus) {
                if($carsStatus->CarsID === $car->ID) {
                    $resultArray[$car->ID]['number'] = isset($car->Number) ? $car->Number  : null;
                    $resultArray[$car->ID]['spot_id'] = isset($carsStatus->DivisionID) ? $carsStatus->DivisionID  : null;
                    $resultArray[$car->ID]['status'] = isset($carsStatus->Status) ? $carsStatus->Status  : null;
                    $resultArray[$car->ID]['inline'] = isset($carsStatus->InLine) ? $carsStatus->InLine  : null;
                    $resultArray[$car->ID]['type'] = isset($car->Type) ? $car->Type  : null;
                    $resultArray[$car->ID]['model'] = isset($car->Model) ? $car->Model  : null;
                    $resultArray[$car->ID]['description'] = isset($car->Description) ? $car->Description  : null;
                    $resultArray[$car->ID]['year'] = isset($car->Year) ? $car->Year  : null;
                } else {
                    continue;
                }
            }
            foreach($carsPositions as $carsPosition) {
                if($carsPosition->CarsID === $car->ID) {
                    $resultArray[$car->ID]['x_pos'] = preg_replace('/,/', '.',$carsPosition->XPos);
                    $resultArray[$car->ID]['y_pos'] = preg_replace('/,/', '.',$carsPosition->YPos);
                } else {
                    continue;
                }
            }
        }
        return $resultArray;
    }


    /**
     * @return bool
     */
    public static function getCarsFromSoapAndSaveInDB()
    {
        ini_set('memory_limit', '1000M');
        ini_set('max_execution_time', '300');
        $cars = self::getSoapCars();
        foreach($cars as $id => $car) {
            $carModel = self::getOrCreate($id);
            $carModel->id = $id;
            $carModel->number = $car['number'];
            $carModel->spot_id = $car['spot_id'];
            $carModel->type = $car['type'] === null ? null : self::MODELS[$car['type']];
            $carModel->model = $car['model'];
            $carModel->description = $car['description'];
            $carModel->year = $car['year'];
            $carModel->x_pos = $car['x_pos'];
            $carModel->y_pos = $car['y_pos'];
            $carModel->save();
        }
        return true;
    }
}
