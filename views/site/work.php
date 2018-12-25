

<pre>
<?php
ini_set('memory_limit', '1000M');
ini_set('max_execution_time', '300');
$client = new SoapClient("http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl", [
    'classmap'=>array('LogInInfo'=>'LogInInfo'),
    'debug'=>true,
    'trace'=> true,
]);

$arr = ['parameters' => [json_encode(['CarsID' => 'faa'])]];
var_dump(json_decode($client->GetCarsPosition([
        'CarsJson' => json_encode([
                'CarsID' => '004bb750-168f-11e2-a699-00155da83faf'
        ])
])->return));

