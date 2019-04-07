<?php
/**
 * @var $organizations \app\models\Organization[]
 * @var $this \yii\web\View
 */
$this->registerCss('
.overlay_block{
  position: absolute;
  width: 100%;
  height: 100%;
  background: url("https://web-finder.ru/files/preloader1.gif") center center no-repeat;
  z-index: 9999;
}
.loading_process{
  -webkit-filter: blur(5px);
  -moz-filter: blur(5px);
  filter: blur(5px);
}
');
?>
<?= $this->render('sidebar') ?>
<script>

    function loading_animation_start(block){
        block.wrap( '<div class="main_overlay_block"></div>');
        const overlay_block = block.parent('.main_overlay_block').children('.overlay_block');
        $('.main_overlay_block').prepend('<div class="overlay_block"></div>');
        overlay_block.width(block.width());
        block.addClass('loading_process');
    }

    function loading_animation_end(block){
        const overlay_block = block.parent('.main_overlay_block').children('.overlay_block');
        block.unwrap();
        overlay_block.remove();
        block.removeClass('loading_process');
    }

    function start_stop_animation(block){
        if ($(block).parent().is('.main_overlay_block')){
            loading_animation_end(block);
        } else {
            loading_animation_start(block);
        }
    }

    ymaps.ready( function() {


        window.stop = false;

        const levels = {
            'company': 0,
            'organization': 1,
            'autocolumn': 2,
            'spot': 3,
            'car': 4
        };

        const levelsWithBadSpot = {
            'company': 0,
            'organization': 1,
            'spot': 2,
            'car': 3
        };

        const capitalize = function(s)
        {
            return s[0].toUpperCase() + s.slice(1);
        };

        const divine = $('input#divineInput');
        const breadcrumpsDiv = $('.nav-sidebar');
        const backButton = $('button.back');
        const divMap = $('div#map');
        const divSidebar = $('div.sidebar');


        let changeInfoForDepartment = function(obj) {
            $('#totTs').html(obj.totTs);
            $('#OnReady').html(obj.readyTs);
            $('#OnLine').html(obj.onLine);
            $('#OnRep').html(obj.onRep);
            $('#onTo').html(obj.onTO);
            $('#passCar').html(obj.passCar);
            $('#freightCar').html(obj.freightCar);
            $('#busCar').html(obj.busCar);
            $('#specCar').html(obj.specCar);
        };

        let changeInfoForCar = function(obj) {
            let keys = [
                "car_id",
                "driver",
                "phone",
                "start_time_plan",
                "end_time_plan",
                "work_time_plan",
                "start_time_fact",
                "work_time_fact",
                "mileage",
                "speed",
                "fuel_norm",
                "fuel_DUT",
                "driver_mark",
                "violations_count"
            ];
            keys.forEach(function(key) {
               if (!obj.hasOwnProperty(key) || obj[key] === null) {
                   obj[key] = 'н/д';
               }
               $('#' + key).html(obj[key]);
            });
        };

        let setBreadcrumps = function(badSpots = false) {
            let breadcrumpsArray = ['<a onclick="window.setLevelCompany()" id="company" href="#">ООО Ресурс Транс</a>'];
            let keys = Object.keys(window.currentElement);
            keys.forEach(function (key) {
                $.ajax({
                    url: 'index.php',
                    data: {
                       r: key + '/get-name',
                       id: window.currentElement[key]
                    },
                    success: function(data) {
                        if (badSpots) {
                            breadcrumpsArray[levelsWithBadSpot[key]] = '<a onclick="window.setLevel' + capitalize(key) + '(\'' + window.currentElement[key] + '\');" data-id="' + window.currentElement[key] + '" id="' + key + '" href="#">' + data + '</a>';
                        } else {
                            breadcrumpsArray[levels[key]] = '<a onclick="window.setLevel' + capitalize(key) + '(\'' + window.currentElement[key] + '\');" data-id="' + window.currentElement[key] + '" id="' + key + '" href="#">' + data + '</a>';
                        }
                    }
                });
            });

            setTimeout( function() {
                breadcrumpsDiv.html(breadcrumpsArray.join(' >>> '))
            }, 300);

        };


        let myMap = new ymaps.Map('map', {
            center: [55.751574, 37.573856],
            zoom: 11,
            behaviors: ['default', 'scrollZoom'],
            controls: []
        }, {
            searchControlProvider: 'yandex#search',
            suppressMapOpenBlock: true
        });

        window.setLevelCar = function(id) {
            window.currentElement.car = id;
            divine.val('car_' + id).trigger('change');
            window.pastElement = {
                key: 'spot',
                id: window.currentElement.spot
            };
            $('div#info-department').addClass('hide');
            $('div#ts-info').removeClass('hide');
            $('div#info-company').addClass('hide');
            loading_animation_start(divSidebar);
            $.ajax({
                url: 'index.php',
                data: {
                    r: 'car/get-data',
                    id: id
                },
                success: function(data) {
                    let carData = JSON.parse(data);
                    changeInfoForCar(carData);
                },
                error: function () {
                    changeInfoForCar({});
                },
                complete: function() {
                    loading_animation_end(divSidebar);
                }
            });
        };


        window.setLevelSpot = function(id) {
            loading_animation_start(divMap);
            /**
             * Cars ajax
             **/
            $.ajax({
                url: 'index.php',
                data: {
                   r: 'spot/get-cars',
                   id: id
                },
                success: function(data) {
                    if (data === 'NaN') {
                        alert('На данном участке нет активных машин');
                        window.stop = true;
                        return;
                    }
                    window.stop = false;
                    delete window.currentElement.car;
                    window.currentElement.spot = id;
                    divine.val('spot_' + id).trigger('change');
                    window.pastElement = {
                        key: window.badSpots ? 'organization' : 'autocolumn',
                        id: window.badSpots ? window.currentElement.organization : window.currentElement.autocolumn
                    };
                    let c_array = [];
                    myMap.geoObjects.removeAll();
                    let c_pm, carLayout, carLayoutUrl, carLayoutClass, carLayoutChecked, carLayoutUrlChecked, carLayoutClassChecked;
                    let carBalloonContentLayout = ymaps.templateLayoutFactory.createClass([
                        '<ul class=list>',
                        '{% for geoObject in properties.geoObjects %}',
                        '<li><a onclick="window.setLevelCar(\'{{geoObject.id}}\')" href=# id="{{geoObject.id}}" class="list_item car-baloon"><img src="yan/img/auto_icon/point_blue_{{geoObject.type}}.svg" alt="">{{ geoObject.properties.balloonContentHeader|raw }}</a></li>',
                        '{% endfor %}',
                        '</ul>'
                    ].join(''));
                    let carClusterLayout = ymaps.templateLayoutFactory.createClass(
                        '<div class="bb-cluster-car"><span class="bb-num">{{ properties.geoObjects.length }}</span></div>'
                    );
                    let clustererCars = new ymaps.Clusterer(
                        {
                            clusterBalloonContentLayout: carBalloonContentLayout,
                            clusterIcons: [{
                                href: '',
                                size: [62, 62],
                                offset: [-26, -26]
                            }],
                            gridSize: 1024,
                            clusterIconContentLayout: carClusterLayout,
                            zoomMargin : [50,50,50,50]
                        }
                    );
                    console.log(carBalloonContentLayout);
                    let response = JSON.parse(data);
                    let cars = response.cars;
                    cars.forEach( function (car) {
                        carLayoutUrl =
                            car.inline ?
                            'yan/img/auto_icon/point_blue_' + car.type + '.svg' :
                            'yan/img/auto_icon/point_noIn_' + car.type + '.svg';
                        carLayoutClass = car.inline ? "bb-num-car" : "bb-num-car-inline";
                        carLayout = ymaps.templateLayoutFactory.createClass(
                            '<div class="bb"><span class="' +
                            carLayoutClass +
                            '"><img src="' +
                            carLayoutUrl +
                            '" alt="auto"></span></div>'
                        );


                        carLayoutClassChecked = car.inline ?
                            "bb-num-car-white" :
                            "bb-num-car-inline_checked";
                        carLayoutUrlChecked = 'yan/img/auto_icon/point_' +
                            (car.inline ? 'noIn_check_' : '') +
                            car.type + '.svg';
                        carLayoutChecked = ymaps.templateLayoutFactory.createClass(
                            '<div class="bb"><span class="' +
                            carLayoutClassChecked +
                            '"><img src="' +
                            carLayoutUrlChecked +
                            '" alt="auto"></span></div>'
                        );

                        c_pm = new ymaps.Placemark([car.x_pos, car.y_pos], {
                            balloonContent: car.description,
                            balloonContentHeader: car.model,
                            balloonContentBody: car.number,
                            balloonContentFooter: car.status
                        },{
                            hasBalloon: false,
                            iconLayout: 'default#imageWithContent',
                            iconImageHref: '',
                            iconImageSize: [62, 62],
                            iconContentOffset: [-70, 75],
                            iconImageOffset: [-24, -24],
                            iconContentLayout: carLayout
                        });

                        c_pm.layout = carLayout;
                        c_pm.layoutChecked = carLayoutChecked;
                        c_pm.id = car.id;
                        c_pm.model = car.model;

                        c_pm.events.add('click', function(c) {
                            let currentClickCar = c.originalEvent.target;
                            if (window.hasOwnProperty('currentCar')) {
                                window.currentCar.options.set('iconContentLayout', window.currentCar.layout);
                            }
                            window.currentCar = currentClickCar;
                            $('#nameTS').html(currentClickCar.model);
                            currentClickCar.options.set('iconContentLayout', currentClickCar.layoutChecked);
                            window.setLevelCar(currentClickCar.id);
                        });

                        c_array.push(c_pm);
                        c_pm = null;
                        carLayout = null;
                        carLayoutClass = null;
                        carLayoutUrl = null;
                        carLayoutChecked = null;
                        carLayoutClassChecked = null;
                        carLayoutUrlChecked = null;
                    });
                    if (response.hasOwnProperty('bounds')) {
                        myMap.setBounds(response.bounds)
                    } else if (response.hasOwnProperty('center')) {
                        myMap.setCenter(response.center);
                    }
                    clustererCars.add(c_array);
                    myMap.geoObjects.add(clustererCars);
                },
                complete: function() {
                    loading_animation_end(divMap);
                }
            });



            loading_animation_start(divSidebar);
            $.ajax({
                url: 'index.php',
                data: {
                    r: 'spot/get-stats',
                    id: id
                },
                success: function(data) {
                    if (!window.stop) {
                        let stats = JSON.parse(data);
                        $('div#info-department').removeClass('hide');
                        $('div#ts-info').addClass('hide');
                        $('div#info-company').addClass('hide');
                        changeInfoForDepartment(stats);
                    }
                },
                error: function(data) {
                    //
                },
                complete: function() {
                    loading_animation_end(divSidebar);
                }
            });
        };


        window.setLevelAutocolumn = function(id)
        {
            loading_animation_start(divMap);
            /**
             * Spots ajax
             **/
            $.ajax({
                url: 'index.php',
                data: {
                    r: 'autocolumn/get-spots',
                    id: id
                },
                success: function(data) {
                    if (data === 'NaN') {
                        alert('Не получено участков');
                        window.stop = true;
                        return;
                    }
                    window.stop = false;
                    delete window.currentElement.spot;
                    delete window.currentElement.car;
                    window.currentElement.autocolumn = id;
                    divine.val('autocolumn_' + id).trigger('change');
                    window.pastElement = {
                        key: 'organization',
                        id: window.currentElement.organization
                    };
                    let s_array = [];
                    myMap.geoObjects.removeAll();
                    let spotBalloonContentLayout = ymaps.templateLayoutFactory.createClass([
                        '<ul class=list>',
                        '{% for geoObject in properties.geoObjects %}',
                            '{% if geoObject.carsTotal == 0 %}',
                                '<li>{{ geoObject.name }} ({{ geoObject.carsTotal }})</li>',
                            '{% else %}',
                                '<li><a onclick="window.setLevelSpot(\'{{geoObject.id}}\')" href=# class="list_item car-baloon">{{ geoObject.name }} ({{ geoObject.carsTotal }})</a></li>',
                            '{% endif %}',
                        '{% endfor %}',
                        '</ul>'
                    ].join(''));
                    let spotClusterLayout = ymaps.templateLayoutFactory.createClass(
                        '<div class="bb-cluster-car"><span class="bb-num">{{ properties.geoObjects.length }}</span></div>'
                    );
                    let s_clusterer = new ymaps.Clusterer(
                        {
                            clusterBalloonContentLayout: spotBalloonContentLayout,
                            clusterIcons: [{
                                href: '',
                                size: [62, 62],
                                offset: [-26, -26]
                            }],
                            gridSize: 4,
                            clusterIconContentLayout: spotClusterLayout,
                            zoomMargin : [50,50,50,50]
                        }
                    );
                    let s_pm, spotLayout;
                    let response = JSON.parse(data);
                    let spots = response.spots;
                    spots.forEach( function (spot) {
                        spotLayout = ymaps.templateLayoutFactory.createClass(
                            '<div class="bb"><span class="bb-num-spot">' +
                            spot.carsTotal +
                            '</span><span id="spot_name" class="bb-name">' +
                            spot.spot.name +
                            '</span></div>'
                        );

                        s_pm = new ymaps.Placemark([spot.spot.x_pos, spot.spot.y_pos], {
                        }, {
                            iconLayout: 'default#imageWithContent',
                            iconImageHref: '',
                            iconImageSize: [62, 62.5],
                            iconContentOffset: [-70, 75],
                            iconImageOffset: [-24, -24],
                            preset: 'islands#greenDotIconWithCaption',
                            iconContentLayout: spotLayout
                        });

                        s_pm.events.add('click', function() {
                           window.setLevelSpot(spot.spot.id);
                           window.currentLevel = 3;
                        });

                        s_pm.id = spot.spot.id;
                        s_pm.carsTotal = spot.carsTotal;
                        s_pm.name = spot.spot.name;

                        s_array.push(s_pm);
                        s_pm = null;
                        spotLayout = null;
                    });
                    s_clusterer.add(s_array);
                    myMap.geoObjects.add(s_clusterer);
                    if (response.hasOwnProperty('bounds')) {
                        myMap.setBounds(response.bounds);
                    } else if (response.hasOwnProperty('center')) {
                        myMap.setCenter(response.center, 9);
                    }
                },
                complete: function() {
                    loading_animation_end(divMap);
                }
            });

            loading_animation_start(divSidebar);
            $.ajax({
                url: 'index.php',
                data: {
                    r: 'autocolumn/get-stats',
                    id: id
                },
                success: function(data) {
                    if (!window.stop) {
                        let stats = JSON.parse(data);
                        $('div#info-department').removeClass('hide');
                        $('div#ts-info').addClass('hide');
                        $('div#info-company').addClass('hide');
                        changeInfoForDepartment(stats);
                    }
                },
                error: function() {
                    //
                },
                complete: function() {
                    loading_animation_end(divSidebar);
                }
            });
        };


        window.setLevelOrganization = function(id)
        {
            loading_animation_start(divMap);
            /**
             * Autocolumns ajax
             **/
            $.ajax({
                url: 'index.php',
                data: {
                    r: 'organization/get-autocolumns',
                    id: id
                },
                success: function(data) {
                    if (data === 'NaN') {
                        return;
                    }
                    let a_array = [];
                    let autocolumnBalloonContentLayout = ymaps.templateLayoutFactory.createClass([
                        '<ul class=list>',
                        '{% for geoObject in properties.geoObjects %}',
                            '{% if geoObject.carsTotal == 0 %}',
                                '<li>{{ geoObject.name }} ({{ geoObject.carsTotal }})</li>',
                            '{% else %}',
                                '<li><a onclick="window.setLevelAutocolumn(\'{{geoObject.id}}\')" href=# class="list_item car-baloon">{{ geoObject.name }} ({{ geoObject.carsTotal }})</a></li>',
                            '{% endif %}',
                        '{% endfor %}',
                        '</ul>'
                    ].join(''));
                    let autocolumnClusterLayout = ymaps.templateLayoutFactory.createClass(
                        '<div class="bb-cluster-car"><span class="bb-num">{{ properties.geoObjects.length }}</span></div>'
                    );
                    let a_clusterer = new ymaps.Clusterer(
                        {
                            clusterBalloonContentLayout: autocolumnBalloonContentLayout,
                            clusterIcons: [{
                                href: '',
                                size: [62, 62],
                                offset: [-26, -26]
                            }],
                            gridSize: 4,
                            clusterIconContentLayout: autocolumnClusterLayout,
                            zoomMargin : [50,50,50,50]
                        }
                    );
                    let a_pm, autoColLayout;
                    let autocolumns = JSON.parse(data);
                    autocolumns.forEach( function (autocolumn) {
                        autoColLayout = ymaps.templateLayoutFactory.createClass(
                            '<div class="bb"><span class="bb-num">'
                            + autocolumn.carsTotal
                            + '</span> <span id="auto_name" class="bb-name">'
                            + autocolumn.autocolumn.name
                            +'</span></div>'
                        );
                        a_pm = new ymaps.Placemark([autocolumn.autocolumn.x_pos, autocolumn.autocolumn.y_pos], {
                        }, {
                            iconLayout: 'default#imageWithContent',
                            iconImageHref: '',
                            iconImageSize: [62, 62.5],
                            iconContentOffset: [-70, 75],
                            iconImageOffset: [-24, -24],
                            preset: 'islands#greenDotIconWithCaption',
                            iconContentLayout: autoColLayout
                        });

                        a_pm.events.add('click', function() {
                            window.setLevelAutocolumn(autocolumn.autocolumn.id);
                            window.currentLevel = 2;
                        });

                        a_pm.id = autocolumn.autocolumn.id;
                        a_pm.name = autocolumn.autocolumn.name;
                        a_pm.carsTotal = autocolumn.carsTotal;

                        a_array.push(a_pm);
                        a_pm = null;
                        autoColLayout = null;
                    });
                    a_clusterer.add(a_array);
                    myMap.geoObjects.add(a_clusterer);
                },
                error: function() {
                    alert('Что-то пошло не так c получением автоколонн');
                }
            });

            /**
             * Bad spots ajax
             **/
            $.ajax({
                url: 'index.php',
                data: {
                    r: 'organization/get-bad-spots',
                    id: id
                },
                success: function(data) {
                    if (data === 'NaN') {
                        alert('Не получено участков без автоколонн и/или автоколонн');
                        window.stop = true;
                        return;
                    }
                    window.stop = false;
                    myMap.geoObjects.removeAll();
                    let b_s_array = [];
                    let badSpotBalloonContentLayout = ymaps.templateLayoutFactory.createClass([
                        '<ul class=list>',
                        '{% for geoObject in properties.geoObjects %}',
                            '{% if geoObject.carsTotal == 0 %}',
                                '<li>{{ geoObject.name }} ({{ geoObject.carsTotal }})</li>',
                            '{% else %}',
                                '<li><a onclick="window.setLevelSpot(\'{{geoObject.id}}\')" href=# class="list_item car-baloon">{{ geoObject.name }} ({{ geoObject.carsTotal }})</a></li>',
                            '{% endif %}',
                        '{% endfor %}',
                        '</ul>'
                    ].join(''));
                    let badSpotClusterLayout = ymaps.templateLayoutFactory.createClass(
                        '<div class="bb-cluster-car"><span class="bb-num">{{ properties.geoObjects.length }}</span></div>'
                    );
                    let b_s_clusterer = new ymaps.Clusterer(
                        {
                            clusterBalloonContentLayout: badSpotBalloonContentLayout,
                            clusterIcons: [{
                                href: '',
                                size: [62, 62],
                                offset: [-26, -26]
                            }],
                            gridSize: 4,
                            clusterIconContentLayout: badSpotClusterLayout,
                            zoomMargin : [50,50,50,50]
                        }
                    );
                    let b_s_pm, badSpotLayout;
                    let response = JSON.parse(data);
                    let badSpots = response.badSpots;
                    badSpots.forEach( function (badSpot) {
                        badSpotLayout = ymaps.templateLayoutFactory.createClass(
                            '<div class="bb"><span class="bb-num">'
                            + badSpot.carsTotal
                            + '</span> <span id="auto_name" class="bb-name">'
                            + badSpot.badSpot.name
                            +'</span></div>'
                        );
                        b_s_pm = new ymaps.Placemark([badSpot.badSpot.x_pos, badSpot.badSpot.y_pos], {
                        }, {
                            iconLayout: 'default#imageWithContent',
                            iconImageHref: '',
                            iconImageSize: [62, 62.5],
                            iconContentOffset: [-70, 75],
                            iconImageOffset: [-24, -24],
                            preset: 'islands#greenDotIconWithCaption',
                            iconContentLayout: badSpotLayout
                        });

                        b_s_pm.events.add('click', function() {
                            window.setLevelSpot(badSpot.badSpot.id);
                            window.currentLevel = 2;
                        });

                        b_s_pm.id = badSpot.badSpot.id;
                        b_s_pm.name = badSpot.badSpot.name;
                        b_s_pm.carsTotal = badSpot.carsTotal;

                        b_s_array.push(b_s_pm);
                        b_s_pm = null;
                        badSpotLayout = null;
                    });
                    b_s_clusterer.add(b_s_array);
                    myMap.geoObjects.add(b_s_clusterer);
                    if (response.hasOwnProperty('bounds')) {
                        myMap.setBounds(response.bounds);
                    } else if (response.hasOwnProperty('center')) {
                        myMap.setCenter(response.center, 9);
                    }
                },
                complete: function() {
                    loading_animation_end(divMap);
                }
            });

            delete window.currentElement.autocolumn;
            delete window.currentElement.spot;
            delete window.currentElement.car;
            window.currentElement.organization = id;
            divine.val('organization_' + id).trigger('change');
            window.pastElement = {
                key: 'company',
                id: ''
            };

            loading_animation_start(divSidebar);
            $.ajax({
                url: 'index.php',
                data: {
                    r: 'organization/get-stats',
                    id: id
                },
                success: function(data) {
                    if (!window.stop) {
                        let stats = JSON.parse(data);
                        $('div#info-department').removeClass('hide');
                        $('div#ts-info').addClass('hide');
                        $('div#info-company').addClass('hide');
                        changeInfoForDepartment(stats);
                    }
                },
                error: function() {
                    //
                },
                complete: function() {
                    loading_animation_end(divSidebar);
                }
            });
        };



        window.setLevelCompany = function()
        {
            myMap.setBounds(<?= \app\models\Organization::getMaxAndMinCoordinatesForAPI() ?>, {checkZoomRange: true});
            window.currentElement = {
                company: 'ООО Ресурс Транс'
            };
            let o_pm, orgLayout, o_array = [];
            myMap.geoObjects.removeAll();
            $('div#info-department').addClass('hide');
            $('div#ts-info').addClass('hide');
            $('div#info-company').removeClass('hide');

            <?php
                foreach ($organizations as $organization) {
            ?>
                orgLayout = ymaps.templateLayoutFactory.createClass(
                    '<div class="bb">' +
                    '<span class="bb-num-org">' +
                    <?= $organization->carsTotal ?> +
                    '</span><span class="bb-name"><?= $organization->getTown() ?>' +
                    '</span></div>'
                );
            o_pm = new ymaps.Placemark([<?= $organization->x_pos ?>, <?= $organization->y_pos ?>], {
                iconCaption : '<?= $organization->getTown() ?>'
            }, {
                iconLayout: 'default#imageWithContent',
                iconImageHref: '',
                iconImageSize: [62, 67.5],
                iconContentOffset: [-74, 83],
                iconImageOffset: [-24, -24],
                preset: 'islands#greenDotIconWithCaption',
                iconContentLayout: orgLayout
            });
            o_pm.events.add('click', function() {
                window.setLevelOrganization('<?= $organization->id ?>');
            });

            o_array.push(o_pm);

            myMap.geoObjects.add(o_pm);

            o_pm = null;
            <?php } ?>
            divine.val('company').trigger('change');
        };

        window.setLevelCompany();

        divine.change(function () {
            console.log(window.currentElement);
            window.badSpots = window.currentElement.hasOwnProperty('spot') && !window.currentElement.hasOwnProperty('autocolumn');
            console.log(window.pastElement);
            console.log('badSpots',window.badSpots);
            setBreadcrumps(window.badSpots);
        });

        backButton.click( function() {
            window['setLevel' + capitalize(window.pastElement.key)](window.pastElement.id);
        });

    }); //ymaps.ready()

</script>
