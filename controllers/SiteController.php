<?php

namespace app\controllers;
header('Access-Control-Allow-Origin: *');

use app\models\Autocolumn;
use app\models\BadSpot;
use app\models\Car;
use app\models\CarsData;
use app\models\Company;
use app\models\Organization;
use app\models\Spot;
use app\models\Statistic;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{

    public $layout=null;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $organizations = \app\models\Organization::getActives();
        foreach ($organizations as $organization) {
            $organization->carsTotal = $organization->getTotalCars();
        }
        return $this->render('index', [
            'organizations' => $organizations,
            'totalCarsData' => Car::getTotalData(),
            'totalTerminals' => Car::getNumberOfTerminals(),
            'totalStats' => Json::encode(Statistic::getTotalStatistic()),
            'bounds' => Organization::getMaxAndMinCoordinatesForAPI()
        ]);
    }

    public function actionWork($cars=0)
    {
        return $this->renderPartial('work', [
            'cars' => $cars
        ]);
    }


    public function actionPhpinfo()
    {
        return phpinfo();
    }

    public function actionOrganizations()
    {
        $company = new Company();
        $company->id = '762b8f6f-1a46-11e5-be74-00155dc6002b';
        $company->name = 'ООО Ресурс Транс';
        $company->save();
        return Organization::getOrganizationsFromSoapAndSaveInDB();
    }

    public function actionAutocolumns()
    {
        Autocolumn::getAutocolumnsFromSoapAndSaveInDB();
        return 1;
    }

    public function actionSpots()
    {
        Spot::getSpotsFromSoapAndSaveInDB();
        return Spot::fixBadSpots();
    }

    public function actionCars()
    {
        return var_dump(Car::getCarsFromSoapAndSaveInDB());
    }


    public function actionCarsforspot($id) {
        $cars = Car::find()->where(['spot_id' => $id])->andWhere(['not',['x_pos' => null]])->all();
        $ids = ArrayHelper::getColumn(ArrayHelper::toArray($cars), 'id');
        Car::resetPositions($ids, $id);
        $cars = Car::find()->where(['spot_id' => $id])->andWhere(['not',['x_pos' => null]])->all();
        $xMinCars = 1000;
        $xMaxCars = 0;
        $yMinCars = 1000;
        $yMaxCars = 0;
        foreach ($cars as $car) {
            if($car->x_pos < $xMinCars) {
                $xMinCars = $car->x_pos;
            }
            if($car->x_pos > $xMaxCars) {
                $xMaxCars = $car->x_pos;
            }
            if($car->y_pos < $yMinCars) {
                $yMinCars = $car->y_pos;
            }
            if($car->y_pos > $yMaxCars) {
                $yMaxCars = $car->y_pos;
            }
        }
        $resultArray = ArrayHelper::toArray($cars);
        return json_encode(['cars' => $resultArray, 'bounds' => [[$xMinCars, $yMinCars],[$xMaxCars, $yMaxCars]]], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $car_id
     * @return false|string
     */
    public function actionGetCarData($car_id) {
        $client = new \SoapClient(\Yii::$app->params['wsdl']);
        $carsData = json_decode($client->GetCarsData(['CarsID' => $car_id])->return);
        $carsDataModel = CarsData::getOrCreate($car_id);
        $carsDataModel->car_id = $car_id;
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
        return json_encode($carsDataModel->toArray());
    }

    /**
     * @param $autocolumn_id
     * @return false|string
     */
    public function actionGetAutocolumnStatistic($autocolumn_id)
    {
        $autocolumn = Autocolumn::findOne(['id' => $autocolumn_id]);
        if ($autocolumn == null) return null;
        return json_encode(['statistic' => $autocolumn->getStatistic()->getAttributes(), 'terminals' => $autocolumn->getNumberOfTerminals()]);
    }

    /**
     * @param $spot_id
     * @return false|string|null
     */
    public function actionGetSpotStatistic($spot_id)
    {
        $spot = Spot::findOne(['id' => $spot_id]);
        if ($spot == null) return null;
        return json_encode(['statistic' => $spot->getStatistic()->getAttributes(), 'terminals' => $spot->getNumberOfTerminals()]);
    }


    /**
     * @param $organization_id
     * @return false|string|null
     */
    public function actionGetOrganizationStatistic($organization_id)
    {
        $organization = Organization::findOne(['id' => $organization_id]);
        if ($organization == null) return null;
        return json_encode(['statistic' => $organization->getStatistic()->getAttributes(), 'terminals' => $organization->getNumberOfTerminals()]);
    }

    public function actionIndex1()
    {
        return $this->render('index1');
    }

}
