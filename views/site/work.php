
<pre>
<?php
ini_set('memory_limit', '1000M');
ini_set('max_execution_time', '300');
$client = new SoapClient("http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl");
var_dump(json_decode($client->getOrganization()->return));
