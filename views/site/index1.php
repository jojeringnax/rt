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


        var s_array = [], a_array = [], o_array = [];
        var myMap = new ymaps.Map('map', {
            center: [55.751574, 37.573856],
            zoom: 11,
            behaviors: ['default', 'scrollZoom']
        }, {
            searchControlProvider: 'yandex#search'
        });
        var clustererOrg = new ymaps.Clusterer({});
        <?php foreach ($organizations as $organization) {
            $organizationPrettyId = $organization->getIdWithoutNumbers();
            ?>

            o_pm = new ymaps.Placemark([<?= $organization->x_pos ?>, <?= $organization->y_pos ?>], {
                iconCaption : '<?= $organization->getTown() ?>'
            }, {
                preset: 'islands#greenDotIconWithCaption'
            });
            o_pm.events.add('click', function(e) {
                if (a_array) {
                    removeArrayFromMap(a_array, myMap);
                    a_array = [];
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
            clustererOrg.add(o_pm);
        <?php } ?>
        myMap.geoObjects.add(clustererOrg);
        myMap.setBounds(<?= \app\models\Organization::getMaxAndMinCoordinatesForAPI() ?>);
    });
</script>