<?php

namespace app\controllers;

use app\models\Autocolumn;
use app\models\Car;
use app\models\Organization;
use app\models\Spot;
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
        return $this->render('work', [
            'cars' => $cars
        ]);
    }


    public function actionPhpinfo()
    {
        return phpinfo();
    }

    public function actionOrganizations()
    {
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
                foreach ($spots as $spot) {
                    if ($spot->autocolumn_id !== $autocolumn->id) {
                        continue;
                    }
                    $carsSum = Car::find()->where(['spot_id' => $spot->id])->andWhere(['not', ['x_pos' => null]])->count();
                    $spot->carsNumber = $carsSum;
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
                    $carsSumsTotalOrganization += $carsSum;
                    $carsSumTotalAutocolumns += $carsSum;
                }
                $spotsAutocolumn[$autocolumnGoodId]['cars'] = $carsSumTotalAutocolumns;
                $spotsAutocolumn[$autocolumnGoodId]["bounds"] = "[[$xMinSpots,$yMinSpots], [$xMaxSpots,$yMaxSpots]]";
            }
            $orgAutocolumns[$organizationGoodId]['cars'] = $carsSumsTotalOrganization;
            $orgAutocolumns[$organizationGoodId]['bounds'] = $yMaxAutolumns ? "[[$xMinAutocolumns,$yMinAutocolumns], [$xMaxAutocolumns,$yMaxAutolumns]]" : false;
        }
        return $this->render('index1', [
            'spots' => $spotsAutocolumn,
            'autocolumns' => $orgAutocolumns,
            'organizations' => $organizations
        ]);
    }

}
