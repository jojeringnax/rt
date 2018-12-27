<?php
/**
 * @var $organizations \app\models\Organization[]
 * @var $autocolumns \app\models\Autocolumn[]
 * @var $spots \app\models\Spot[]
 */
?>
    <div class="row" style="position: relative; width: 100%">
        <div class="bbb" style="left:0; display: flex; justify-content: center; height: 50px; position: absolute; z-index: 100123123123; top: 0; width: 100%; margin-left:18%">
            <span class=""  style="display: block; font-size: 2.5vh; font-weight: bold">

            </span>
        </div>
    </div>
<?= $this->render('sidebar') ?>

<?php $breadcrumps = []; ?>
<script>
    ymaps.ready( function() {

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
            o_pm.events.add('click', function(e) {
                <?php $breadcrumps['filial'] = 'Филиал '.$organization->getTown(); ?>
                $('.bbb > span').html("<?= $breadcrumps['filial'] ?>");
                $('#info-company').addClass('hide');
                $('#ts-info').addClass('hide');
                $('#info-department').removeClass('hide');
                removeAllDontNeed();
            <?php
                foreach ($autocolumns[$organizationPrettyId] as $key => $autocolumn) {
                    if ($key !== 'bounds' && $key !== 'cars') {
                        $autocolumnPrettyId = $autocolumn->getIdWithoutNumbers(); ?>
                var AutoColLayout = ymaps.templateLayoutFactory.createClass(
                    '<div class="autocolumn" style="color: black; font-weight: bold; display: flex; justify-content: space-between; flex-direction: column; align-items: center; height: 80px; width: 200px;"><span style="color:white"><?= $spots[$autocolumnPrettyId]["cars"] ?></span> <span style="width: 200px; display:none;"><?= $autocolumn->description ?></span></div>'
                );
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
                        a_array.push(a_pm);
                        a_pm.events.add('click', function(e) {
                            <?php $breadcrumps['autocolumn'] = $autocolumn->description ?>
                            $('.bbb > span').html('<?= $breadcrumps['filial'].' => '.$breadcrumps['autocolumn'] ?>');
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
                        if ($key !== 'bounds' && $key !== 'cars') {
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
                            s_pm.events.add('click', function() {
                                <?php $breadcrumps['spot'] = $spot->description ?>
                                $('.bbb > span').html('<?= $breadcrumps['filial'].' => '.$breadcrumps['autocolumn'].' => '.$breadcrumps['spot'] ?>');
                                $('#info-company').addClass('hide');
                                $('#info-department').addClass('hide');
                                $('#ts-info').removeClass('hide');

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
                                          c_array.push(c_pm);
                                          if (data.cars.length === 1) {
                                              myMap.setCenter(data.cars[0].geometry._coordinates, 6);
                                          } else {
                                              myMap.setBounds(data.bounds);
                                          }
                                      });
                                      addArrayOnMap(c_array, myMap);
                                  }
                                });
                                window.currentElement = {
                                    level: 'spot',
                                    id: '<?= $spotPrettyId ?>'
                                };
                            });
                <?php   }
                    } ?>
                        if(s_array.length) {
                            addArrayOnMap(s_array, myMap);
                            if(s_array.length === 1) {
                                myMap.setCenter(s_array[0].geometry._coordinates, 6);
                            } else {
                                myMap.setBounds(<?= $spots[$autocolumnPrettyId]['bounds'] ?>);
                            }
                        }
                        window.currentElement = {
                            level: 'autocolumn',
                            id: '<?= $autocolumnPrettyId ?>'
                        };

            <?php } // if (array_key_exists($autocolumnPrettyId, $spots)) ?>

                        }); //Autocolumn click

          <?php } // if ($key !== bounds)
            } // foreach($autocolumns) ?>
                if (a_array.length) {
                    addArrayOnMap(a_array, myMap);
                    myMap.setBounds(<?= $autocolumns[$organizationPrettyId]['bounds'] ?>);
                }
                window.currentElement = {
                    level: 'organization',
                    id: '<?= $organizationPrettyId ?>'
                };
            }); // Organization click
        <?php } ?>
        addArrayOnMap(o_array, myMap);
        myMap.setBounds(<?= \app\models\Organization::getMaxAndMinCoordinatesForAPI() ?>);
        myMap.controls.add('zoomControl');
    });
</script>