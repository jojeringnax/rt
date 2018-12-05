
<?= $this->render('sidebar') ?>

<script>
    ymaps.ready( function() {
        var sectionCompany = $('section#info-company'), sectionDepartment = $('section#info-department'),
            sectionTS = $('section#ts-info'),
            MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
            );
        var myMap = new ymaps.Map('map', {
            center: [55.751574, 37.573856],
            zoom: 11,
            behaviors: ['default', 'scrollZoom']
        }, {
            searchControlProvider: 'yandex#search'
        });
        var el = 0;
        var clustererSpots = new ymaps.Clusterer({});
        clustererSpots.createCluster = function (center, geoObjects) {
            var clusterSpot = ymaps.Clusterer.prototype.createCluster.call(this, center, geoObjects);
            clusterSpot.properties.set({
                data: [
                    { weight: 4, color: '#408022' },
                    { weight: 4, color: '#802240' }
                ],
                // Подпись к диаграмме.
                iconCaption: 'Диаграмма кластера',
            });/*
            clusterSpot.options.set('iconLayout', 'default#pieChart');
            clusterSpot.options.set('balloonContentLayout', 'cluster#balloonTwoColumns');
            clusterSpot.options.set('balloonPanelContentLayout', 'cluster#balloonTwoColumns');
            clusterSpot.options.set('disableClickZoom', true);
            clusterSpot.options.set('openBalloonOnClick', true);
            clusterSpot.options.set('balloonLeftColumnWidth', 60);
            clusterSpot.options.set('balloonPanelMaxMapArea', 0);
            clusterSpot.options.set('balloonContentLayoutHeight', 200);
            clusterSpot.options.set('balloonContentLayoutWidth', 300);
            clusterSpot.options.set('balloonItemContentLayout', 'cluster#balloonTwoColumnsItemContent');*/
            return clusterSpot;
        };
        <?php foreach ($spots as $spot) { ?>
        el = new ymaps.Placemark([<?= $spot->x_pos ?>, <?= $spot->y_pos ?>]);
        clustererSpots.add(el);
        <?php } ?>
        myMap.geoObjects.add(clustererSpots);

        var clustererOrg = new ymaps.Clusterer({});
        <?php foreach ($organizations as $orgaznization) { ?>
        el = new ymaps.Placemark([<?= $orgaznization->x_pos ?>, <?= $orgaznization->y_pos ?>]);
        clustererOrg.add(el);
        <?php } ?>
        myMap.geoObjects.add(clustererOrg);
    });
</script>