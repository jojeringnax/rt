<?php

namespace app\models;
ini_set('memory_limit', '1000M');
ini_set('max_execution_time', '300');

use Codeception\Util\Soap;
use Yii;
use yii\base\ErrorException;
use yii\db\Exception;
use yii\helpers\Console;

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
 * @property string $status
 * @property bool $inline
 */
class Car extends \yii\db\ActiveRecord
{

    const MODELS = array(
        'Легковые ТС' => 0,
        'Грузовые ТС' => 1,
        'Автобусы' => 2,
        'Спецтехника' => 3
    );

    const LIGHT = 0;
    const TRUCK = 1;
    const BUS = 2;
    const SPEC = 3;


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
        $carsPositions = json_decode($client->getCarsPosition([])->return);
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
        ini_set('max_execution_time', '0');
        $cars = self::getSoapCars();
        $count = count($cars);
        Console::startProgress(0, $count);
        $i = 0;
        foreach($cars as $id => $car) {
            $i++;
            $carModel = self::getOrCreate($id);
            $carModel->id = $id;
            $carModel->number = $car['number'];
            $carModel->spot_id = $car['spot_id'];
            $carModel->type = $car['type'] === null ? null : self::MODELS[$car['type']];
            $carModel->model = $car['model'];
            $carModel->description = $car['description'];
            $carModel->status = $car['status'];
            $carModel->inline = $car['inline'];
            $carModel->year = $car['year'];
            $carModel->x_pos = $car['x_pos'];
            $carModel->y_pos = $car['y_pos'];
            $carModel->save();
            Console::updateProgress($i, $count);
        }
        Console::endProgress();
        return true;
    }

    public static function resetCoordinates()
    {
        ini_set('memory_limit', '1000M');
        ini_set('max_execution_time', '600');
        $client = new \SoapClient('http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl');
        $carsPositions = json_decode($client->getCarsPosition([])->return);
        $i = 0;
        $count = count($carsPositions);
        Console::startProgress(0,$count);
        foreach($carsPositions as $carsPosition) {
            $i++;
            usleep(100);
            $car = self::getOrCreate($carsPosition->CarsID);
            try {
                if($car->x_pos == $carsPosition->XPos && $car->y_pos == $carsPosition->YPos) {
                    echo 'Нечего менять';
                    continue;
                }
                $car->x_pos = $carsPosition->YPos;
                $car->y_pos = $carsPosition->XPos;
                $car->save();
                Console::updateProgress($i, $count);
            } catch (Exception $e) {
                echo 'Не получилось обновить'.PHP_EOL;
                if($car === null) {
                    echo 'Нет машины в БД;';
                } else {
                    echo $e->getMessage();
                    echo $e->getTraceAsString();
                }
                return false;
            }
        }
        Console::endProgress();
        return true;
    }

    public static function resetStatuses()
    {
        ini_set('memory_limit', '1000M');
        ini_set('max_execution_time', '600');
        $client = new \SoapClient('http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl');
        $carsStatuses = json_decode($client->getGarsStatus()->return);
        $count = count($carsStatuses);
        $i = 0;
        Console::startProgress(0,$count);
        foreach ($carsStatuses as $carsStatus) {
            $i++;
            Console::updateProgress($i, $count);
            $car = Car::getOrCreate($carsStatus->CarsID);
            try {
                $car->spot_id = isset($carsStatus->DivisionID) ? $carsStatus->DivisionID : null;
            } catch (Exception $e) {
                $car->spot_id = null;
            };
            $car->status = isset($carsStatus->Status) ? $carsStatus->Status : null;
            $car->inline = isset($carsStatus->InLine) ? $carsStatus->InLine : null;
            $car->save();
        }
        Console::endProgress();
        return true;
    }

    public function resetPosition()
    {
        $client = new \SoapClient('http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl');
        $res = json_decode($client->GetCarsPosition(array('CarsJson' => json_encode(['CarsID' => $this->id])))->return[0]);
        $this->x_pos = $res->YPos;
        $this->y_pos = $res->XPos;
        $this->save();
    }

    public static function resetPositions($carIDs, $spotID = null)
    {
        $client = new \SoapClient('http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl');
        foreach($carIDs as $ID) {
            $array[] = ['CarsID' => $ID];
        }
        $res = json_decode($client->GetCarsPosition(['CarsJson' => json_encode($array)])->return);
        try {
            foreach ($res as $position) {
                $car = Car::getOrCreate($position->CarsID);
                $car->spot_id = $spotID;
                $car->x_pos = $position->YPos;
                $car->y_pos = $position->XPos;
                $car->save();
            }
        } catch (ErrorException $e) {
            return true;
        }
        return $carIDs;
    }

    /**
     * @return string
     */
    public function getIdWithoutNumbers()
    {
        $s = array('/0/','/1/','/2/','/3/','/4/','/5/','/6/','/7/','/8/','/9/', '/-/');
        $a = array('a','b','c','d','e','f','g','h','i','j','');
        return preg_replace($s, $a, $this->id);
    }
}
