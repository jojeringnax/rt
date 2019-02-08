<?php
/**
 * @var $organizations \app\models\Organization[]
 * @var $autocolumns \app\models\Autocolumn[]
 * @var $spots \app\models\Spot[]
 */
?>
<?= $this->render('sidebar') ?>

<?php $breadcrumps = []; ?>
<script>
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
                removeArrayFromMap(a_array, myMap);
                if (level === 0) {
                    a_array = [];
                }
            }
            if (s_array.length) {
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
            '<div class="organizations" style="color: black; font-weight: bold; display: flex; justify-content: space-between; flex-direction: column; align-items: center; height: 50px; width: 250px;"><span style="color:white; margin-top: -50px"><?= $autocolumns[$organizationPrettyId]["cars"] ?></span> <span style="width: 250px; margin-top: 50px"><?= $organization->getTown() ?></span></div>'
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
            <?php
                foreach ($autocolumns[$organizationPrettyId] as $key1 => $autocolumn) {
                    if ($key1 !== 'bounds' && $key1 !== 'cars' && $key1 !== 'carsStatuses' && $key1 !== 'carsTypes') {
                        $autocolumnPrettyId = $autocolumn->getIdWithoutNumbers(); ?>
                console.log(<?= $spots[$autocolumnPrettyId]["carsTypes"][0] ?>);
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
                                $.ajax({
                                    url: 'index.php?r=site/carsforspot&id=<?= $spot->id ?>',
                                    method: 'GET',
                                    dataType: 'json',
                                    success: function(data) {
                                        if(c_array.length) {
                                          removeArrayFromMap(c_array, myMap);
                                          c_array = [];
                                        }
                                        data.cars.forEach(function(el) {
                                          c_pm = new ymaps.Placemark([el.x_pos, el.y_pos], {
                                              hintContent: el.description
                                          },{
                                              iconLayout: 'default#imageWithContent',
                                                  iconImageHref: 'yan/img/icon/point_'+ el.type+'.svg',
                                                  iconImageSize: [110, 115.5],
                                                  iconContentOffset: [-70, 75],
                                                  iconImageOffset: [-24, -24],
                                          });
                                          c_pm.breadcrumps = el.description;
                                          c_pm.events.add('click', function (c) {
                                              window.currentElement.car = c.originalEvent.target;
                                              $('#info-company').addClass('hide');
                                              $('#info-department').addClass('hide');
                                              $('#ts-info').removeClass('hide');
                                              myMap.setCenter(window.currentElement.car.geometry._coordinates, 12);
                                              $('.bbb > span').html(window.currentElement.organization.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.autocolumn.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.spot.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.car.breadcrumps);
                                              console.log(window.currentElement);
                                          });
                                          c_array.push(c_pm);
                                          if (data.cars.length === 1) {
                                              s.originalEvent.target.center = c_pm.geometry._coordinates;
                                              myMap.setCenter(s.originalEvent.target.center, 6);
                                          } else {
                                              s.originalEvent.target.bounds = data.bounds;
                                              myMap.setBounds(data.bounds, {checkZoomRange: true});
                                          }

                                        });
                                        addArrayOnMap(c_array, myMap);
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
                            addArrayOnMap(s_array, myMap);
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
                    addArrayOnMap(a_array, myMap);
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
                    removeArrayFromMap(c_array, myMap);
                    c_array = [];
                }
                $('.bbb > span').html(window.currentElement.organization.breadcrumps + '<span class="arrow-r" style="color: green"> > </span>' + window.currentElement.autocolumn.breadcrumps + ' <span class="arrow-r" style="color: green"> > </span> ' + window.currentElement.spot.breadcrumps);
                $('#info-company').addClass('hide');
                $('#ts-info').addClass('hide');
                $('#info-department').removeClass('hide');
                delete window.currentElement.car;
                myMap.setCenter(window.currentElement.spot.geometry._coordinates, 12);
                console.log(window.currentElement);
                return true;
            }
            if(window.currentElement.hasOwnProperty('spot')) {
                if(c_array.length) {
                   removeArrayFromMap(c_array, myMap);
                    c_array = [];
                }
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
                    removeArrayFromMap(s_array, myMap);
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
                    addArrayOnMap(a_array, myMap);
                }
                return true;
            }
            if(a_array) {
                removeArrayFromMap(a_array, myMap);
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