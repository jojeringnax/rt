<?php

namespace app\controllers;

use app\models\Car;
use app\models\CarsData;
use yii\helpers\Json;

class CarController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @param $car_id
     * @return false|string
     */
    public function actionGetData($id) {
        $client = new \SoapClient(\Yii::$app->params['wsdl']);
        $carsData = json_decode($client->GetCarsData(['CarsID' => $id])->return);
        $car = Car::findOne($id);
        $carsDataModel = CarsData::getOrCreate($id);
        $carsDataModel->car_id = $id;
        $carsDataModel->driver = isset($carsData->Driver) ? $carsData->Driver : null;
        $carsDataModel->phone = isset($carsData->Phone) ? $carsData->Phone : null;
        $carsDataModel->start_time_plan = isset($carsData->StartTimePlan) ? date("Y-m-d H:i:s", strtotime($carsData->StartTimePlan)) : null;
        $carsDataModel->end_time_plan = isset($carsData->EndTimePlan) ? date("Y-m-d H:i:s", strtotime($carsData->EndTimePlan)) : null;
        $carsDataModel->work_time_plan = isset($carsData->WorkTimePlan) ? (float) $carsData->WorkTimePlan : null;
        $carsDataModel->start_time_fact = isset($carsData->StartTimeFact) ? date("Y-m-d H:i:s", strtotime($carsData->StartTimeFact)) : null;
        $carsDataModel->work_time_fact = isset($carsData->WorkTimeFact) ? (float) $carsData->WorkTimeFact : null;
        $carsDataModel->mileage = isset($carsData->Mileage) ? (integer) $carsData->Mileage : null;
        $carsDataModel->speed = isset($carsData->Speed) ? (integer) $carsData->Speed : null;
        $carsDataModel->fuel_norm = isset($carsData->FuelNorm) ? (float) $carsData->FuelNorm : null;
        $carsDataModel->fuel_DUT = isset($carsData->FuelDUT) ? (integer) $carsData->FuelDUT : null;
        $carsDataModel->driver_mark = isset($carsData->DriverMark) ? $carsData->DriverMark : null;
        $carsDataModel->violations_count = isset($carsData->ViolationsCount) ? (integer) $carsData->ViolationsCount : null;
        $carsDataModel->save();
        return Json::encode(['carsData' => $carsDataModel, 'type' => $car->type]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionGetName($id)
    {
        $car = Car::findOne($id);
        return $car->model;
    }
}
