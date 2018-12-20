<?php
/**
 * @var $organizations \app\models\Organization[]
 * @var $autocolumns \app\models\Autocolumn[]
 * @var $spots \app\models\Spot[]
 */
?>
<?= $this->render('sidebar') ?>

<script>
    ymaps.ready( function() {

        let MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
            '<div style="color: #FFFFFF; font-weight: bold; display: flex; justify-content: around; flex-direction: column; align-items: center">$[properties.iconContent] <span>1120</span></div>'
        );

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

            o_pm = new ymaps.Placemark([<?= $organization->x_pos ?>, <?= $organization->y_pos ?>], {
                iconCaption : '<?= $organization->getTown() ?>',
                iconContent: 'Ф',
                hintContent: '<?= $organization->getTown() ?>'
            }, {
                iconLayout: 'default#imageWithContent',
                iconImageHref: 'yan/img/union.png',
                iconImageSize: [42, 47.5],
                iconContentOffset: [16, 8],
                iconImageOffset: [-24, -24],
                preset: 'islands#greenDotIconWithCaption',
                iconContentLayout: MyIconContentLayout
            });
            o_array.push(o_pm);
            o_pm.events.add('click', function(e) {
                removeArrayFromMap(o_array, myMap);
                o_array = [];
                if (a_array.length) {
                    removeArrayFromMap(a_array, myMap);
                    a_array = [];
                }
                if (s_array.length) {
                    removeArrayFromMap(s_array, myMap);
                    s_array = [];
                }
                <?php
                foreach ($autocolumns[$organizationPrettyId] as $key => $autocolumn) {
                    if ($key !== 'bounds') {
                        $autocolumnPrettyId = $autocolumn->getIdWithoutNumbers(); ?>

                        a_pm = new ymaps.Placemark([<?= $autocolumn->x_pos ?>, <?= $autocolumn->y_pos ?>], {
                            hintContent: '<?= $autocolumn->description ?>'
                        }, {
                            iconColor: '#990000'
                        });
                        a_array.push(a_pm);
                        a_pm.events.add('click', function(e) {
                            if (s_array) {
                                removeArrayFromMap(s_array, myMap);
                                s_array = [];
                            }

              <?php
                    if (array_key_exists($autocolumnPrettyId, $spots)) {
                        foreach ($spots[$autocolumnPrettyId] as $key => $spot) {
                            if ($key !== 'bounds') {
                                $spotPrettyId = $spot->getIdWithoutNumbers(); ?>

                                s_pm = new ymaps.Placemark([<?= $spot->x_pos ?>, <?= $spot->y_pos ?>],{
                                    hintContent: '<?= $spot->description ?>'
                                });
                                s_array.push(s_pm);
                                s_pm.events.add('click', function() {
                                  $.ajax({
                                      url: 'index.php?r=site/carsforspot&id=<?= $spot->id ?>',
                                      method: 'GET',
                                      dataType: 'json',
                                      success: function(data) {
                                          if(c_array.length) {
                                              removeArrayFromMap(c_array, myMap);
                                              c_array = [];
                                          }
                                          data.forEach(function(el) {
                                              c_pm = new ymaps.Placemark([el.x_pos, el.y_pos], {
                                                  hintContent: el.description
                                              });
                                              c_array.push(c_pm);
                                          });
                                          addArrayOnMap(c_array, myMap);
                                      }
                                  });
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
                console.log(s_array);
                console.log(a_array);
            }); // Organization click
            clustererOrg.add(o_pm);
        <?php } ?>
        myMap.geoObjects.add(clustererOrg);
        myMap.setBounds(<?= \app\models\Organization::getMaxAndMinCoordinatesForAPI() ?>);
        myMap.controls.add('zoomControl');
    });
</script>