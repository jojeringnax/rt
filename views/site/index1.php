

<?php
/**
 * @var $organizations \app\models\Organization[]
 * @var $autocolumns \app\models\Autocolumn[]
 * @var $spots \app\models\Spot[]
 */
?>
<?= $this->render('sidebar') ?>

<?php $breadcrumps = []; ?>

<style>
    [class*="ymaps-2"][class*="-ground-pane"] {
        filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
        -webkit-filter: grayscale(100%) brightness(30%);
    }
</style>
<script>
    let firmsData = <?= json_encode($totalStats->getAttributes()) ?>;
    let totalTerminals =<?= json_encode($totalTerminals) ?>;
    let totalCarsData =<?= json_encode($totalCarsData) ?>;
    console.log(firmsData, totalTerminals,totalCarsData);
    let changeInfo = function(totTs, onLine, onRep, onTO, passCar, freightCar, busCar, specCar) {
        $('#totTs').html(totTs);
        $('#compOnLine').html(onLine);
        $('#OnRep').html(onRep);
        $('#onTo').html(onTO);
        $('#passCar').html(passCar);
        $('#freightCar').html(freightCar);
        $('#busCar').html(busCar);
        $('#specCar').html(specCar);
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
                myMap.geoObjects.remove(clustererAutocolumns);
                if (level === 0) {
                    a_array = [];
                }
            }
            if (s_array.length) {
                myMap.geoObjects.remove(clustererSpots);
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
        var clustererAutocolumns = new ymaps.Clusterer(), clustererSpots = new ymaps.Clusterer(), clustererCars = new ymaps.Clusterer();
        var myMap = new ymaps.Map('map', {
            center: [55.751574, 37.573856],
            zoom: 11,
            behaviors: ['default', 'scrollZoom'],
            controls: []
        }, {
            searchControlProvider: 'yandex#search',
            suppressMapOpenBlock: true
        });
        var clustererOrg = new ymaps.Clusterer({});

        <?php foreach ($organizations as $organization) {
            $organizationPrettyId = $organization->getIdWithoutNumbers();
            ?>

        var OrgLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="organizations" style="color: black; font-weight: bold; display: flex; justify-content: space-between; flex-direction: column; align-items: center; height: 50px; width: 250px;"><span style="color:white; margin-top: -50px"><?= $autocolumns[$organizationPrettyId]["cars"] ?></span> <span style="width: 250px; margin-top: 50px"></span></div>'
        );
            o_pm = new ymaps.Placemark([<?= $organization->x_pos ?>, <?= $organization->y_pos ?>], {
                iconCaption : '<?= $organization->getTown() ?>',
                hintContent: '<?= $organization->getTown() ?>'
            }, {
                iconLayout: 'default#imageWithContent',
                iconImageHref: 'yan/img/icon/filial.svg',
                iconImageSize: [102, 107.5],
                iconContentOffset: [-74, 75],
                iconImageOffset: [-24, -24],
                preset: 'islands#greenDotIconWithCaption',
                iconContentLayout: OrgLayout
            });

            o_array.push(o_pm);
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
                $.ajax({
                    url: "http://rt.xxx/web/?r=site/get-organization-statistic&organization_id=" + "<?= $organization->id ?>",
                    type: 'get',
                    success: function(res) {
                        console.log('---org', res);
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

            <?php
                foreach ($autocolumns[$organizationPrettyId] as $key1 => $autocolumn) {
                    if ($key1 !== 'bounds' && $key1 !== 'cars' && $key1 !== 'carsStatuses' && $key1 !== 'carsTypes') {
                        $autocolumnPrettyId = $autocolumn->getIdWithoutNumbers(); ?>
                var AutoColLayout = ymaps.templateLayoutFactory.createClass(
                    '<div class="autocolumn" style="color: black; font-weight: bold; display: flex; justify-content: space-between; flex-direction: column; align-items: center;  height: 50px; width: 250px;"><span style="color:white; margin-top: -50px"><?= $spots[$autocolumnPrettyId]["cars"] ?></span> <span style="width: 200px; display:none; margin-top: 50px"><?= $autocolumn->description ?></span></div>'
                );
                a_pm = new ymaps.Placemark([<?= $autocolumn->x_pos ?>, <?= $autocolumn->y_pos ?>], {
                    hintContent: '<?= $autocolumn->description ?>'
                }, {
                    iconLayout: 'default#imageWithContent',
                    iconImageHref: 'yan/img/icon/autocol.svg',
                    iconImageSize: [110, 115.5],
                    iconContentOffset: [-70, 75],
                    iconImageOffset: [-24, -24],
                    preset: 'islands#greenDotIconWithCaption',
                    iconContentLayout: AutoColLayout
                });
                a_pm.breadcrumps = '<?= $autocolumn->description ?>';
                a_array.push(a_pm);
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
                    $.ajax({
                        url: "http://rt.xxx/web/?r=site/get-autocolumn-statistic&autocolumn_id=" + "<?= $autocolumn->id ?>",
                        type: 'get',
                        success: function(res) {
                            console.log('---spot', res);
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

            <?php
                if (array_key_exists($autocolumnPrettyId, $spots)) {
                    foreach ($spots[$autocolumnPrettyId] as $key2 => $spot) {
                        if ($key2 !== 'bounds' && $key2 !== 'cars' && $key2 !== 'carsStatuses' && $key2 !== 'carsTypes') {
                            $spotPrettyId = $spot->getIdWithoutNumbers(); ?>
                            var SpotsLayout = ymaps.templateLayoutFactory.createClass(
                                '<div class="spots" style="color: black; font-weight: bold; display: flex; justify-content: space-between; flex-direction: column; align-items: center; height: 50px; width: 250px;"><span style="color:white;  margin-top: -42px"><?= $spot->carsNumber ?></span> <span style="width: 200px; display: none;"><?= $spot->description ?></span></div>'
                            );
                            s_pm = new ymaps.Placemark([<?= $spot->x_pos ?>, <?= $spot->y_pos ?>],{
                                hintContent: '<?= $spot->description ?>'
                            }, {
                                iconLayout: 'default#imageWithContent',
                                iconImageHref: 'yan/img/icon/spots_main.svg',
                                iconImageSize: [115, 115],
                                iconContentOffset: [-68, 75],
                                iconImageOffset: [-24, -24],
                                preset: 'islands#greenDotIconWithCaption',
                                iconContentLayout: SpotsLayout
                            });
                            s_array.push(s_pm);
                            s_pm.breadcrumps = '<?= $spot->description ?>';
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
                                $.ajax({
                                    url: "http://rt.xxx/web/?r=site/get-spot-statistic&spot_id=" + "<?= $spot->id ?>",
                                    type: 'get',
                                    success: function(res) {
                                        console.log('---spot', res);
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
                                        myMap.geoObjects.remove(clustererSpots);
                                        data.cars.forEach(function(el) {
                                          c_pm = new ymaps.Placemark([el.x_pos, el.y_pos], {
                                              hintContent: el.description,
                                              balloonContent: el.description,
                                              balloonContentHeader: el.model,
                                              balloonContentBody: el.number,
                                              balloonContentFooter: el.status
                                          },{
                                              hasBalloon: false,
                                              iconLayout: 'default#imageWithContent',
                                                  iconImageHref: 'yan/img/icon/point_'+ el.type+'.svg',
                                                  iconImageSize: [110, 115.5],
                                                  iconContentOffset: [-70, 75],
                                                  iconImageOffset: [-24, -24],
                                          });
                                          c_pm.breadcrumps = el.description;
                                          c_pm.events.add('click', function (c) {
                                              console.log('---',el);
                                              window.currentElement.car = c.originalEvent.target;
                                              $('#info-company').addClass('hide');
                                              $('#info-department').addClass('hide');
                                              $('#ts-info').removeClass('hide');
                                              myMap.setCenter(window.currentElement.car.geometry._coordinates, 19);
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

                                              applicationAdd('nameTS', el['model']);

                                              applicationAdd('oilChangeDist', el['tire_change_days']);

                                              applicationAdd('tireChangeDist', el['tire_change_days']);

                                              applicationAdd('accChangeDist', el['battery_change_days']);

                                              applicationAdd('toChangeDist', el['technical_inspection_days']);

                                              console.log('---', el);




                                          });
                                          c_array.push(c_pm);
                                          clustererCars.add(c_array);
                                          myMap.geoObjects.add(clustererCars);

                                          if (data.cars.length === 1) {
                                              s.originalEvent.target.center = c_pm.geometry._coordinates;
                                              myMap.setCenter(s.originalEvent.target.center, 6);
                                          } else {
                                              s.originalEvent.target.bounds = data.bounds;
                                              myMap.setBounds(data.bounds, {checkZoomRange: true});
                                          }

                                        });
                                        $('.loading-layout').css({'display':'none'});
                                    }
                                });
                                window.currentElement.spot = s.originalEvent.target;
                                $('.bbb > span').html(window.currentElement.organization.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.autocolumn.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.spot.breadcrumps);
                                console.log(window.currentElement);
                            }); //spots cllick
                <?php   }
                    } ?>
                        if(s_array.length) {
                            clustererSpots.removeAll();
                            clustererSpots.add(s_array);
                            myMap.geoObjects.add(clustererSpots);
                            if(s_array.length === 1) {
                                a.originalEvent.target.center = s_array[0].geometry._coordinates;
                                myMap.setCenter(a.originalEvent.target.center, 12);
                            } else {
                                a.originalEvent.target.bounds = <?= $spots[$autocolumnPrettyId]['bounds'] ?>;
                                myMap.setBounds(a.originalEvent.target.bounds, {checkZoomRange: true});
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
                    myMap.geoObjects.add(clustererAutocolumns);

                    <?php if(!empty($autocolumns[$organizationPrettyId]['bounds'])) { ?>
                        if(a_array.length === 1) {
                            o.originalEvent.target.center = a_array[0].geometry._coordinates;
                            myMap.setCenter(o.originalEvent.target.center, 6)
                        } else {
                            o.originalEvent.target.bounds = <?= $autocolumns[$organizationPrettyId]['bounds'] ?>;
                            myMap.setBounds(o.originalEvent.target.bounds, {checkZoomRange: true});
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

        var button = $('.back');
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
                console.log(window.currentElement);
                return true;
            }
            if(window.currentElement.hasOwnProperty('spot')) {
                myMap.geoObjects.remove(window.currentElement.spot);
                if(c_array.length) {
                   removeArrayFromMap(c_array, myMap);
                    c_array = [];
                }
                myMap.geoObjects.add(clustererSpots);
                $('.bbb > span').html(window.currentElement.organization.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.autocolumn.breadcrumps);
                $('#info-company').addClass('hide');
                $('#ts-info').addClass('hide');
                $('#info-department').removeClass('hide');
                delete window.currentElement.spot;
                if(window.currentElement.autocolumn.hasOwnProperty('bounds')) {
                   myMap.setBounds(window.currentElement.autocolumn.bounds, {checkZoomRange: true});
                }
                if(window.currentElement.autocolumn.hasOwnProperty('center')) {
                   myMap.setCenter(window.currentElement.autocolumn.center, 12);
                }
                console.log(window.currentElement);
                return true;
            }
            if(window.currentElement.hasOwnProperty('autocolumn')) {
                if(s_array) {
                    myMap.geoObjects.remove(clustererSpots);
                    s_array = [];
                }
                $('.bbb > span').html(window.currentElement.organization.breadcrumps);
                $('#info-company').addClass('hide');
                $('#ts-info').addClass('hide');
                $('#info-department').removeClass('hide');
                delete window.currentElement.autocolumn;
                if(window.currentElement.organization.hasOwnProperty('bounds')) {
                    myMap.setBounds(window.currentElement.organization.bounds, {checkZoomRange: true});
                }
                if(window.currentElement.organization.hasOwnProperty('center')) {
                    myMap.setCenter(window.currentElement.organization.center, 12);
                }
                console.log(window.currentElement);
                if(a_array) {
                    myMap.geoObjects.add(clustererAutocolumns);
                }
                return true;
            }
            if(a_array) {
                myMap.geoObjects.remove(clustererAutocolumns);
                a_array = [];
            }
            if(o_array) {
                addArrayOnMap(o_array, myMap);
            }
            delete window.currentElement.organization;
            myMap.setBounds(<?= \app\models\Organization::getMaxAndMinCoordinatesForAPI() ?>, {checkZoomRange: true});
            console.log(window.currentElement);

            $('.bbb > span').html('ООО РесурсТранс');
        });
    });
</script>