

<pre>
<?php
ini_set('memory_limit', '1000M');
ini_set('max_execution_time', '300');
$client = new SoapClient("http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl", [
    'classmap'=>array('LogInInfo'=>'LogInInfo'),
    'debug'=>true,
    'trace'=> true,
]);
$ac = \app\models\Organization::findOne(' 5b050580-4a6b-11e5-b89f-00155d630038');
//var_dump($ac->getNumberOfTerminals());
var_dump(json_decode($client->GetCarsData(['CarsID' => '013f6af2-473c-11e5-b89f-00155d630038'])->return));

//var_dump(\app\models\Car::getTotalData());

