

<pre>
<?php
ini_set('memory_limit', '1000M');
ini_set('max_execution_time', '300');
$client = new SoapClient("http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl", [
    'classmap'=>array('LogInInfo'=>'LogInInfo'),
    'debug'=>true,
    'trace'=> true,
]);
$ac = \app\models\Organization::findOne('762b8f6f-1a46-11e5-be74-00155dc6002b');
var_dump($ac->getNumberOfTerminals());
//var_dump(json_decode($client->GetWBMonitoring()->return));

