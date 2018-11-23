
<?php
require_once('data/GetData.php');
use rt\data\GetData;

$data = new GetData;
$client = $data->connectSOAP();
$organizations = $client->getOrganization();