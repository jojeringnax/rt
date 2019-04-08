<?php

namespace app\controllers;

use app\models\Autocolumn;
use app\models\BadSpot;
use app\models\Car;
use app\models\Division;
use app\models\Organization;
use yii\helpers\Json;

class OrganizationController extends \yii\web\Controller
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
        $organization = Organization::findOne($id);
        $stats = $organization->getCarsNumberWithStatuses();
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
    public function actionGetAutocolumns($id)
    {
        /**
         * @var $autocolumns Autocolumn[]|Autocolumn
         */
        $organization = Organization::findOne($id);
        $autocolumns = $organization->getAutocolumns()->where(['!=', 'x_pos', 0])->all();
        if (empty($autocolumns)) {
            return 'NaN';
        }
        $resultArray = [];
        foreach ($autocolumns as $autocolumn) {
            $resultArray[] = [
                'autocolumn' => $autocolumn,
                'carsTotal' => $autocolumn->getTotalCars()
            ];
        }
        return Json::encode($resultArray);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionGetBadSpots($id)
    {
        /**
         * @var $badSpots BadSpot[]|BadSpot
         */
        $organization = Organization::findOne($id);
        $badSpots = $organization->getBadSpots()->where(['!=', 'x_pos', 0])->all();
        $autocolumns = $organization->getAutocolumns()->where(['!=', 'x_pos', 0])->all();
        if (empty($badSpots)) {
            if (empty($autocolumns)) {
                return 'NaN';
            }
            $resultArray['badSpots'] = [];
        }
        foreach ($badSpots as $badSpot) {
            $resultArray['badSpots'][] = [
                'badSpot' => $badSpot,
                'carsTotal' => $badSpot->getTotalCars()
            ];
        }
        $bounds = Division::getBoundsAsArray(array_merge($badSpots, $autocolumns));
        if (count($bounds) === 4) {
            if ($bounds['x_min'] == $bounds['x_max'] && $bounds['y_min'] == $bounds['y_max']) {
                $resultArray['center'] = [$bounds['x_min'], $bounds['y_min']];
            } else {
                $resultArray['bounds'] = [
                    [$bounds['x_min'], $bounds['y_min']],
                    [$bounds['x_max'], $bounds['y_max']]
                ];
            }
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
        $organization = Organization::findOne($id);
        return $organization->description;
    }

    /**
     * @param $id
     * @return string|null
     */
    public function actionGetStatistic($id)
    {
        $organization = Organization::findOne(['id' => $id]);
        if ($organization == null) return null;
        return Json::encode(['statistic' => $organization->getStatistic()->getAttributes(), 'terminals' => $organization->getNumberOfTerminals()]);
    }

}
