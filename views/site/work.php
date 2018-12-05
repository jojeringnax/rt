
<pre>
<?php
ini_set('memory_limit', '1000M');
ini_set('max_execution_time', '300');
$client = new SoapClient("http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl", array(
    'trace' => true,
    'stream_context' => stream_context_create(array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    ))));
if ($cars === 0) {
    $organizations = json_decode($client->getOrganization()->return, true);
    $divisions = json_decode($client->getDivision()->return, true);
    $ids = [];
    $parentIds = [];
    $spots = [];

    /**
     * Company add
     */

    $company = new \app\models\Company();
    $company->id = '762b8f6f-1a46-11e5-be74-00155dc6002b';
    $company->name = 'Ресурс Транс';
    $company->save();

    /**
     * Work with organizations
     */

    foreach ($organizations as $organization) {
        if (isset($organization['Аddress'])) {
            $organizationModel = \app\models\Organization::getOrCreate($organization['ID']);
            $organizationModel->id = $organization['ID'];
            $organizationModel->company_id = '762b8f6f-1a46-11e5-be74-00155dc6002b';
            $organizationModel->description = $organization['Description'];
            $organizationModel->address = $organization['Аddress'];
            $organizationModel->x_pos = $organization['XPos'];
            $organizationModel->y_pos = $organization['YPos'];
            $organizationModel->save();
        }
    }

    /**
     * Work with divisions
     */
    foreach ($divisions as $division) {
        $ids[] = $division['ID'];
        $parentIds[] = $division['ParentID'];
    }

    foreach ($divisions as $division) {
        if (in_array($division['ID'], $parentIds)) {
            $div = \app\models\Autocolumn::getOrCreate($division['ID']);
            $div->organization_id = $division['ParentID'];
        } else {
            $div = \app\models\Spot::getOrCreate($division['ID']);
            $div->organization_id = $division['FirmsID'];
            $div->autocolumn_id = $division['ParentID'];
        }
        $div->company_id = '762b8f6f-1a46-11e5-be74-00155dc6002b';
        $div->id = $division['ID'];
        $div->description = $division['Description'];
        $div->address = $division['Аddress'];
        $div->x_pos = $division['XPos'];
        $div->y_pos = $division['YPos'];
        $div->save();
    }

    /**
     * Work with cars
     */


    //cars {"ID":"0006ccce-b1bd-440f-a00c-651e850d5359","Number":"В629ТХ64","Type":"Автобусы","Model":"ПАЗ 3205","Description":"В629ТХ64 - (ПАЗ 3205)","Year":"1998"}
    //organization {"ID":"0500a8be-1a47-11e5-be74-00155dc6002b","Description":"Филиал ООО \"РесурсТранс\" в г. Новосибирск","Аddress":"630099, Новосибирская обл, Новосибирск г, Вокзальная магистраль ул, дом № 16","XPos":"55.031319","YPos":"82.914177"}
    //division {"ID":"00082dc7-2ebd-11e5-b05a-00155dc6002b","FirmsID":"762b8f6f-1a46-11e5-be74-00155dc6002b","ParentID":"d7d87234-2ebc-11e5-b05a-00155dc6002b","Description":"Автобригада Врачебный проезд участка № 1 в г. Москва Московской автоколонны  филиала ООО \"РесурсТранс\" в г. Москва","Аddress":"Москва Рижская,  Москва, Врачебный проезд, д. 1","XPos":"55.812272","YPos":"37.461517"}
}


$cars = json_decode($client->getCars()->return, true);
foreach ($cars as $car) {
    $carModel = \app\models\Car::getOrCreate($car['ID']);
    $carModel->id = $car['ID'];
    $carModel->number = $car['Number'];
    $carModel->type = rand(0,4);
    $carModel->model = $car['Model'];
    $carModel->year = $car['Year'];
    $carModel->x_pos = rand(47, 133);
    $carModel->y_pos = rand(54, 71);
    $carModel->save();
}
?>
</pre>
