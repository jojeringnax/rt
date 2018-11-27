
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=27544797-3131-4759-9f4b-54f17c827eb2&lang=ru_RU" type="text/javascript"></script>

    <meta charset="UTF-8">
    <title>Title</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">


</head>
<body>
<div class="result" style="position:fixed;width: 20%; height: 100%;left:0;">
    <section id="info-company" class="section-info">
        <div class="main-item-result">
            <div class="logo">
                <div class="burger-logo">
                    <img src="yan/img/burger-menu.svg" alt="">
                </div>
                <div class="img-logo">
                    <img src="yan/img/logo.svg" alt="">
                </div>
            </div>
            <div id="company-info">
                <span id="nameCompany">Компания</span>
                <div class="ts-title">
                    <span class="img"><img src="yan/img/delivery-truck.svg" alt="#" class="span-yan/img-h3"></span>
                    <span id="tranport-means" class="ts-text h3-main">Транспортные средства</span>
                </div>
                <div id="total" class="item-info">
                    <span id="" class="trans-auto">Всего, шт.</span>
                    <span id="compAmOfTs" class="figures">311</span>
                </div>
                <div class="item-info">
                    <p id="on-line" class="trans-auto">На линии, шт.</p>
                    <p id="compOnLine" class="figures">124</p>
                </div>
                <div class="item-info">
                    <p id="on-repair" class="trans-auto">На ремонте, шт.</p>
                    <p id="compOnRep" class="figures">3</p>
                </div>
                <div class="item-info">
                    <p id="on-to" class="trans-auto">На ТО, шт.</p>
                    <p id="compOnTo" class="figures">4</p>
                </div>
            </div>

            <div id="request" class="">
                <div class="request-title">
                    <span class="img"><img src="yan/img/copy.svg" alt="#" class="span-yan/img-h3-2nd"></span>
                    <span id="title-request" class="text">Заявки</span>
                </div>
                <div class="item-info">
                    <p id="done" class="trans-auto">Выполнено, шт.</p>
                    <p id="compReqDone" class="figures">311</p>
                </div>
                <div class="item-info">
                    <p id="canseled" class="trans-auto">Отменено, шт.</p>
                    <p id="compReqCans" class="figures">124</p>
                </div>
                <div class="item-info">
                    <p id="transfered" class="trans-auto">Переданы на СП, шт.</p>
                    <p id="compReqTransf" class="figures">3</p>
                </div>
            </div>

            <div id="indicators" class="indicators-class">
                <div class="indicators-title">
                    <span class="img"><img src="yan/img/pie.svg" alt="#" class="span-yan/img-h3-3nd"></span>
                    <span id="h3-indicator" class="text">Показатели компании</span>
                </div>
                <div class="item-bar">
                    <div class="" style="display:flex; justify-content:center; align-items:center; position:relative; margin-left: 15px;">
                        <p id="cpb_per1" class="p-bar">60%</p>
                        <div id="circle1" class="circle"></div>
                    </div>
                    <div class="div-bar-text">
                        <p id="filed" class="p-bar-text">Поданы через АС<br/>"Авто-Контроль", %</p>
                    </div>
                </div>
                <div class="item-bar">
                    <div class="" style="display:flex; justify-content:center; align-items:center; position:relative; margin-left: 15px;">
                        <p id="cpb_per2" class="p-bar">30%</p>
                        <div id="circle2" class="circle"></div>
                    </div>
                    <div class="div-bar-text">
                        <p id="processed" class="p-bar-text-str">Обработано ПЛ, %</p>
                    </div>
                </div>
                <div class="item-bar">
                    <div class="" style="display:flex; justify-content:center; align-items:center; position:relative; margin-left: 15px;">
                        <p id="cpb_per3" class="p-bar">90%</p>
                        <div id="circle3" class="circle"></div>
                    </div>
                    <div class="div-bar-text">
                        <p id="dtp" class="p-bar-text">ДТП по вине<br/>компании, %</p>
                    </div>
                </div>
                <div class="item-bar">
                    <div class="" style="display:flex; justify-content:center; align-items:center; position:relative; margin-left: 15px;">
                        <p id="cpb_per4" class="p-bar">100%</p>
                        <div id="circle4" class="circle"></div>
                    </div>
                    <div class="div-bar-text">
                        <p id="mileage" class="p-bar-text">Принятый пробег<br/>по БСМТ, %</p>
                    </div>
                </div>
                <div class="item-bar">
                    <div class="" style="display:flex; justify-content:center; align-items:center; position:relative; margin-left: 15px;">
                        <p id="cpb_per5" class="p-bar">100%</p>
                        <div id="circle5" class="circle"></div>
                    </div>
                    <div class="div-bar-text">
                        <p id="sla" class="p-bar-text">Принятый пробег<br/>SLA Филиала, %</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="info-department" class="hide section-info">
        <div class="sidebar">
            <div id="head-filial">
                <div class="logo">
                    <div class="burger-logo">
                        <img src="yan/img/burger-menu.svg" alt="">
                    </div>
                    <div class="img-logo">
                        <img src="yan/img/logo.svg" alt="">
                    </div>
                </div>
                <div id="department" class="level-menu">
                    <span id="level-menu-company" class="text-level-menu">Компания</span>
                    <img src="yan/img/arrow.svg" alt="">
                    <span id="level-menu-department" class="text-level-menu">Филиал</span>
                </div>
                <div class="title-department">
                    <span id="nameofDST" class="title-department-text">Московский филиал</span>
                </div>
                <div class="">
                    <div class="ts-title">
                        <span class="img"><img src="yan/img/delivery-truck.svg" alt="#" class="span-yan/img-h3"></span>
                        <span id="tranport-means" class="ts-text h3-main">Транспортные средства</span>
                    </div>
                    <div id="" class="item-info">
                        <span id="" class="trans-auto">Всего, шт.</span>
                        <span id="totTs" class="figures">311</span>
                    </div>
                    <div class="item-info">
                        <p id="on-line-department" class="trans-auto">На линии, шт.</p>
                        <p id="OnLine" class="figures">124</p>
                    </div>
                    <div class="item-info">
                        <p id="on-repair" class="trans-auto">На ремонте, шт.</p>
                        <p id="OnRep" class="figures">3</p>
                    </div>
                    <div class="item-info">
                        <p id="on-to" class="trans-auto">На ТО, шт.</p>
                        <p id="onTo" class="figures">4</p>
                    </div>
                </div>

                <div class="div-transport">
                    <hr/ class="hr-trans">
                    <div class="item-info transort-department">
                        <div class="transport-title">
                            <span class="span-h3-filial"><img src="yan/img/car-1.svg" alt="#" class="span-yan/img-h3-filial"></span>
                            <p id="passenger-car" class="p-type-transport">Легковые</p>
                        </div>
                        <p id="passCar" class="p-quantity">40</p>
                    </div>
                    <hr/ class="hr-trans">
                    <div class="item-info transort-department">
                        <div class="transport-title">
                            <span class="span-h3-filial"><img src="yan/img/car-2.svg" alt="#" class="span-yan/img-h3-filial"></span>
                            <p id="freight" class="p-type-transport">Грузовые</p>
                        </div>
                        <p id="freightCar" class="p-quantity">221</p>
                    </div>
                    <hr/ class="hr-trans">
                    <div class="item-info transort-department">
                        <div class="transport-title">
                            <span class="span-h3-filial"><img src="yan/img/car-3.svg" alt="#" class="span-yan/img-h3-filial"></span>
                            <p id="bus" class="p-type-transport">Автобусы</p>
                        </div>
                        <p id="busCar" class="p-quantity">20</p>
                    </div>
                    <hr/ class="hr-trans">
                    <div class="item-info transort-department">
                        <div class="transport-title">
                            <span class="span-h3-filial"><img src="yan/img/car-4.svg" alt="#" class="span-yan/img-h3-filial"></span>
                            <p id="spec" class="p-type-transport">Спецтехника</p>
                        </div>
                        <p id="specCar" class="p-quantity">12</p>
                    </div>
                    <hr/ class="hr-trans">
                </div>
            </div>

            <div class="">
                <div id="request-department" class="">
                    <div class="request-title">
                        <span class="img"><img src="yan/img/copy.svg" alt="#" class="span-yan/img-h3-2nd"></span>
                        <span id="title-request" class="text">Заявки</span>
                    </div>
                    <div class="item-info">
                        <p id="" class="trans-auto">Выполнено, шт.</p>
                        <p id="ReqDone" class="figures">311</p>
                    </div>
                    <div class="item-info">
                        <p id="" class="trans-auto">Отменено, шт.</p>
                        <p id="ReqCans" class="figures">124</p>
                    </div>
                    <div class="item-info">
                        <p id="" class="trans-auto">Переданы на СП, шт.</p>
                        <p id="ReqTransf" class="figures">3</p>
                    </div>
                </div>
            </div>
            <div id="indicators-department" class="indicators-class">
                <div class="indicators-title">
                    <span class="img"><img src="yan/img/pie.svg" alt="#" class="span-yan/img-h3-3nd"></span>
                    <span id="h3-indicator" class="text">Показатели компании</span>
                </div>
                <div class="item-bar">
                    <div class="ilia" style="display:flex; justify-content:center; align-items:center; position:relative;">
                        <p id="bps_per1" class="p-bar">60%</p>
                        <div id="spb1" class="circle"></div>
                    </div>
                    <div class="div-bar-text">
                        <p id="filed" class="p-bar-text">Поданы через АС<br/>"Авто-Контроль", %</p>
                    </div>
                </div>
                <div class="item-bar">
                    <div class="ilia" style="display:flex; justify-content:center; align-items:center; position:relative;">
                        <p id="bps_per2" class="p-bar">30%</p>
                        <div id="spb2" class="circle"></div>
                    </div>
                    <div class="div-bar-text">
                        <p id="processed" class="p-bar-text-str">Обработано ПЛ, %</p>
                    </div>
                </div>
                <div class="item-bar">
                    <div class="ilia" style="display:flex; justify-content:center; align-items:center; position:relative;">
                        <p id="bps_per2" class="p-bar">90%</p>
                        <div id="spb3" class="circle"></div>
                    </div>
                    <div class="div-bar-text">
                        <p id="dtp" class="p-bar-text">ДТП по вине<br/>компании, %</p>
                    </div>
                </div>
                <div class="item-bar">
                    <div class="ilia" style="display:flex; justify-content:center; align-items:center; position:relative;">
                        <p id="bps_per4" class="p-bar">100%</p>
                        <div id="spb4" class="circle"></div>
                    </div>
                    <div class="div-bar-text">
                        <p id="mileage" class="p-bar-text">Принятый пробег<br/>по БСМТ, %</p>
                    </div>
                </div>
                <div class="item-bar">
                    <div class="ilia" style="display:flex; justify-content:center; align-items:center; position:relative;">
                        <p id="bps_per5" lass="p-bar">100%</p>
                        <div id="spb5" class="circle"></div>
                    </div>
                    <div class="div-bar-text">
                        <p id="sla" class="p-bar-text">Принятый пробег<br/>SLA Филиала, %</p>
                    </div>
                </div>
            </div>
            <div class="indic-bot">
                <div class="div-meanings div-pr25">
                    <p id="lmch-2" class="p-meanings-2nd"><span class="span-figures-2nd">5,1</span> л\мч<br/>ТМЧ</p>
                </div>
                <div class="div-meanings">
                    <p id="terminals-2" class="p-meanings-2nd"><span class="span-figures-2nd">110</span><br/>Терминалов</p>
                </div>
            </div>
        </div>
    </section>

    <section id="ts-info" class="section-info hide">
        <div id="head-filial">
            <div class="logo">
                <div class="burger-logo">
                    <img src="yan/img/burger-menu.svg" alt="">
                </div>
                <div class="img-logo">
                    <img src="yan/img/logo.svg" alt="">
                </div>
            </div>
            <div id="ts" class="level-menu">
                <span id="level-menu-company" class="text-level-menu">Компания</span>
                <img src="yan/img/arrow.svg" alt="">
                <span id="level-menu-department" class="text-level-menu">Филиал</span>
                <img src="yan/img/arrow.svg" alt="">
                <span id="level-menu-autocolumn" class="text-level-menu">Автоколонна</span>
                <img src="yan/img/arrow.svg" alt="">
                <span id="level-menu-spot" class="text-level-menu">Участок</span>
                <img src="yan/img/arrow.svg" alt="">
                <span id="level-menu-ts" class="text-level-menu">Транспортное средство</span>
            </div>
            <div class="title-ts">
                <span id="nameTS" class="title-ts-text">ТС №93 BMW</span>
            </div>
            <div class="">
                <div id="route" class="">
                    <div class="route-title">
                        <span class="yan/img"><img src="yan/img/route.svg" alt="#" class="span-yan/img-h3-2nd"></span>
                        <span id="title-route" class="text">Маршрут</span>
                    </div>
                    <div class="item-info">
                        <p id="" class="trans-auto">Кол-во пройденного пути, км</p>
                        <p id="doneRoute" class="figures">1300</p>
                    </div>
                    <div class="item-info">
                        <p id="" class="trans-auto">Кол-во отработанного t, ч.</p>
                        <p id="" class="figures">10</p>
                    </div>
                    <div class="item-info">
                        <p id="" class="trans-auto">Объем потреченного топлива, л.</p>
                        <p id="AmOfFuel" class="figures">12</p>
                    </div>
                </div>
            </div>
            <div id="oil" class="item-distance">
                <div class="plr-yan/img-distance my-auto">
                    <img class="yan/img-distance" src="yan/img/fuel.svg" alt="">
                </div>
                <div class="plr-col-distance">
                    <p id="oilChangeDist" class="distance-figure">50 000 км</p>
                    <p id="total" class="distance-text">До замены масла</p>
                </div>
                <div id="lb1" class="longlar"></div>
            </div>
            <div id="wheel" class="item-distance">
                <div class="plr-yan/img-distance my-auto">
                    <img class="yan/img-distance" src="yan/img/wheel.svg" alt="">
                </div>
                <div class="plr-col-distance">
                    <p id="tireChangeDist" class="distance-figure">2 000 км</p>
                    <p id="total" class="distance-text">До замены шин</p>
                </div>
                <div id="lb2" class="longlar"></div>
            </div>
            <div id="wheel" class="item-distance">
                <div class="plr-yan/img-distance my-auto">
                    <img class="yan/img-distance" src="yan/img/battery.svg" alt="">
                </div>
                <div class="plr-col-distance">
                    <p id="accChangeDist" class="distance-figure">23 000 часа</p>
                    <p id="total" class="distance-text">До замены АКБ</p>
                </div>
                <div id="lb3" class="longlar"></div>
            </div>

            <div id="wheel" class="item-distance">
                <div class="plr-yan/img-distance my-auto">
                    <img class="yan/img-distance" src="yan/img/key.svg" alt="">
                </div>
                <div class="plr-col-distance">
                    <p id="toChangeDist" class="distance-figure">70 000 км</p>
                    <p id="total" class="distance-text">До ТО</p>
                </div>
                <div id="lb4" class="longlar"></div>
            </div>
        </div>
    </section>
</div>
<div id="map" style="position:fixed;width: 80%; height: 100%;right:0;"></div>

</body>