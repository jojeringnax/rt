<?php

namespace app\controllers;

use app\models\Car;
use app\models\Division;
use app\models\Spot;
use yii\helpers\Json;

class SpotController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @param $id
     * @return string
     */
    public function actionGetStats($id)
    {
        $spot = Spot::findOne($id);
        $stats = $spot->getCarsNumberWithStatuses();
        return Json::encode([
            'totTs' => $stats['total'],
            'readyTs' => $stats['statuses_count']['G'],
            'onRep' => $stats['statuses_count']['R'],
            'onTO' => $stats['statuses_count']['TO'],
            'onLine' => $stats['inline'],
            'passCar' => $stats['types'][Car::LIGHT],
            'freightCar' => $stats['types'][Car::TRUCK],
            'busCar' => $stats['types'][Car::BUS],
            'specCar' => $stats['types'][Car::SPEC]
        ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionGetCars($id)
    {
        /**
         * @var $spots Spot[]
         */
        $spot = Spot::findOne($id);
        if ($spot == null) return null;
        Car::resetPositions($spot->getCars()->select('id')->column());
        $cars = $spot->getCars()->all();
        if (empty($cars)) {
            return 'NaN';
        }
        $resultArray = [];
        $bounds = Division::getBoundsAsArray($cars);
        $resultArray['cars'] = $cars;
        if (count($bounds) === 4) {
            $resultArray['bounds'] = [
                [$bounds['x_min'], $bounds['y_min']],
                [$bounds['x_max'], $bounds['y_max']]
            ];
        } else {
            $resultArray['center'] = [$bounds['x'], $bounds['y']];
        }
        return Json::encode($resultArray);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionGetName($id)
    {
        $spot = Spot::findOne($id);
        if ($spot == null) return null;
        return $spot->name;
    }

    /**
     * @param $id
     * @return false|string|null
     */
    public function actionGetStatistic($id)
    {
        $spot = Spot::findOne($id);
        if ($spot == null) return null;
        return Json::encode(['statistic' => $spot->getStatistic()->getAttributes(), 'terminals' => $spot->getNumberOfTerminals()]);
    }
}
