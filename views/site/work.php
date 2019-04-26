

<pre>
<?php
ini_set('memory_limit', '1000M');
ini_set('max_execution_time', '300');
$client = new SoapClient(Yii::$app->params['wsdl'], [
    'classmap'=>array('LogInInfo'=>'LogInInfo'),
    'debug'=>true,
    'trace'=> true,
]);

//$carsPositions = json_decode($client->getCarsPosition([])->return);
//$ac = \app\models\Autocolumn::findOne('5f9c7542-2eca-11e5-b05a-00155dc6002b');
//$sp = \app\models\Spot::findOne('f72053f5-2fa9-11e5-b05a-00155dc6002b');
//$org = \app\models\Organization::findOne('762b8f6f-1a46-11e5-be74-00155dc6002b');
//var_dump(\app\models\Statistic::getTotalStatistic());
//var_dump(\app\models\Car::getTotalData());
//var_dump(\app\models\Car::getNumberOfTerminals());
$applications = json_decode($client->GetRequests()->return);
foreach ($applications as $application) {
    echo $application->CountAC;
    echo '<br />';
}
//var_dump($ac->getCarsNumberWithStatuses());
//var_dump($sp->getTotalCars());
//var_dump($sp->getCarsNumberWithStatuses());
//var_dump($ac->getNumberOfTerminals());
//var_dump(json_decode($client->GetCarsData(['CarsID' => 'fc345c34-481b-11e5-b89f-00155d630038'])->return));
//print_r(\app\models\Spot::fixBadSpots());
//var_dump(\app\models\Car::getTotalData());

