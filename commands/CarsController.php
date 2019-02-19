<?php
/**
 * Created by PhpStorm.
 * User: Броненосец
 * Date: 20.12.2018
 * Time: 14:53
 */
namespace app\commands;

ini_set('memory_limit', '1000M');
ini_set('max_execution_time', 0);
use app\models\Car;
use app\models\CarsData;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class CarsController extends Controller
{
    public $positions=0;
    public $statuses=0;
    public $main=0;
    public $data=0;

    public function options($actionID)
    {
        return ['positions', 'statuses', 'main', 'data'];
    }

    public function optionAliases()
    {
        return ['p' => 'positions', 's' => 'statuses', 'm' => 'main', 'd' => 'data'];
    }

    public function actionIndex()
    {
        if($this->positions) Car::resetCoordinates();
        if($this->main) {
            Car::getCarsFromSoapAndSaveInDB();
            CarsData::getAllCarsData();
        }
        if($this->statuses) Car::resetStatuses();
        if($this->data) CarsData::getAllCarsData();
    }
}