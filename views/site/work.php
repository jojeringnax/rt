
<pre>
<?php

$client=new SoapClient("http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl", array(
    'trace' => true,
    'stream_context' => stream_context_create(array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    ))));
$cars = json_decode($client->getCars()->return);
$organizations = json_decode($client->getOrganization()->return);
$divisions = json_decode($client->getDivision()->return);
foreach($divisions as $division) {
    $ids[] = $division->ID;
    $parentIds[] = $division->ParentID;
}
foreach($ids as $id) {
    if(in_array($id, $parentIds)) {
        $spots[] = $id;
    } else {
        $autocolumns[] = $id;
    }
}
foreach($divisions as $division) {
    if(in_array($division->id, $spots)) {
        $spot = new \app\models\Spot();

    }
}

//cars {"ID":"0006ccce-b1bd-440f-a00c-651e850d5359","Number":"В629ТХ64","Type":"Автобусы","Model":"ПАЗ 3205","Description":"В629ТХ64 - (ПАЗ 3205)","Year":"1998"}
//organization {"ID":"0500a8be-1a47-11e5-be74-00155dc6002b","Description":"Филиал ООО \"РесурсТранс\" в г. Новосибирск","Аddress":"630099, Новосибирская обл, Новосибирск г, Вокзальная магистраль ул, дом № 16","XPos":"55.031319","YPos":"82.914177"}
//division {"ID":"00082dc7-2ebd-11e5-b05a-00155dc6002b","FirmsID":"762b8f6f-1a46-11e5-be74-00155dc6002b","ParentID":"d7d87234-2ebc-11e5-b05a-00155dc6002b","Description":"Автобригада Врачебный проезд участка № 1 в г. Москва Московской автоколонны  филиала ООО \"РесурсТранс\" в г. Москва","Аddress":"Москва Рижская,  Москва, Врачебный проезд, д. 1","XPos":"55.812272","YPos":"37.461517"}
?>
</pre>
