

<script type="text/javascript">
    $(document).ready(function(){
        console.log('asd')
        $('.transort-department').mouseover(function(){
            let img = $(this).children('.transport-title').children('.span-h3-filial').children('img');
            img.attr('src', 'yan/img/auto_icon/point_'+img.data('type')+'.svg');
        });
        $('.transort-department').mouseleave(function(){
            let img = $(this).children('.transport-title').children('.span-h3-filial').children('img');
            img.attr('src', 'yan/img/auto_icon/point_blue_'+img.data('type')+'.svg');
        });
    });
</script>
<?php
/**
 * @var $organizations \app\models\Organization[]
 * @var $autocolumns \app\models\Autocolumn[]
 * @var $spots \app\models\Spot[]
 */
?>
<?= $this->render('sidebar') ?>

<?php $breadcrumps = []; ?>
<!---->
<!--<style>-->
<!--    [class*="ymaps-2"][class*="-ground-pane"] {-->
<!--        filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");-->
<!--        -webkit-filter: grayscale(100%) brightness(30%);-->
<!--    }-->
<!--</style>-->

<script>
    let idOfCurrentElement = {
        organizations: '',
        autocolumn: '',
        spot: ''
    };

    let dataLevel = {
        firms :{
            totalCars: '',
            oinLine:'',
            onRep:'',
            onTO: '',
            applications_executed: '',
            applications_canceled: '',
            applications_sub: '',
            applications_ac:'' ,
            waybills_total: '',
            accidents_total: '',
            WB_M: '',
            fuel: '' ,
            terminals: ''
        },
        organization: {
            light:'',
            truck: '',
            bus: '',
            spec: '',
            totalCars: '',
            oinLine:'',
            onRep:'',
            onTO: '',
            applications_executed: '',
            applications_canceled: '',
            applications_sub: '',
            applications_ac:'' ,
            waybills_total: '',
            accidents_total: '',
            WB_M: '',
            fuel: '' ,
            terminals: ''
        },
        autocolumn: {
            light:'',
            truck: '',
            bus: '',
            spec: '',
            totalCars: '',
            oinLine:'',
            onRep:'',
            onTO: '',
            applications_executed: '',
            applications_canceled: '',
            applications_sub: '',
            applications_ac:'' ,
            waybills_total: '',
            accidents_total: '',
            WB_M: '',
            fuel: '' ,
            terminals: ''
        },
        spot: {
            light:'',
            truck: '',
            bus: '',
            spec: '',
            totalCars: '',
            oinLine:'',
            onRep:'',
            onTO: '',
            applications_executed: '',
            applications_canceled: '',
            applications_sub: '',
            applications_ac:'' ,
            waybills_total: '',
            accidents_total: '',
            WB_M: '',
            fuel: '' ,
            terminals: ''
        }
    };

    let firmsData = <?= json_encode($totalStats->getAttributes()) ?>;
    let totalTerminals =<?= json_encode($totalTerminals) ?>;
    let totalCarsData =<?= json_encode($totalCarsData) ?>;
    let changeInfo = function(totTs, onLine, onRep, onTO, passCar, freightCar, busCar, specCar) {
        $('#totTs').html(totTs);
        $('#OnLine').html(onLine);
        $('#OnRep').html(onRep);
        $('#onTo').html(onTO);
        $('#passCar').html(passCar);
        $('#freightCar').html(freightCar);
        $('#busCar').html(busCar);
        $('#specCar').html(specCar);
    };

    let waybills_total = (firmsData['waybills_processed']/firmsData['waybills_total']).toFixed(2);
    let accidents_total = (firmsData['accidents_guilty']/firmsData['accidents_total']).toFixed(2);
    let applications_ac = (firmsData['applications_ac']/firmsData['applications_total']).toFixed(2);
    let fuel = (Math.round(firmsData['fuel'])/firmsData['time']).toFixed(2);
    let WB_M = (Math.round(firmsData['WB_M'])/firmsData['WB_ALL']).toFixed(2);


    dataLevel['firms'] = {
        totalCars: totalCarsData['G'],
        onLine: totalCarsData['totalInline'],
        onRep: totalCarsData['R'],
        onTO: totalCarsData['TO'],
        applications_executed: firmsData['applications_executed'],
        applications_canceled: firmsData['applications_canceled'],
        applications_sub: firmsData['applications_sub'],
        applications_ac:applications_ac ,
        waybills_total: waybills_total,
        accidents_total: accidents_total,
        WB_M: WB_M,
        fuel: fuel ,
        terminals: totalTerminals
    };

    //add data -> COMPANY

        //compAmOfTs
        applicationAdd('compAmOfTs', totalCarsData['G']);

        //compOnLine
        applicationAdd('compOnLine', totalCarsData['totalInline']);

        //compOnRep
        applicationAdd('compOnRep', totalCarsData['R']);

        //compOnTo
        applicationAdd('compOnTo', totalCarsData['TO']);

        //comp_applications_executed
        applicationAdd('comp_applications_executed',firmsData['applications_executed']);

        //canceled_app
        applicationAdd('comp_applications_canceled',firmsData['applications_canceled']);

        //sub_app
        applicationAdd('comp_applications_sub',firmsData['applications_sub']);

        //ac_app
        circleBar('comp_applications_ac', (firmsData['applications_ac']/firmsData['applications_total']).toFixed(2));

        //waybills = waybills_processed / waybills_total
        circleBar('comp_waybills_total', (firmsData['waybills_processed']/firmsData['waybills_total']).toFixed(2));

        //accidents = accidents_guilty / accidents_total
        circleBar('comp_accidents_total', (firmsData['accidents_guilty']/firmsData['accidents_total']).toFixed(2));

        //GetWBMonitoring =  CountM/CountAll
        circleBar('comp_WB_M', (Math.round(firmsData['WB_M'])/firmsData['WB_ALL']).toFixed(2));

        // TMCH = fuel/time
        applicationAdd('comp_fuel', (Math.round(firmsData['fuel'])/firmsData['time']).toFixed(2));

        //terminals =
        applicationAdd('comp_terminals', totalTerminals);

    //end add data -> COMPANY


    $('.loading-layout').css({'display':'none'});
    ymaps.ready( function() {
        $('.bbb > span').html('ООО РесурсТранс');
        window.currentElement = {};
        var addArrayOnMap = function(array, map) {
            array.forEach(function (el) {
               map.geoObjects.add(el);
            });
        };

        var removeArrayFromMap = function(array, map) {
            array.forEach(function(el) {
             map.geoObjects.remove(el)
            });
        };


        var removeAllDontNeed = function(level=0) {
            if (o_array.length) {
                removeArrayFromMap(o_array, myMap);
            }
            if (a_array.length) {
                //myMap.geoObjects.remove(clustererAutocolumns);
                removeArrayFromMap(a_array, myMap);
                if (level === 0) {
                    a_array = [];
                }
            }
            if (s_array.length) {
                //myMap.geoObjects.remove(clustererSpots);
                removeArrayFromMap(s_array, myMap);
                if (level > 0) {
                    s_array = [];
                }
            }
            if(c_array.length) {
                removeArrayFromMap(c_array, myMap);
                if (level > 1) {
                    c_array = [];
                }
            }
        };


        var c_array = [], s_array = [], a_array = [], o_array = [];

        var ClusterLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="bb-cluster"><span class="bb-num">{{ properties.geoObjects.length }}</span></div>'
        );

        var CarClusterLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="bb-cluster-car"><span class="bb-num">{{ properties.geoObjects.length }}</span></div>'
        );

        let clustererAutocolumns = new ymaps.Clusterer(
            {
                clusterIcons: [{
                    href: '',
                    size: [100, 100],
                    offset: [-50, -50]
                }],
                gridSize: 256,
                clusterIconContentLayout: ClusterLayout,
                zoomMargin : [50,100,50,50]
            }
        );
        let clustererSpots = new ymaps.Clusterer(
            {
                clusterIcons: [{
                    href: '',
                    size: [100, 100],
                    offset: [-50, -50]
                }],
                gridSize: 256,
                clusterIconContentLayout: ClusterLayout,
                zoomMargin : [50,100,50,50]
            }
        );

        let clustererCars = new ymaps.Clusterer(
            {
                clusterIcons: [{
                    href: '',
                    size: [100, 100],
                    offset: [-50, -50]
                }],
                gridSize: 128,
                clusterIconContentLayout: CarClusterLayout,
                zoomMargin : [50,50,50,50]
            }
        );


        var myMap = new ymaps.Map('map', {
            center: [55.751574, 37.573856],
            zoom: 11,
            behaviors: ['default', 'scrollZoom'],
            controls: []
        }, {
            searchControlProvider: 'yandex#search',
            suppressMapOpenBlock: true
        });

        <?php foreach ($organizations as $organization) {
            $organizationPrettyId = $organization->getIdWithoutNumbers();
            ?>
        var OrgLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="bb"><span class="bb-num"><?= $autocolumns[$organizationPrettyId]["cars"] ?></span> <span class="bb-name"><?= $organization->getTown()?></span></div>'
        );
            o_pm = new ymaps.Placemark([<?= $organization->x_pos ?>, <?= $organization->y_pos ?>], {
                iconCaption : '<?= $organization->getTown() ?>',
                hintContent: '<?= $organization->getTown() ?>'
            }, {
                iconLayout: 'default#imageWithContent',
                iconImageHref: '',
                iconImageSize: [102, 107.5],
                iconContentOffset: [-74, 83],
                iconImageOffset: [-24, -24],
                preset: 'islands#greenDotIconWithCaption',
                iconContentLayout: OrgLayout
            });

            <?php if ($autocolumns[$organizationPrettyId]["cars"] !== 0) { ?>
                o_array.push(o_pm);
            <?php } ?>
            o_pm.breadcrumps = '<?= 'Филиал '.$organization->getTown() ?>';
            o_pm.events.add('click', function(o) {
                changeInfo(
                    <?= $autocolumns[$organizationPrettyId]["cars"] ?>,
                    <?= $autocolumns[$organizationPrettyId]["carsStatuses"]["inline"] ?>,
                    <?= $autocolumns[$organizationPrettyId]["carsStatuses"]["R"] ?>,
                    <?= $autocolumns[$organizationPrettyId]["carsStatuses"]["TO"] ?>,
                    <?= $autocolumns[$organizationPrettyId]["carsTypes"][\app\models\Car::LIGHT] ?>,
                    <?= $autocolumns[$organizationPrettyId]["carsTypes"][\app\models\Car::TRUCK] ?>,
                    <?= $autocolumns[$organizationPrettyId]["carsTypes"][\app\models\Car::BUS] ?>,
                    <?= $autocolumns[$organizationPrettyId]["carsTypes"][\app\models\Car::SPEC] ?>
                );

                $('#info-company').addClass('hide');
                $('#ts-info').addClass('hide');
                $('#info-department').removeClass('hide');
                removeAllDontNeed();

                //ajax request -> ORGANIZATION
                idOfCurrentElement['organizations'] = "<?= $organization->id ?>";

                $.ajax({
                    url: "index.php?r=site/get-organization-statistic&organization_id=" + "<?= $organization->id ?>",
                    type: 'get',
                    success: function(res) {
                        let terminals = JSON.parse(res)['terminals'];
                        let data = JSON.parse(res)['statistic'];

                        let waybills_total = (data['waybills_processed']/data['waybills_total']).toFixed(2);
                        let accidents_total = (data['accidents_guilty']/data['accidents_total']).toFixed(2);
                        let applications_ac = (data['waybills_processed']/data['waybills_total']).toFixed(2);
                        let fuel = (Math.round(data['fuel'])/data['time']).toFixed(2);
                        let WB_M = (Math.round(data['WB_M'])/data['WB_ALL']).toFixed(2);

                        dataLevel['organization'] = {
                            totalCars: <?= $autocolumns[$organizationPrettyId]["cars"] ?>,
                            onLine:  <?= $autocolumns[$organizationPrettyId]["carsStatuses"]["inline"] ?>,
                            onRep: <?= $autocolumns[$organizationPrettyId]["carsStatuses"]["R"] ?>,
                            onTO: <?= $autocolumns[$organizationPrettyId]["carsStatuses"]["TO"] ?>,
                            light: <?= $autocolumns[$organizationPrettyId]["carsTypes"][\app\models\Car::LIGHT] ?>,
                            truck: <?= $autocolumns[$organizationPrettyId]["carsTypes"][\app\models\Car::TRUCK] ?>,
                            bus: <?= $autocolumns[$organizationPrettyId]["carsTypes"][\app\models\Car::BUS] ?>,
                            spec: <?= $autocolumns[$organizationPrettyId]["carsTypes"][\app\models\Car::SPEC] ?>,
                            applications_executed: data['applications_executed'],
                            applications_canceled: data['applications_canceled'],
                            applications_sub: data['applications_sub'],
                            applications_ac: applications_ac,
                            waybills_total: waybills_total,
                            accidents_total: accidents_total,
                            WB_M: WB_M,
                            fuel: fuel ,
                            terminals: terminals
                        };

                        //example of data = {
                        //{"id":null,
                        // "spot_id":null,
                        // "autocolumn_id":null,
                        // "applications_total":289,
                        // "applications_executed":260,
                        // "applications_canceled":11,
                        // "applications_sub":3,
                        // "applications_ac":0,
                        // "applications_mp":0,
                        // "waybills_total":3769,
                        // "waybills_processed":3438,
                        // "accidents_total":0,
                        // "accidents_guilty":0,
                        // "time":91025.12,
                        // "fuel":25134.369999999995,
                        // "WB_M":1988,"WB_ALL":2013}

                        //executed_app
                        applicationAdd('applications_executed',data['applications_executed']);

                        //canceled_app
                        applicationAdd('applications_canceled',data['applications_canceled']);

                        //sub_app
                        applicationAdd('applications_sub',data['applications_sub']);

                        //ac_app
                        circleBar('applications_ac', (data['applications_ac']/data['applications_total']).toFixed(2));

                        //waybills = waybills_processed / waybills_total
                        circleBar('waybills_total', (data['waybills_processed']/data['waybills_total']).toFixed(2));

                        //accidents = accidents_guilty / accidents_total
                        circleBar('accidents_total', (data['accidents_guilty']/data['accidents_total']).toFixed(2));

                        //GetWBMonitoring =  CountM/CountAll
                        circleBar('WB_M', (Math.round(data['WB_M'])/data['WB_ALL']).toFixed(2));

                        // TMCH = fuel/time
                        applicationAdd('fuel', (Math.round(data['fuel'])/data['time']).toFixed(2));

                        //terminals =
                        applicationAdd('terminals', terminals);
                    }
                }); //end ajax request

            <?php
                foreach ($autocolumns[$organizationPrettyId] as $key1 => $autocolumn) {
                    if ($key1 !== 'bounds' && $key1 !== 'cars' && $key1 !== 'carsStatuses' && $key1 !== 'carsTypes') {
                        $autocolumnPrettyId = $autocolumn->getIdWithoutNumbers(); ?>
                var AutoColLayout = ymaps.templateLayoutFactory.createClass(
                    '<div class="bb"><span class="bb-num"><?= $spots[$autocolumnPrettyId]["cars"] ?></span> <span id="auto_name" class="bb-name"><?= $autocolumn->name ?></span></div>'
                );
                a_pm = new ymaps.Placemark([<?= $autocolumn->x_pos ?>, <?= $autocolumn->y_pos ?>], {
                    hintContent: '<?= $autocolumn->description ?>'
                }, {
                    iconLayout: 'default#imageWithContent',
                    iconImageHref: '',
                    iconImageSize: [110, 115.5],
                    iconContentOffset: [-70, 75],
                    iconImageOffset: [-24, -24],
                    preset: 'islands#greenDotIconWithCaption',
                    iconContentLayout: AutoColLayout
                });
                a_pm.breadcrumps = '<?= $autocolumn->name ?>';
                <?php if ($spots[$autocolumnPrettyId]["cars"] !== 0) { ?>
                    a_array.push(a_pm);
                <?php } ?>
                a_pm.events.add('click', function(a) {
                    changeInfo(
                        <?= $spots[$autocolumnPrettyId]["cars"] ?>,
                        <?= $spots[$autocolumnPrettyId]["carsStatuses"]["inline"] ?>,
                        <?= $spots[$autocolumnPrettyId]["carsStatuses"]["R"] ?>,
                        <?= $spots[$autocolumnPrettyId]["carsStatuses"]["TO"] ?>,
                        <?= $spots[$autocolumnPrettyId]["carsTypes"][\app\models\Car::LIGHT] ?>,
                        <?= $spots[$autocolumnPrettyId]["carsTypes"][\app\models\Car::TRUCK] ?>,
                        <?= $spots[$autocolumnPrettyId]["carsTypes"][\app\models\Car::BUS] ?>,
                        <?= $spots[$autocolumnPrettyId]["carsTypes"][\app\models\Car::SPEC] ?>
                    );

                    $('#info-company').addClass('hide');
                    $('#ts-info').addClass('hide');
                    $('#info-department').removeClass('hide');
                    removeAllDontNeed(1);

                    //ajax request -> AUTOCOLUMN
                    idOfCurrentElement['autocolumn'] = "<?= $autocolumn->id ?>";
                    $.ajax({
                        url: "index.php?r=site/get-autocolumn-statistic&autocolumn_id=" + "<?= $autocolumn->id ?>",
                        type: 'get',
                        success: function(res) {
                            let terminals = JSON.parse(res)['terminals'];
                            let data = JSON.parse(res)['statistic'];

                            let waybills_total = (data['waybills_processed']/data['waybills_total']).toFixed(2);
                            let accidents_total = (data['accidents_guilty']/data['accidents_total']).toFixed(2);
                            let applications_ac = (data['waybills_processed']/data['waybills_total']).toFixed(2);
                            let fuel = (Math.round(data['fuel'])/data['time']).toFixed(2);
                            let WB_M = (Math.round(data['WB_M'])/data['WB_ALL']).toFixed(2);

                            dataLevel['autocolumn'] = {
                                totalCars: <?= $spots[$autocolumnPrettyId]["cars"] ?>,
                                onLine:  <?= $spots[$autocolumnPrettyId]["carsStatuses"]["inline"] ?>,
                                onRep: <?= $spots[$autocolumnPrettyId]["carsStatuses"]["R"] ?>,
                                onTO: <?= $spots[$autocolumnPrettyId]["carsStatuses"]["TO"] ?>,
                                light: <?= $spots[$autocolumnPrettyId]["carsTypes"][\app\models\Car::LIGHT] ?>,
                                truck: <?= $spots[$autocolumnPrettyId]["carsTypes"][\app\models\Car::TRUCK] ?>,
                                bus: <?= $spots[$autocolumnPrettyId]["carsTypes"][\app\models\Car::BUS] ?>,
                                spec: <?= $spots[$autocolumnPrettyId]["carsTypes"][\app\models\Car::SPEC] ?>,
                                applications_executed: data['applications_executed'],
                                applications_canceled: data['applications_canceled'],
                                applications_sub: data['applications_sub'],
                                applications_ac: applications_ac,
                                waybills_total: waybills_total,
                                accidents_total: accidents_total,
                                WB_M: WB_M,
                                fuel: fuel ,
                                terminals: terminals
                            };

                            //example of data = {
                                // "id":null,
                                // "spot_id":null,
                                // "autocolumn_id":"68280a1c-2acf-11e5-b13d-00155dc6002b",
                                // "applications_total":76,
                                // "applications_executed":67,
                                // "applications_canceled":3,
                                // "applications_sub":0,
                                // "applications_ac":0,
                                // "applications_mp":0,
                                // "waybills_total":433,
                                // "waybills_processed":427,
                                // "accidents_total":0,
                                // "accidents_guilty":0,
                                // "time":9681,
                                // "fuel":2340.9700000000003
                            // }

                            //executed_app
                            applicationAdd('applications_executed',data['applications_executed']);

                            //canceled_app
                            applicationAdd('applications_canceled',data['applications_canceled']);

                            //sub_app
                            applicationAdd('applications_sub',data['applications_sub']);

                            //ac_app
                            circleBar('applications_ac', (data['applications_ac']/data['applications_total']).toFixed(2));

                            //waybills = waybills_processed / waybills_total
                            circleBar('waybills_total', (data['waybills_processed']/data['waybills_total']).toFixed(2));

                            //accidents = accidents_guilty / accidents_total
                            circleBar('accidents_total', (data['accidents_guilty']/data['accidents_total']).toFixed(2));

                            //GetWBMonitoring =  CountM/CountAll
                            circleBar('WB_M', (Math.round(data['WB_M'])/data['WB_ALL']).toFixed(2));

                            // TMCH = fuel/time
                            applicationAdd('fuel', (Math.round(data['fuel'])/data['time']).toFixed(2));

                            //terminals =
                            applicationAdd('terminals', terminals);
                        }
                    }); //end ajax request

            <?php
                if (array_key_exists($autocolumnPrettyId, $spots)) {
                    foreach ($spots[$autocolumnPrettyId] as $key2 => $spot) {
                        if ($key2 !== 'bounds' && $key2 !== 'cars' && $key2 !== 'carsStatuses' && $key2 !== 'carsTypes') {
                            $spotPrettyId = $spot->getIdWithoutNumbers(); ?>
                            var SpotsLayout = ymaps.templateLayoutFactory.createClass(
                                '<div class="bb"><span class="bb-num"><?= $spot->carsNumber ?></span><span id="spot_name" class="bb-name"><?= $spot->name ?></span></div>'
                            );
                            s_pm = new ymaps.Placemark([<?= $spot->x_pos ?>, <?= $spot->y_pos ?>],{
                                hintContent: '<?= $spot->description ?>'
                            }, {
                                iconLayout: 'default#imageWithContent',
                                iconImageHref: '',
                                iconImageSize: [115, 115],
                                iconContentOffset: [-68, 75],
                                iconImageOffset: [-24, -24],
                                preset: 'islands#greenDotIconWithCaption',
                                iconContentLayout: SpotsLayout
                            });
                            <?php if ($spot->carsNumber) { ?>
                                s_array.push(s_pm);
                            <?php } ?>
                            s_pm.breadcrumps = '<?= $spot->name ?>';
                            s_pm.events.add('click', function(s) {
                                changeInfo(
                                    <?= $spot->carsNumber ?>,
                                    <?= $spot->carsStatuses["inline"] ?>,
                                    <?= $spot->carsStatuses["R"] ?>,
                                    <?= $spot->carsStatuses["TO"] ?>,
                                    <?= $spot->carsTypes[\app\models\Car::LIGHT] ?>,
                                    <?= $spot->carsTypes[\app\models\Car::TRUCK] ?>,
                                    <?= $spot->carsTypes[\app\models\Car::BUS] ?>,
                                    <?= $spot->carsTypes[\app\models\Car::SPEC] ?>
                                );
                                $('.loading-layout').css({'display':'flex'});
                                $('#info-company').addClass('hide');
                                $('#info-department').removeClass('hide');
                                $('#ts-info').addClass('hide');






                                //ajax request -> SPOT
                                idOfCurrentElement['spot'] = "<?= $spot->id ?>";
                                $.ajax({
                                    url: "index.php?r=site/get-spot-statistic&spot_id=" + "<?= $spot->id ?>",
                                    type: 'get',
                                    success: function(res) {
                                        let terminals = JSON.parse(res)['terminals'];
                                        let data = JSON.parse(res)['statistic'];

                                        //executed_app
                                        applicationAdd('applications_executed',data['applications_executed']);

                                        //canceled_app
                                        applicationAdd('applications_canceled',data['applications_canceled']);

                                        //sub_app
                                        applicationAdd('applications_sub',data['applications_sub']);

                                        //ac_app
                                        circleBar('applications_ac', (data['applications_ac']/data['applications_total']).toFixed(2));

                                        //waybills = waybills_processed / waybills_total
                                        circleBar('waybills_total', (data['waybills_processed']/data['waybills_total']).toFixed(2));

                                        //accidents = accidents_guilty / accidents_total
                                        circleBar('accidents_total', (data['accidents_guilty']/data['accidents_total']).toFixed(2));

                                        //GetWBMonitoring =  CountM/CountAll
                                        circleBar('WB_M', (Math.round(data['WB_M'])/data['WB_ALL']).toFixed(2));

                                        // TMCH = fuel/time
                                        applicationAdd('fuel', (Math.round(data['fuel'])/data['time']).toFixed(2));

                                        //terminals =
                                        applicationAdd('terminals', terminals);

                                        let waybills_total = (data['waybills_processed']/data['waybills_total']).toFixed(2);
                                        let accidents_total = (data['accidents_guilty']/data['accidents_total']).toFixed(2);
                                        let applications_ac = (data['waybills_processed']/data['waybills_total']).toFixed(2);
                                        let fuel = (Math.round(data['fuel'])/data['time']).toFixed(2);
                                        let WB_M = (Math.round(data['WB_M'])/data['WB_ALL']).toFixed(2);

                                        dataLevel['spot'] = {
                                            totalCars: <?= $spot->carsNumber ?>,
                                            onLine:  <?= $spot->carsStatuses["inline"] ?>,
                                            onRep: <?= $spot->carsStatuses["R"] ?>,
                                            onTO: <?= $spot->carsStatuses["TO"] ?>,
                                            light: <?= $spot->carsTypes[\app\models\Car::LIGHT] ?>,
                                            truck: <?= $spot->carsTypes[\app\models\Car::TRUCK] ?>,
                                            bus: <?= $spot->carsTypes[\app\models\Car::BUS] ?>,
                                            spec: <?= $spot->carsTypes[\app\models\Car::SPEC] ?>,
                                            applications_executed: data['applications_executed'],
                                            applications_canceled: data['applications_canceled'],
                                            applications_sub: data['applications_sub'],
                                            applications_ac: applications_ac,
                                            waybills_total: waybills_total,
                                            accidents_total: accidents_total,
                                            WB_M: WB_M,
                                            fuel: fuel,
                                            terminals: terminals
                                        };
                                    }
                                }); //end ajax request

                                $.ajax({
                                    url: 'index.php?r=site/carsforspot&id=<?= $spot->id ?>',
                                    method: 'GET',
                                    dataType: 'json',
                                    success: function(data) {
                                        if(c_array.length) {
                                            c_array = [];
                                            myMap.geoObjects.remove(clustererCars);
                                            clustererCars.removeAll();
                                        }
                                        removeArrayFromMap(s_array, myMap);
                                        //myMap.geoObjects.remove(clustererSpots);
                                            // 'Легковые ТС' => 0,
                                            // 'Грузовые ТС' => 1,
                                            // 'Автобусы' => 2,
                                            // 'Спецтехника' => 3
                                        data.cars.forEach(function(el) {
                                            let carsLayout = ymaps.templateLayoutFactory.createClass(
                                                '<div class="bb"><span class="bb-num-car"><img src="yan/img/auto_icon/point_' + el.type + '.svg" alt="auto"></span></div>'
                                            );
                                          c_pm = new ymaps.Placemark([el.x_pos, el.y_pos], {
                                              hintContent: el.description,
                                              balloonContent: el.description,
                                              balloonContentHeader: el.model,
                                              balloonContentBody: el.number,
                                              balloonContentFooter: el.status
                                          },{
                                              hasBalloon: false,
                                              iconLayout: 'default#imageWithContent',
                                              iconImageHref: '',
                                              iconImageSize: [110, 115.5],
                                              iconContentOffset: [-70, 75],
                                              iconImageOffset: [-24, -24],
                                              iconContentLayout: carsLayout
                                          });
                                          c_pm.type = el.type;
                                          c_pm.breadcrumps = el.description;
                                          c_pm.events.add('click', function (c) {
                                              $('.loading-layout').css({'display':'flex'});
                                              if (window.currentElement.hasOwnProperty('car')) {
                                                  let carOldLayout = ymaps.templateLayoutFactory.createClass(
                                                      '<div class="bb"><span class="bb-num-car"><img src="yan/img/auto_icon/point_' + window.currentElement.car.type + '.svg" alt="auto"></span></div>'
                                                  );
                                                  window.currentElement.car.options.set('iconContentLayout', carOldLayout);
                                              }
                                              carsLayout = ymaps.templateLayoutFactory.createClass(
                                                  '<div class="bb"><span class="bb-num-car-white"><img src="yan/img/auto_icon/point_blue_' + el.type + '.svg" alt="auto"></span></div>'
                                              );
                                              c.originalEvent.target.options.set('iconContentLayout', carsLayout);
                                              window.currentElement.car = c.originalEvent.target;
                                              myMap.setCenter(window.currentElement.car.geometry._coordinates, 19);
                                              $('#info-company').addClass('hide');
                                              $('#info-department').addClass('hide');
                                              $('#ts-info').removeClass('hide');
                                              $('.bbb > span').html(window.currentElement.organization.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.autocolumn.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.spot.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.car.breadcrumps);

                                              console.log(window.currentElement);


                                              //battery_change_days: 248
                                              //description: "Х009СС197 - (УАЗ 390995)"
                                              //id: "f6310338-481b-11e5-b89f-00155d630038"
                                              //inline: 1
                                              //model: "УАЗ 390995"
                                              //number: "Х009СС197"
                                              //profitability: 81
                                              //spot_id: "137b4a11-4e39-11e6-80be-1cc1def361b0"
                                              //status: "G"
                                              //technical_inspection_days: 1612
                                              //terminal: 1
                                              //tire_change_days: 10772
                                              //tire_season: "Всесезонные"
                                              //type: 1
                                              //x_pos: 55.3855
                                              //y_pos: 36.7841
                                              //year: 2011
                                              //add cars information

                                              console.log('--- el', el)
                                              let url_img = "yan/img/auto_icon/point_blue_" + el.type + ".svg";

                                              document.getElementById('img-ts').setAttribute('src', url_img);

                                              applicationAdd('nameTS', el['model']);

                                              applicationAdd('profitabilities', el['profitability']);

                                              applicationAdd('seasonality', el['tire_season']);

                                              applicationAdd('tier-change', el['tire_change_days']);

                                              applicationAdd('battery-change', el['battery_change_days']);

                                              applicationAdd('untilTO', el['technical_inspection_days']);

                                                console.log(el.id);
                                              $.ajax({
                                                  url: "index.php?r=site/get-car-data&car_id=" + el.id,
                                                  type: 'get',
                                                  success: function(res) {
                                                      console.log('---keys', Object.keys(res).length);
                                                      let obj = new Object();
                                                      obj = JSON.parse(res);
                                                      if (Object.keys(res).length === 2) {
                                                         // console.log('huy')
                                                          applicationAdd('driver-name', 'н/д');

                                                          applicationAdd('driver-phone', 'н/д');

                                                          applicationAdd('plan-start', 'н/д');

                                                          applicationAdd('fact-start', 'н/д');

                                                          applicationAdd('fact-end', 'н/д');

                                                          applicationAdd('fuel-time-plan', 'н/д');

                                                          applicationAdd('fuel-time-work', 'н/д');

                                                          applicationAdd('mileage', 'н/д');

                                                          applicationAdd('average-speed', 'н/д');

                                                          applicationAdd('fuel-norm', 'н/д');

                                                          applicationAdd('fuel-dut', 'н/д');

                                                          applicationAdd('numb-of-violations', 'н/д');

                                                          applicationAdd('quality-of-driving', 'н/д');


                                                      } else {

                                                          console.log(obj, obj['car_id']);

                                                          applicationAdd('driver-name', obj['driver']);

                                                          applicationAdd('driver-phone', obj['phone']);

                                                          applicationAdd('plan-start', obj['start_time_plan']);

                                                          applicationAdd('fact-start', obj['start_time_fact']);

                                                          applicationAdd('fact-end', obj['end_time_plan']);

                                                          applicationAdd('fuel-time-plan', obj['work_time_plan']);

                                                          applicationAdd('fuel-time-work', obj['work_time_fact']);

                                                          applicationAdd('mileage', obj['mileage']);

                                                          applicationAdd('average-speed', obj['speed']);

                                                          applicationAdd('fuel-norm', obj['fuel_norm']);

                                                          applicationAdd('fuel-dut', obj['fuel_DUT']);

                                                          applicationAdd('numb-of-violations', obj['violations_count']);

                                                          applicationAdd('quality-of-driving', obj['driver_mark']);
                                                      }

                                                      $('.loading-layout').css({'display':'none'});
                                                  },
                                                  error: function(err) {
                                                      $('.loading-layout').css({'display':'none'});
                                                      console.log('---err',err);
                                                      applicationAdd('driver-name', 'н/д');

                                                      applicationAdd('driver-phone', 'н/д');

                                                      applicationAdd('plan-start', 'н/д');

                                                      applicationAdd('fact-start', 'н/д');

                                                      applicationAdd('fact-end', 'н/д');

                                                      applicationAdd('fuel-time-plan', 'н/д');

                                                      applicationAdd('fuel-time-work', 'н/д');

                                                      applicationAdd('mileage', 'н/д');

                                                      applicationAdd('average-speed', 'н/д');

                                                      applicationAdd('fuel-norm', 'н/д');

                                                      applicationAdd('fuel-dut', 'н/д');

                                                      applicationAdd('numb-of-violations', 'н/д');

                                                      applicationAdd('quality-of-driving', 'н/д');
                                                  }

                                              })
                                          });

                                          c_array.push(c_pm);
                                          clustererCars.add(c_array);
                                          myMap.geoObjects.add(clustererCars);
                                          if (data.cars.length === 1) {
                                              s.originalEvent.target.center = c_pm.geometry._coordinates;
                                              myMap.setCenter(s.originalEvent.target.center, 6);
                                          } else {
                                              s.originalEvent.target.bounds = data.bounds;
                                              myMap.setBounds(data.bounds, {checkZoomRange: false}).then(function() {
                                                  myMap.setZoom(myMap.getZoom() - 1, {duration: 1000})
                                              });
                                          }

                                        });
                                        $('.loading-layout').css({'display':'none'});
                                    }
                                });
                                window.currentElement.spot = s.originalEvent.target;
                                $('.bbb > span').html(window.currentElement.organization.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.autocolumn.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.spot.breadcrumps);
                                console.log(window.currentElement);
                            }); //spots click
                <?php   }
                    } ?>
                        if(s_array.length) {
                            clustererSpots.removeAll();
                            clustererSpots.add(s_array, myMap);
                            //myMap.geoObjects.add(clustererSpots);
                            addArrayOnMap(s_array, myMap);
                            if(s_array.length === 1) {
                                a.originalEvent.target.center = s_array[0].geometry._coordinates;
                                myMap.setCenter(a.originalEvent.target.center, 12);
                            } else {
                                a.originalEvent.target.bounds = <?= $spots[$autocolumnPrettyId]['bounds'] ?>;
                                myMap.setBounds(a.originalEvent.target.bounds, {checkZoomRange: true}).then(function() {
                                    myMap.setZoom(myMap.getZoom() - 1, {duration: 1000})
                                });
                            }
                        }
                        delete window.currentElement.spot;
            <?php } // if (array_key_exists($autocolumnPrettyId, $spots)) ?>
                    window.currentElement.autocolumn = a.originalEvent.target;
                    $('.bbb > span').html(window.currentElement.organization.breadcrumps + '<span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.autocolumn.breadcrumps);
                    console.log(window.currentElement);
                }); //Autocolumn click


          <?php } // if ($key !== bounds)
            } // foreach($autocolumns) ?>
                if (a_array.length) {

                    clustererAutocolumns.removeAll();
                    clustererAutocolumns.add(a_array);
                    //myMap.geoObjects.add(clustererAutocolumns);
                    addArrayOnMap(a_array, myMap);
                    <?php if(!empty($autocolumns[$organizationPrettyId]['bounds'])) { ?>
                        if(a_array.length === 1) {
                            o.originalEvent.target.center = a_array[0].geometry._coordinates;
                            myMap.setCenter(o.originalEvent.target.center, 6)
                        } else {
                            o.originalEvent.target.bounds = <?= $autocolumns[$organizationPrettyId]['bounds'] ?>;
                            myMap.setBounds(o.originalEvent.target.bounds, {checkZoomRange: true}).then(function() {
                                myMap.setZoom(myMap.getZoom() - 1, {duration: 1000});
                            });
                        }
                    <?php }?>
                }
                delete window.currentElement.spot;
                delete window.currentElement.autocolumn;
                window.currentElement.organization = o.originalEvent.target;
                $('.bbb > span').html(window.currentElement.organization.breadcrumps);
                console.log(window.currentElement);
            }); // Organization click
        <?php } ?>
        addArrayOnMap(o_array, myMap);
        myMap.setBounds(<?= \app\models\Organization::getMaxAndMinCoordinatesForAPI() ?>, {checkZoomRange: true});
        myMap.controls.add('zoomControl');

        let button = $('.back');
        button.click( function () {
            if(window.currentElement.hasOwnProperty('car')) {
                if(c_array.length) {
                    c_array = [];
                    myMap.geoObjects.remove(clustererCars);
                    clustererCars.removeAll();
                }
                $('.bbb > span').html(window.currentElement.organization.breadcrumps + '<span class="arrow-r" style="color: green"> > </span>' + window.currentElement.autocolumn.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.spot.breadcrumps);
                $('#info-company').addClass('hide');
                $('#ts-info').addClass('hide');
                $('#info-department').removeClass('hide');
                delete window.currentElement.car;
                myMap.setCenter(window.currentElement.spot.geometry._coordinates, 12);
                myMap.geoObjects.add(window.currentElement.spot);
                console.log(window.currentElement.spot);

                //AJAX -> SPOT
                $.ajax({
                    url: "index.php?r=site/get-spot-statistic&spot_id=" + idOfCurrentElement['spot'],
                    type: 'get',
                    success: function(res) {
                        let terminals = JSON.parse(res)['terminals'];
                        let data = JSON.parse(res)['statistic'];

                        //executed_app
                        applicationAdd('applications_executed',data['applications_executed']);

                        //canceled_app
                        applicationAdd('applications_canceled',data['applications_canceled']);

                        //sub_app
                        applicationAdd('applications_sub',data['applications_sub']);

                        //ac_app
                        circleBar('applications_ac', (data['applications_ac']/data['applications_total']).toFixed(2));

                        //waybills = waybills_processed / waybills_total
                        circleBar('waybills_total', (data['waybills_processed']/data['waybills_total']).toFixed(2));

                        //accidents = accidents_guilty / accidents_total
                        circleBar('accidents_total', (data['accidents_guilty']/data['accidents_total']).toFixed(2));

                        //GetWBMonitoring =  CountM/CountAll
                        circleBar('WB_M', (Math.round(data['WB_M'])/data['WB_ALL']).toFixed(2));

                        // TMCH = fuel/time
                        applicationAdd('fuel', (Math.round(data['fuel'])/data['time']).toFixed(2));

                        //terminals =
                        applicationAdd('terminals', terminals);



                        //totalCars: <?//= $spot->carsNumber ?>//,
                        //oinLine:  <?//= $spot->carsStatuses["inline"] ?>//,
                        //onRep: <?//= $spot->carsStatuses["R"] ?>//,
                        //onTO: <?//= $spot->carsStatuses["TO"] ?>//,
                        //light: <?//= $spot->carsTypes[\app\models\Car::LIGHT] ?>//,
                        //truck: <?//= $spot->carsTypes[\app\models\Car::TRUCK] ?>//,
                        //bus: <?//= $spot->carsTypes[\app\models\Car::BUS] ?>//,
                        //spec: <?//= $spot->carsTypes[\app\models\Car::SPEC] ?>//,
                        //applications_executed: data['applications_executed'],
                        //applications_canceled: data['applications_canceled'],
                        //applications_sub: data['applications_sub'],
                        //applications_ac: applications_ac,
                        //waybills_total: waybills_total,
                        //accidents_total: accidents_total,
                        //WB_M: WB_M

                        changeInfo(
                            dataLevel['spot']['totalCars'],
                            dataLevel['spot']['onLine'],
                            dataLevel['spot']['onRep'],
                            dataLevel['spot']['onTO'],
                            dataLevel['spot']['light'],
                            dataLevel['spot']['truck'],
                            dataLevel['spot']['bus'],
                            dataLevel['spot']['spec'],
                        );

                    }
                }); //end ajax request
                return true;

            }
            if(window.currentElement.hasOwnProperty('spot')) {
                myMap.geoObjects.remove(window.currentElement.spot);
                if(c_array.length) {
                   removeArrayFromMap(c_array, myMap);
                    c_array = [];
                    myMap.geoObjects.remove(clustererCars);
                    clustererCars.removeAll();
                }
                //myMap.geoObjects.add(clustererSpots);
                addArrayOnMap(s_array, myMap);
                $('.bbb > span').html(window.currentElement.organization.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.autocolumn.breadcrumps);
                $('#info-company').addClass('hide');
                $('#ts-info').addClass('hide');
                $('#info-department').removeClass('hide');
                delete window.currentElement.spot;
                if(window.currentElement.autocolumn.hasOwnProperty('bounds')) {
                   myMap.setBounds(window.currentElement.autocolumn.bounds, {checkZoomRange: true}).then(function() {
                       myMap.setZoom(myMap.getZoom() - 1, {duration: 1000})
                   });
                }
                if(window.currentElement.autocolumn.hasOwnProperty('center')) {
                   myMap.setCenter(window.currentElement.autocolumn.center, 12);
                }
                console.log(window.currentElement);

                $.ajax({
                    url: "index.php?r=site/get-autocolumn-statistic&autocolumn_id=" + idOfCurrentElement['autocolumn'],
                    type: 'get',
                    success: function(res) {
                        let terminals = JSON.parse(res)['terminals'];
                        let data = JSON.parse(res)['statistic'];

                        //example of data = {
                        // "id":null,
                        // "spot_id":null,
                        // "autocolumn_id":"68280a1c-2acf-11e5-b13d-00155dc6002b",
                        // "applications_total":76,
                        // "applications_executed":67,
                        // "applications_canceled":3,
                        // "applications_sub":0,
                        // "applications_ac":0,
                        // "applications_mp":0,
                        // "waybills_total":433,
                        // "waybills_processed":427,
                        // "accidents_total":0,
                        // "accidents_guilty":0,
                        // "time":9681,
                        // "fuel":2340.9700000000003
                        // }

                        //executed_app
                        applicationAdd('applications_executed',data['applications_executed']);

                        //canceled_app
                        applicationAdd('applications_canceled',data['applications_canceled']);

                        //sub_app
                        applicationAdd('applications_sub',data['applications_sub']);

                        //ac_app
                        circleBar('applications_ac', (data['applications_ac']/data['applications_total']).toFixed(2));

                        //waybills = waybills_processed / waybills_total
                        circleBar('waybills_total', (data['waybills_processed']/data['waybills_total']).toFixed(2));

                        //accidents = accidents_guilty / accidents_total
                        circleBar('accidents_total', (data['accidents_guilty']/data['accidents_total']).toFixed(2));

                        //GetWBMonitoring =  CountM/CountAll
                        circleBar('WB_M', (Math.round(data['WB_M'])/data['WB_ALL']).toFixed(2));

                        // TMCH = fuel/time
                        applicationAdd('fuel', (Math.round(data['fuel'])/data['time']).toFixed(2));

                        //terminals =
                        applicationAdd('terminals', terminals);
                    }
                }); //end ajax request

                //totalCars: <?//= $spot->carsNumber ?>//,
                //oinLine:  <?//= $spot->carsStatuses["inline"] ?>//,
                //onRep: <?//= $spot->carsStatuses["R"] ?>//,
                //onTO: <?//= $spot->carsStatuses["TO"] ?>//,
                //light: <?//= $spot->carsTypes[\app\models\Car::LIGHT] ?>//,
                //truck: <?//= $spot->carsTypes[\app\models\Car::TRUCK] ?>//,
                //bus: <?//= $spot->carsTypes[\app\models\Car::BUS] ?>//,
                //spec: <?//= $spot->carsTypes[\app\models\Car::SPEC] ?>//,
                //applications_executed: data['applications_executed'],
                //applications_canceled: data['applications_canceled'],
                //applications_sub: data['applications_sub'],
                //applications_ac: applications_ac,
                //waybills_total: waybills_total,
                //accidents_total: accidents_total,
                //WB_M: WB_M

                changeInfo(
                    dataLevel['autocolumn']['totalCars'],
                    dataLevel['autocolumn']['onLine'],
                    dataLevel['autocolumn']['onRep'],
                    dataLevel['autocolumn']['onTO'],
                    dataLevel['autocolumn']['light'],
                    dataLevel['autocolumn']['truck'],
                    dataLevel['autocolumn']['bus'],
                    dataLevel['autocolumn']['spec'],
                );

                return true;
            }
            if(window.currentElement.hasOwnProperty('autocolumn')) {
                if(s_array) {
                    //myMap.geoObjects.remove(clustererSpots);
                    removeArrayFromMap(s_array, myMap);
                    s_array = [];
                }

                $('.bbb > span').html(window.currentElement.organization.breadcrumps);
                $('#info-company').addClass('hide');
                $('#ts-info').addClass('hide');
                $('#info-department').removeClass('hide');
                delete window.currentElement.autocolumn;
                if(window.currentElement.organization.hasOwnProperty('bounds')) {
                    myMap.setBounds(window.currentElement.organization.bounds, {checkZoomRange: true}).then(function() {
                        myMap.setZoom(myMap.getZoom() - 1, {duration: 1000})
                    });
                }
                if(window.currentElement.organization.hasOwnProperty('center')) {
                    myMap.setCenter(window.currentElement.organization.center, 12);
                }
                console.log(window.currentElement);
                if(a_array) {
                   //myMap.geoObjects.add(clustererAutocolumns);
                    addArrayOnMap(a_array, myMap);
                }

                $.ajax({
                    url: "index.php?r=site/get-organization-statistic&organization_id=" + idOfCurrentElement['organizations'],
                    type: 'get',
                    success: function(res) {
                        let terminals = JSON.parse(res)['terminals'];
                        let data = JSON.parse(res)['statistic'];

                        //example of data = {
                        //{"id":null,
                        // "spot_id":null,
                        // "autocolumn_id":null,
                        // "applications_total":289,
                        // "applications_executed":260,
                        // "applications_canceled":11,
                        // "applications_sub":3,
                        // "applications_ac":0,
                        // "applications_mp":0,
                        // "waybills_total":3769,
                        // "waybills_processed":3438,
                        // "accidents_total":0,
                        // "accidents_guilty":0,
                        // "time":91025.12,
                        // "fuel":25134.369999999995,
                        // "WB_M":1988,"WB_ALL":2013}

                        //executed_app
                        applicationAdd('applications_executed',data['applications_executed']);

                        //canceled_app
                        applicationAdd('applications_canceled',data['applications_canceled']);

                        //sub_app
                        applicationAdd('applications_sub',data['applications_sub']);

                        //ac_app
                        circleBar('applications_ac', (data['applications_ac']/data['applications_total']).toFixed(2));

                        //waybills = waybills_processed / waybills_total
                        circleBar('waybills_total', (data['waybills_processed']/data['waybills_total']).toFixed(2));

                        //accidents = accidents_guilty / accidents_total
                        circleBar('accidents_total', (data['accidents_guilty']/data['accidents_total']).toFixed(2));

                        //GetWBMonitoring =  CountM/CountAll
                        circleBar('WB_M', (Math.round(data['WB_M'])/data['WB_ALL']).toFixed(2));

                        // TMCH = fuel/time
                        applicationAdd('fuel', (Math.round(data['fuel'])/data['time']).toFixed(2));

                        //terminals =
                        applicationAdd('terminals', terminals);
                    }
                }); //end ajax request

                //totalCars: <?//= $spot->carsNumber ?>//,
                //oinLine:  <?//= $spot->carsStatuses["inline"] ?>//,
                //onRep: <?//= $spot->carsStatuses["R"] ?>//,
                //onTO: <?//= $spot->carsStatuses["TO"] ?>//,
                //light: <?//= $spot->carsTypes[\app\models\Car::LIGHT] ?>//,
                //truck: <?//= $spot->carsTypes[\app\models\Car::TRUCK] ?>//,
                //bus: <?//= $spot->carsTypes[\app\models\Car::BUS] ?>//,
                //spec: <?//= $spot->carsTypes[\app\models\Car::SPEC] ?>//,
                //applications_executed: data['applications_executed'],
                //applications_canceled: data['applications_canceled'],
                //applications_sub: data['applications_sub'],
                //applications_ac: applications_ac,
                //waybills_total: waybills_total,
                //accidents_total: accidents_total,
                //WB_M: WB_M

                changeInfo(
                    dataLevel['organization']['totalCars'],
                    dataLevel['organization']['onLine'],
                    dataLevel['organization']['onRep'],
                    dataLevel['organization']['onTO'],
                    dataLevel['organization']['light'],
                    dataLevel['organization']['truck'],
                    dataLevel['organization']['bus'],
                    dataLevel['organization']['spec'],
                );

                return true;
            }
            if(a_array) {
                //myMap.geoObjects.remove(clustererAutocolumns);
                removeArrayFromMap(a_array, myMap);
                a_array = [];
            }
            if(o_array) {
                addArrayOnMap(o_array, myMap);
            }
            delete window.currentElement.organization;
            myMap.setBounds(<?= \app\models\Organization::getMaxAndMinCoordinatesForAPI() ?>, {checkZoomRange: true});
            console.log(window.currentElement);

            $('#info-company').removeClass('hide');
            $('#ts-info').addClass('hide');
            $('#info-department').addClass('hide');
            $('.bbb > span').html('ООО РесурсТранс');
        });
    });
</script>