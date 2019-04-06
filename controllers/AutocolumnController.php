<?php

namespace app\controllers;

use app\models\Autocolumn;
use app\models\Car;
use app\models\Division;
use app\models\Spot;
use yii\helpers\Json;

class AutocolumnController extends \yii\web\Controller
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
        $autocolumn = Autocolumn::findOne($id);
        $stats = $autocolumn->getCarsNumberWithStatuses();
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
    public function actionGetSpots($id)
    {
        /**
         * @var $spots Spot[]
         */
        $autocolumn = Autocolumn::findOne($id);
        $spots = $autocolumn->getSpots()->where(['!=', 'x_pos', 0])->all();
        $resultArray = [];
        foreach ($spots as $spot) {
            $resultArray['spots'][] = [
                'spot' => $spot,
                'carsTotal' => $spot->getTotalCars()
            ];
        }
        $bounds = Division::getBoundsAsArray($spots);
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
        $autocolumn = Autocolumn::findOne($id);
        return $autocolumn->name;
    }
}
