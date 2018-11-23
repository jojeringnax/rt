<?php
require('work.php');
$json = file_get_contents('data.json');
$regions = file_get_contents('regions.json');
?>


<?= require_once('sidebar.php'); ?>

<script type="text/javascript">

    var pWrap = function(e) {
        return '<p>' + e + '</p>';
    };

    ymaps.ready( function() {
        let sectionCompany = $('section#info-company'), sectionDepartment = $('section#info-department'), sectionTS = $('section#ts-info'),
      MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
          '<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
      );
        var regions = JSON.parse('<?= $regions ?>').features;
        var myMap = new ymaps.Map('map', {
            center: [55.751574, 37.573856],
            zoom: 11,
            behaviors: ['default', 'scrollZoom']
        }, {
            searchControlProvider: 'yandex#search'
        });


        /**
         * Variable for determinate current depth-view level
         * @type {number}
         */
        window.currentLevel = 0;

        /**
         * Variable for the choosen element
         * @type {ymaps.Placemark}
         */
        window.currentElement = null;

        /**
         * Bread Crumbs
         * @type {*|jQuery|HTMLElement}
         */
        var levelMenuTs = $('div.level-menu#ts'),
            levelMenuTsDepartment = $('div.level-menu#ts > span#level-menu-department'),
            levelMenuTsAutocolumn = $('div.level-menu#ts > span#level-menu-autocolumn'),
            levelMenuTsSpot = $('div.level-menu#ts > span#level-menu-spot'),
            levelMenuTsTs = $('div.level-menu#ts > span#level-menu-ts'),
            levelMenuDepartment = $('div.level-menu#department'),
                addLevelInBreadCrumbs = function(level, text) {
                  levelMenuDepartment.innerHTML += '<img src="yan/img/arrow.svg" alt=""><span id="level-menu-'+level+'" class="text-level-menu">'+text+'</span>';
                };
            levelMenuDepDepartment = $('div.level-menu#department > span#level-menu-department');

        var setBoundsForPoints = function(map, array) {
            if(array.length === 1) {
                return map.setCenter(array[0].geometry._coordinates, 11);
            }
            var
                minX = array[0].geometry._coordinates[0],
                minY = array[0].geometry._coordinates[1],
                maxX = array[0].geometry._coordinates[0],
                maxY = array[0].geometry._coordinates[1];
            for(var i=0;i<array.length;i++) {
                minX = array[i].geometry._coordinates[0] < minX ? array[i].geometry._coordinates[0] : minX;
                minY = array[i].geometry._coordinates[1] < minY ? array[i].geometry._coordinates[1] : minY;
                maxX = array[i].geometry._coordinates[0] > maxX ? array[i].geometry._coordinates[0] : maxX;
                maxY = array[i].geometry._coordinates[1] > maxY ? array[i].geometry._coordinates[1] : maxY;
            }

            return map.setBounds([[minX,minY],[maxX,maxY]]);
        },
            actionClickPoint = function(map, target) {
                target.RTOptions.children.forEach(function(el) {
                    map.geoObjects.add(el);
                });
                setBoundsForPoints(map, target.RTOptions.children);
                if(target.RTOptions.master) {
                    target.RTOptions.master.RTOptions.children.forEach(function(el) {
                        map.geoObjects.remove(el);
                    });
                } else {
                    map.geoObjects.remove(target);
                }
                window.currentElement = target;
                if(window.currentLevel !== 0) {
                    map.controls.add(oneLevelLess);
                }
            };
        var d_point, s_point, a_point, c_point, spots, autocolumns, cars;
        for(var i = 0, d_len = regions.length, d_array = [];i < d_len;i++) {
            autocolumns = regions[i].autocolumns;
            d_point = new ymaps.Placemark(regions[i].geometry.coordinates,{
                iconContent: '43'
                },{
                iconLayout: 'default#imageWithContent',
                iconImageHref: 'yan/img/union.png',
                iconImageSize: [42, 47.5],
                iconImageOffset: [-24, -24],
                iconContentOffset: [15, 15],
                iconContentLayout: MyIconContentLayout
            });
            for(var j = 0, a_len = autocolumns.length, a_array = [];j < a_len;j++) {
                spots = autocolumns[j].spots;
                a_point = new ymaps.Placemark(autocolumns[j].geometry.coordinates,{
                  iconContent: '12'
                  },{
                    iconLayout: 'default#imageWithContent',
                    iconContentOffset: [15, 15],
                    iconImageHref: 'yan/img/union.png',
                    iconImageSize: [42, 47.5],
                    iconImageOffset: [-24, -24],
                    iconContentLayout: MyIconContentLayout
                });
                for (var k = 0, s_len = spots.length, s_array = [];k < s_len;k++) {
                    cars = spots[k].cars;
                    s_point = new ymaps.Placemark(spots[k].geometry.coordinates,{
                      iconContent: '1123'
                      },{
                      iconLayout: 'default#imageWithContent',
                      iconImageHref: 'yan/img/union.png',
                      iconImageSize: [42, 47.5],
                      iconContentOffset: [9, 13],
                      iconImageOffset: [-24, -24],
                      iconContentLayout: MyIconContentLayout
                    });
                    for (var l=0, c_len = cars.length, c_array = [], c_dep, c_autocolumn, c_spot;l < c_len;l++) {
                        c_point = new ymaps.Placemark(cars[l].geometry.coordinates,{
                          iconContent: ''
                          },{
                          iconLayout: 'default#imageWithContent',
                          iconImageHref: 'yan/img/point_'+cars[l].type+'.png',
                          iconImageSize: [42, 47.5],
                          iconContentOffset: [9, 13],
                          iconImageOffset: [-24, -24],
                          iconContentLayout: MyIconContentLayout});
                        c_point.RTOptions = {
                            id: cars[l].id,
                            master: s_point,
                            status: cars[l].status,
                            type: cars[l].type,
                            applications: {done: cars[l].application.done, canceled: cars[l].application.canceled, st: cars[l].application.st},
                            children: false,
                            regionType: 'c'
                        };
                        c_point.RTInfo = {
                            nameTS: cars[l].brand,
                            oilChangeDist: accounting.formatNumber(cars[l].oilChangeDist, 0, ' ') + ' км',
                            tireChangeDist: accounting.formatNumber(cars[l].tireChangeDist, 0, ' ') + ' км',
                            accChangeDist: accounting.formatNumber(cars[l].accChangeDist, 0, ' ') + ' ч',
                            toChangeDist: accounting.formatNumber(cars[l].toChangeDist, 0, ' ') + ' км',
                            lb1: cars[l].lbs.lb1,
                            lb2: cars[l].lbs.lb2,
                            lb3: cars[l].lbs.lb3,
                            lb4: cars[l].lbs.lb4
                        };
                        c_dep = regions[i];
                        c_autocolumn = autocolumns[j];
                        c_spot = spots[k];
                        c_point.events.add('click', function(e) {
                            var target = e.originalEvent.target;
                            sectionDepartment.classList.add('hide');
                            sectionCompany.classList.add('hide');
                            sectionTS.classList.remove('hide');
                            newInfoTs(target.RTInfo);
                            levelMenuTsDepartment.innerText = c_dep.regionName;
                            levelMenuTsAutocolumn.innerText = c_autocolumn.regionName;
                            levelMenuTsSpot.innerText = c_spot.regionName;
                            levelMenuTsTs.innerText = target.RTInfo.nameTS + ' ТС №' + target.RTOptions.id;
                        });
                        c_array.push(c_point);
                    }
                    s_point.RTOptions = {
                        id: spots[k].id,
                        master: a_point,
                        children: c_array,
                        regionType: 's',
                        regionName: spots[k].regionName
                    };
                    s_point.events.add('click', function(e) {
                        var target = e.originalEvent.target;
                        window.currentLevel = 3;
                        actionClickPoint(myMap, target);
                        addLevelInBreadCrumbs('spot',target.RTOptions.regionName);
                    });
                    s_array.push(s_point);
                } // Iteration through the spots  for (var k = 0, s_len = spots.length, s_array = [];k < s_len;k++) {
                a_point.RTOptions = {
                    id: autocolumns[j].id,
                    master: d_point,
                    children: s_array,
                    regionType: 'a',
                    regionName: autocolumns[j].regionName
                };
                a_point.events.add('click', function(e) {
                    var target = e.originalEvent.target;
                    window.currentLevel = 2;
                    actionClickPoint(myMap, target);
                    addLevelInBreadCrumbs('autocolumn',target.RTOptions.regionName);
                });
                a_array.push(a_point);
            } // Iteration through the autocolumns  for(var j = 0, a_len = autocolumns.length, a_array = [];j < a_len;j++) {
            d_point.RTOptions = {
                id: regions[i].id,
                master: false,
                children: a_array,
                regionType: 'd',
                regionName: regions[i].regionName
            };
            d_point.RTInfo = {
              'spb': {
                'spb1':0.2,
                'spb2':0.5,
                'spb3':1.0,
                'spb4':0.6,
                'spb5':0.9
              }
            };
            d_point.events.add('click', function(e) {
                var target = e.originalEvent.target;
                window.currentLevel = 1;
                actionClickPoint(myMap, target);
                sectionCompany.classList.add('hide');
                sectionTS.classList.add('hide');
                sectionDepartment.classList.remove('hide');
                console.log(levelMenuDepDepartment);
                levelMenuDepDepartment.innerText = target.RTOptions.regionName;
                newInfoDASTs(d_point.RTInfo);
            });
            d_array.push(d_point);
            myMap.geoObjects.add(d_point);
        } //Iteration through the departments for(var i = 0, d_len = regions.length, d_array = [];i < d_len;i++) {

        var oneLevelLess = new ymaps.control.Button('Вернуться на один уровень назад', {float: 'right'});
        oneLevelLess.events.add('click', function(e) {
            window.currentLevel--;
            if (window.currentLevel === 0) {
                window.currentElement.RTOptions.children.forEach(function(el) {
                    myMap.geoObjects.remove(el);
                });
                d_array.forEach(function(el) {
                   myMap.geoObjects.add(el);
                });
                a_array.forEach(function(el) {
                   myMap.geoObjects.remove(el);
                });
                setBoundsForPoints(myMap, d_array);
                myMap.controls.remove(oneLevelLess);
                sectionDepartment.classList.add('hide');
                sectionCompany.classList.remove('hide');
                return true;
            } else if (window.currentLevel === 2) {
                sectionTS.classList.add('hide');
                sectionDepartment.classList.remove('hide');
            }
            levelMenuDepartment.lastElementChild.remove();
            levelMenuDepartment.lastElementChild.remove();
            var
                currentLevelElements = window.currentElement.RTOptions.master.RTOptions.children,
                currentChildren = window.currentElement.RTOptions.children,
                currentElementAfterClick = window.currentElement.RTOptions.master;
            for(i=0;i<currentChildren.length;i++) {
                myMap.geoObjects.remove(currentChildren[i]);
            }
            for(i=0;i<currentLevelElements.length;i++) {
                myMap.geoObjects.add(currentLevelElements[i]);
            }
            setBoundsForPoints(myMap, currentLevelElements);
            window.currentElement = currentElementAfterClick;
        });
        myMap.events.add('boundschange', function(e) {
            if (e.get('newZoom') >= 12) {
                if(window.currentElement === null) {
                    d_array.forEach(function(el) {
                        myMap.geoObjects.remove(el);
                    });
                } else {
                    window.currentElement.RTOptions.children.forEach(function (el) {
                        myMap.geoObjects.remove(el);
                    });
                }
                c_array.forEach(function(el) {
                    myMap.geoObjects.add(el);
                });
            } else {
                c_array.forEach(function(el) {
                    myMap.geoObjects.remove(el);
                });

                if(window.currentElement === null) {
                    d_array.forEach(function(el) {
                        myMap.geoObjects.add(el);
                    });
                } else {
                    window.currentElement.RTOptions.children.forEach(function (el) {
                        myMap.geoObjects.add(el);
                    });
                }
            }
        });
    }); // ymaps.ready(function() {
</script>
<script src="js_new/accounting.min.js"></script>
<script src="js_new/progressbar.min.js"></script>
<script src="js_new/main.js"></script>
</html>
