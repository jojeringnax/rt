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


        var removeAllDontNeed = function() {
            if (o_array.length) {
                removeArrayFromMap(o_array, myMap);
            }
            if (a_array.length) {
                removeArrayFromMap(a_array, myMap);
                a_array = [];
            }
            if (s_array.length) {
                removeArrayFromMap(s_array, myMap);
                s_array = [];
            }
            if(c_array.length) {
                removeArrayFromMap(c_array, myMap);
                c_array = [];
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
            '<div class="organizations" style="color: black; font-weight: bold; display: flex; justify-content: space-between; flex-direction: column; align-items: center; height: 50px; width: 200px;"><span style="color:white"><?= $autocolumns[$organizationPrettyId]["cars"] ?></span> <span style="width: 200px"><?= $organization->getTown() ?></span></div>'
        );
            o_pm = new ymaps.Placemark([<?= $organization->x_pos ?>, <?= $organization->y_pos ?>], {
                iconCaption : '<?= $organization->getTown() ?>',
                hintContent: '<?= $organization->getTown() ?>'
            }, {
                iconLayout: 'default#imageWithContent',
                iconImageHref: 'yan/img/icon/filial.svg',
                iconImageSize: [42, 47.5],
                iconContentOffset: [-80, 15],
                iconImageOffset: [-24, -24],
                preset: 'islands#greenDotIconWithCaption',
                iconContentLayout: OrgLayout
            });

            o_array.push(o_pm);
            o_pm.breadcrumps = '<?= 'Филиал '.$organization->getTown() ?>';
            o_pm.events.add('click', function(o) {
                $('#info-company').addClass('hide');
                $('#ts-info').addClass('hide');
                $('#info-department').removeClass('hide');
                removeAllDontNeed();
            <?php
                foreach ($autocolumns[$organizationPrettyId] as $key => $autocolumn) {
                    if ($key !== 'bounds' && $key !== 'cars' && $key !== 'carsStatuses') {
                        $autocolumnPrettyId = $autocolumn->getIdWithoutNumbers(); ?>
                var AutoColLayout = ymaps.templateLayoutFactory.createClass(
                    '<div class="autocolumn" style="color: black; font-weight: bold; display: flex; justify-content: space-between; flex-direction: column; align-items: center; height: 80px; width: 200px;"><span style="color:white"><?= $spots[$autocolumnPrettyId]["cars"] ?></span> <span style="width: 200px; display:none;"><?= $autocolumn->description ?></span></div>'
                );
                console.log(<?= $spots[$autocolumnPrettyId]["carsStatuses"]['G'] ?>);
                a_pm = new ymaps.Placemark([<?= $autocolumn->x_pos ?>, <?= $autocolumn->y_pos ?>], {
                    hintContent: '<?= $autocolumn->description ?>'
                }, {
                    iconLayout: 'default#imageWithContent',
                    iconImageHref: 'yan/img/icon/autocol.svg',
                    iconImageSize: [50, 55.5],
                    iconContentOffset: [-75, 18],
                    iconImageOffset: [-24, -24],
                    preset: 'islands#greenDotIconWithCaption',
                    iconContentLayout: AutoColLayout
                });
                a_pm.breadcrumps = '<?= $autocolumn->description ?>';
                a_array.push(a_pm);
                a_pm.events.add('click', function(a) {
                    $('#info-company').addClass('hide');
                    $('#ts-info').addClass('hide');
                    $('#info-department').removeClass('hide');
                    if (s_array) {
                        removeArrayFromMap(s_array, myMap);
                        s_array = [];
                    }
                    if(c_array.length) {
                        removeArrayFromMap(c_array, myMap);
                        c_array = [];
                    }
            <?php
                if (array_key_exists($autocolumnPrettyId, $spots)) {
                    foreach ($spots[$autocolumnPrettyId] as $key => $spot) {
                        if ($key !== 'bounds' && $key !== 'cars' && $key !== 'carsStatuses') {
                            $spotPrettyId = $spot->getIdWithoutNumbers(); ?>
                            var SpotsLayout = ymaps.templateLayoutFactory.createClass(
                                '<div class="spots" style="color: black; font-weight: bold; display: flex; justify-content: space-between; flex-direction: column; align-items: center; height: 100px; width: 200px;"><span style="color:white"><?= $spot->carsNumber ?></span> <span style="width: 200px; display: none;"><?= $spot->description ?></span></div>'
                            );
                            s_pm = new ymaps.Placemark([<?= $spot->x_pos ?>, <?= $spot->y_pos ?>],{
                                hintContent: '<?= $spot->description ?>'
                            }, {
                                iconLayout: 'default#imageWithContent',
                                iconImageHref: 'yan/img/icon/spots_main.svg',
                                iconImageSize: [47, 52.5],
                                iconContentOffset: [-77.5, 18],
                                iconImageOffset: [-20, -22],
                                preset: 'islands#greenDotIconWithCaption',
                                iconContentLayout: SpotsLayout
                            });
                            s_array.push(s_pm);
                            s_pm.breadcrumps = '<?= $spot->description ?>';
                            s_pm.events.add('click', function(s) {
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
                                                  iconImageSize: [42, 47.5],
                                                  iconContentOffset: [20, 13],
                                                  iconImageOffset: [-24, -24]
                                          });
                                          c_pm.breadcrumps = el.description;
                                          c_pm.events.add('click', function (c) {
                                              window.currentElement.car = c.originalEvent.target;
                                              $('#info-company').addClass('hide');
                                              $('#info-department').addClass('hide');
                                              $('#ts-info').removeClass('hide');
                                              myMap.setCenter(window.currentElement.car.geometry._coordinates, 12);
                                              $('.bbb > span').html(window.currentElement.organization.breadcrumps + ' => ' + window.currentElement.autocolumn.breadcrumps + ' => ' + window.currentElement.spot.breadcrumps + ' => ' + window.currentElement.car.breadcrumps);
                                              console.log(window.currentElement);
                                          });
                                          c_array.push(c_pm);
                                          if (data.cars.length === 1) {
                                              s.originalEvent.target.center = data.cars[0].geometry._coordinates;
                                              myMap.setCenter(data.cars[0].geometry._coordinates, 6);
                                          } else {
                                              s.originalEvent.target.bounds = data.bounds;
                                              myMap.setBounds(data.bounds, {checkZoomRange: true});
                                          }

                                        });
                                        addArrayOnMap(c_array, myMap);
                                    }
                                });
                                window.currentElement.spot = s.originalEvent.target;
                                $('.bbb > span').html(window.currentElement.organization.breadcrumps + ' => ' + window.currentElement.autocolumn.breadcrumps + ' => ' + window.currentElement.spot.breadcrumps);
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
                    $('.bbb > span').html(window.currentElement.organization.breadcrumps + ' => ' + window.currentElement.autocolumn.breadcrumps);
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

        var button = new ymaps.control.Button("Кнопка");
        button.events.add('click', function () {
            if(window.currentElement.hasOwnProperty('car')) {
                if(c_array.length) {
                    removeArrayFromMap(c_array, myMap);
                    c_array = [];
                }
                $('.bbb > span').html(window.currentElement.organization.breadcrumps + ' => ' + window.currentElement.autocolumn.breadcrumps + ' => ' + window.currentElement.spot.breadcrumps);
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
                $('.bbb > span').html(window.currentElement.organization.breadcrumps + ' => ' + window.currentElement.autocolumn.breadcrumps);
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
        myMap.controls.add(button, {float: 'right'});
    });
</script>