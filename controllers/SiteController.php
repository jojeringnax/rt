<?php

namespace app\controllers;

use app\models\Autocolumn;
use app\models\Car;
use app\models\CarsData;
use app\models\Company;
use app\models\Organization;
use app\models\Spot;
use app\models\Statistic;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
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
        return $this->render('index');
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
        return Spot::getSpotsFromSoapAndSaveInDB();

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
        $client = new \SoapClient("http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl");
        $carsData = json_decode($client->GetCarsData(['CarsID' => '013f6af2-473c-11e5-b89f-00155d630038'])->return);
        $carsDataModel = CarsData::getOrCreate($car_id);
        $carsDataModel->car_id = $car_id;
        $carsDataModel->driver = isset($carsData->Driver) ? $carsData->Driver : null;
        $carsDataModel->phone = isset($carsData->Phone) ? $carsData->Phone : null;
        $carsDataModel->start_time_plan = isset($carsData->StartTimePlan) ? $carsData->StartTimePlan : null;
        $carsDataModel->end_time_plan = isset($carsData->EndTimePlan) ? $carsData->EndTimePlan : null;
        $carsDataModel->work_time_plan = isset($carsData->EndTimePlan) ? $carsData->EndTimePlan : null;
        $carsDataModel->start_time_fact = isset($carsData->StartTimeFact) ? $carsData->StartTimeFact : null;
        $carsDataModel->work_time_fact = isset($carsData->WorkTimeFact) ? $carsData->WorkTimeFact : null;
        $carsDataModel->mileage = isset($carsData->Mileage) ? $carsData->Mileage : null;
        $carsDataModel->speed = isset($carsData->Speed) ? $carsData->Speed : null;
        $carsDataModel->fuel_norm = isset($carsData->FuelNorm) ? $carsData->FuelNorm : null;
        $carsDataModel->fuel_DUT = isset($carsData->FuelDUT) ? $carsData->FuelDUT : null;
        $carsDataModel->driver_mark = isset($carsData->DriverMark) ? $carsData->DriverMark : null;
        $carsDataModel->violations_count = isset($carsData->ViolationsCount) ? $carsData->ViolationsCount : null;
        $carsDataModel->save();
        return json_encode($carsDataModel);
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
        $organizations = Organization::getActives();
        $spots = Spot::getActives();
        $autocolumns = Autocolumn::getActives();
        foreach ($organizations as $organization) {
            $organizationGoodId = $organization->getIdWithoutNumbers();
            $xMinAutocolumns = 1000;
            $xMaxAutocolumns = 0;
            $yMinAutocolumns = 1000;
            $yMaxAutolumns = 0;
            $carsSumsTotalOrganization = 0;
            $carsWithStatusesOrganization = [
                'G' => 0,
                'R' => 0,
                'TO' => 0,
                'inline' => 0
            ];
            $carsTypesOrganization = [0,0,0,0];
            foreach ($autocolumns as $autocolumn) {
                $autocolumnGoodId = $autocolumn->getIdWithoutNumbers();
                if ($autocolumn->organization_id != $organization->id) {
                    continue;
                }
                $orgAutocolumns[$organizationGoodId][] = $autocolumn;
                if($autocolumn->x_pos < $xMinAutocolumns) {
                    $xMinAutocolumns = $autocolumn->x_pos;
                }
                if($autocolumn->x_pos > $xMaxAutocolumns) {
                    $xMaxAutocolumns = $autocolumn->x_pos;
                }
                if($autocolumn->y_pos < $yMinAutocolumns) {
                    $yMinAutocolumns = $autocolumn->y_pos;
                }
                if($autocolumn->y_pos > $yMaxAutolumns) {
                    $yMaxAutolumns = $autocolumn->y_pos;
                }
                if($xMaxAutocolumns === 0) {continue;}
                $xMinSpots = 1000;
                $xMaxSpots = 0;
                $yMinSpots = 1000;
                $yMaxSpots = 0;
                $carsSumTotalAutocolumns = 0;
                $carsWithStatusesAutocolumn = [
                    'G' => 0,
                    'R' => 0,
                    'TO' => 0,
                    'inline' => 0
                ];
                $carsTypesAutocolumn = [0,0,0,0];

                $carsWithGStatus = 0;
                $carsWithRStatus = 0;
                $carsWithTOStatus = 0;
                $carsInline = 0;
                foreach ($spots as $spot) {
                    $carsTypesSpot = [0,0,0,0];
                    if ($spot->autocolumn_id !== $autocolumn->id) {
                        continue;
                    }
                    $carsQuery = Car::find()->where(['spot_id' => $spot->id])->andWhere(['not', ['x_pos' => null]]);
                    $carsSum = $carsQuery->count();
                    $cars = $carsQuery->all();
                    foreach($cars as $car) {
                        if ($car->type !== null){
                            $carsTypesSpot[$car->type]++;
                        }

                        if ($car->inline) {
                            $carsInline++;
                        }
                        if ($car->status === 'G') {
                            $carsWithGStatus++;
                        } elseif ($car->status === 'R') {
                            $carsWithRStatus++;
                        } elseif ($car->status === 'TO') {
                            $carsWithTOStatus++;
                        } else {
                            continue;
                        }
                    }
                    $spot->carsTypes = $carsTypesSpot;
                    $spot->carsNumber = $carsSum;
                    $spot->carsStatuses = [
                        'G' => $carsWithGStatus,
                        'R' => $carsWithRStatus,
                        'TO' => $carsWithTOStatus,
                        'inline' => $carsInline
                        ];
                    $spotsAutocolumn[$autocolumnGoodId][] = $spot;
                    if($spot->x_pos < $xMinSpots) {
                        $xMinSpots = $spot->x_pos;
                    }
                    if($spot->x_pos > $xMaxSpots) {
                        $xMaxSpots = $spot->x_pos;
                    }
                    if($spot->y_pos < $yMinSpots) {
                        $yMinSpots = $spot->y_pos;
                    }
                    if($spot->y_pos > $yMaxSpots) {
                        $yMaxSpots = $spot->y_pos;
                    }
                    if($xMaxSpots === 0) {continue;}
                    $carsSumTotalAutocolumns += $carsSum;


                    for ($i = 0; $i < count(Car::MODELS); $i++) {
                        $carsTypesAutocolumn[$i] += $carsTypesSpot[$i];
                    }
                }
                $carsWithStatusesAutocolumn['G'] = $carsWithGStatus;
                $carsWithStatusesAutocolumn['R'] = $carsWithRStatus;
                $carsWithStatusesAutocolumn['TO'] = $carsWithTOStatus;
                $carsWithStatusesAutocolumn['inline'] = $carsInline;
                $spotsAutocolumn[$autocolumnGoodId]['carsStatuses'] = $carsWithStatusesAutocolumn;
                $spotsAutocolumn[$autocolumnGoodId]['cars'] = $carsSumTotalAutocolumns;
                $spotsAutocolumn[$autocolumnGoodId]['bounds'] = "[[$xMinSpots,$yMinSpots], [$xMaxSpots,$yMaxSpots]]";
                $spotsAutocolumn[$autocolumnGoodId]['carsTypes'] = $carsTypesAutocolumn;



                $carsWithStatusesOrganization['G'] += $carsWithStatusesAutocolumn['G'];
                $carsWithStatusesOrganization['R'] += $carsWithStatusesAutocolumn['R'];
                $carsWithStatusesOrganization['TO'] += $carsWithStatusesAutocolumn['TO'];
                $carsWithStatusesOrganization['inline'] += $carsWithStatusesAutocolumn['inline'];
                for ($i = 0; $i < count(Car::MODELS); $i++) {
                    $carsTypesOrganization[$i] += $carsTypesAutocolumn[$i];
                }
                $carsSumsTotalOrganization += $carsSumTotalAutocolumns;
            }
            $orgAutocolumns[$organizationGoodId]['carsTypes'] = $carsTypesOrganization;
            $orgAutocolumns[$organizationGoodId]['cars'] = $carsSumsTotalOrganization;
            $orgAutocolumns[$organizationGoodId]['carsStatuses'] = $carsWithStatusesAutocolumn;
            $orgAutocolumns[$organizationGoodId]['bounds'] = $yMaxAutolumns ? "[[$xMinAutocolumns,$yMinAutocolumns], [$xMaxAutocolumns,$yMaxAutolumns]]" : false;
        }
        return $this->render('index1', [
            'totalCarsData' => Car::getTotalData(),
            'totalTerminals' => Car::getNUmberOfTerminals(),
            'totalStats' => Statistic::getTotalStatistic(),
            'spots' => $spotsAutocolumn,
            'autocolumns' => $orgAutocolumns,
            'organizations' => $organizations
        ]);
    }

}
