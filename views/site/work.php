

<pre>
<?php
ini_set('memory_limit', '1000M');
ini_set('max_execution_time', '300');
$client = new SoapClient("http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl", [
    'classmap'=>array('LogInInfo'=>'LogInInfo'),
    'debug'=>true,
    'trace'=> true,
]);
$cars = \app\models\Car::find()->where(['spot_id' => '3a185df5-3449-11e5-80bc-1cc1de769e18'])->andWhere(['not',['x_pos' => null]])->all();
$ids = \yii\helpers\ArrayHelper::getColumn(\yii\helpers\ArrayHelper::toArray($cars), 'id');
$arr = ['parameters' => [json_encode(['CarsID' => 'faa'])]];
foreach($ids as $ID) {
    $array[] = ['CarsID' => $ID];
}
$res = json_decode($client->GetCarsPosition(['CarsJson' => json_encode($array)])->return);
var_dump($res);

