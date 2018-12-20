<?php
/**
 * Created by PhpStorm.
 * User: Броненосец
 * Date: 20.12.2018
 * Time: 14:53
 */

namespace app\commands;


use app\models\Car;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class CarsController extends Controller
{
    public $positions=0;
    public $statuses=0;
    public $main=0;

    public function options($actionID)
    {
        return ['positions', 'statuses', 'main'];
    }

    public function optionAliases()
    {
        return ['p' => 'positions', 's' => 'statuses', 'm' => 'main'];
    }

    public function actionIndex()
    {
        if($this->positions) {
            Car::resetCoordinates();
        }
        if($this->main) {
            Car::getCarsFromSoapAndSaveInDB();
        }
        if($this->statuses) {
            Car::resetStatuses();
        }
    }
}