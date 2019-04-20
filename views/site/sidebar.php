<?php
$carsTotalData = \app\models\Car::getTotalData();
$totalStatistic = \app\models\Statistic::getTotalStatistic();
?>

<div class="result" style="position:fixed;width: 20%; height: 100%;left:0; ">
    <section  class="section-info">
        <div class="logo">
            <div class="img-logo">
                <img src="yan/img/logo.svg" alt="">
            </div>
        </div>
        <div class="nav-sidebar" id="firm">
            Компания
        </div>

        <div class="sidebar" >
            <div id="info-company">
                <div>
                    <div class="ts-title">
                        <span class="img"><img src="yan/img/delivery-truck.svg" alt="#" class=""></span>
                        <span id="tranport-means" class="ts-text h3-main">Транспортные средства</span>
                    </div>
                    <div id="total" class="item-info">
                        <span class="trans-auto">Всего, шт.</span>
                        <span id="compAmOfTs" class="figures"><?= $carsTotalData['total'] ?></span>
                    </div>
                    <div class="item-info">
                        <p class="trans-auto">Готовы, шт.</p>
                        <p id="compReady" class="figures"><?= $carsTotalData['G'] ?></p>
                    </div>
                    <div class="item-info">
                        <p class="trans-auto">На ремонте, шт.</p>
                        <p id="compOnRep" class="figures"><?= $carsTotalData['R'] ?></p>
                    </div>
                    <div class="item-info">
                        <p class="trans-auto">На ТО, шт.</p>
                        <p id="compOnTo" class="figures"><?= $carsTotalData['TO'] ?></p>
                    </div>
                    <div class="item-info">
                        <p class="trans-auto">На линии, шт.</p>
                        <p id="compOnLine" class="figures"><?= $carsTotalData['totalInline'] ?></p>
                    </div>
                </div>

                <div id="request" class="">
                    <div class="request-title">
                        <span class="img"><img src="yan/img/copy.svg" alt="#" class="span-yan/img-h3-2nd"></span>
                        <span class="text">Заявки</span>
                    </div>
                    <div class="item-info">
                        <p class="trans-auto">Выполнено, шт.</p>
                        <p id="comp_applications_executed" class="figures"><?= $totalStatistic->applications_executed ?></p>
                    </div>
                    <div class="item-info">
                        <p class="trans-auto">Отменено, шт.</p>
                        <p id="comp_applications_canceled" class="figures"><?= $totalStatistic->applications_canceled ?></p>
                    </div>
                    <div class="item-info">
                        <p class="trans-auto">Переданы на СП, шт.</p>
                        <p id="comp_applications_sub" class="figures"><?= $totalStatistic->applications_sub ?></p>
                    </div>
                </div>

                <div id="indicators" class="indicators-class">
                    <div class="indicators-title">
                        <span class="img"><img src="yan/img/pie.svg" alt="#" class="span-yan/img-h3-3nd"></span>
                        <span class="text">Показатели компании</span>
                    </div>
                    <div class="item-bar">
                        <div class="cirk" style="display:flex; justify-content:center; align-items:center; position:relative; margin-left: 15px;">
                            <p id="comp_applications_ac_per" class="p-bar">60%</p>
                            <div id="comp_applications_ac" class="circle"></div>
                        </div>
                        <div class="div-bar-text">
                            <p id="filed" class="p-bar-text">Поданы через АС<br/>"Авто-Контроль", %</p>
                        </div>
                    </div>
                    <div class="item-bar">
                        <div class="cirk" style="display:flex; justify-content:center; align-items:center; position:relative; margin-left: 15px;">
                            <p id="comp_waybills_total_per" class="p-bar">30%</p>
                            <div id="comp_waybills_total" class="circle"></div>
                        </div>
                        <div class="div-bar-text">
                            <p id="processed" class="p-bar-text-str">Обработано ПЛ, %</p>
                        </div>
                    </div>
                    <div class="item-bar">
                        <div class="cirk" style="display:flex; justify-content:center; align-items:center; position:relative; margin-left: 15px;">
                            <p id="comp_accidents_total_per" class="p-bar">90%</p>
                            <div id="comp_accidents_total" class="circle"></div>
                        </div>
                        <div class="div-bar-text">
                            <p id="dtp" class="p-bar-text">ДТП по вине<br/>компании, %</p>
                        </div>
                    </div>
                    <div class="item-bar">
                        <div class="cirk" style="display:flex; justify-content:center; align-items:center; position:relative; margin-left: 15px;">
                            <p id="comp_WB_M_per" class="p-bar">100%</p>
                            <div id="comp_WB_M" class="circle"></div>
                        </div>
                        <div class="div-bar-text">
                            <p id="mileage-bsmt" class="p-bar-text">Принятый пробег<br/>по БСМТ, %</p>
                        </div>
                    </div>

                    <div class="indic-bot">
                        <div class="div-pr25">
                            <p id="lmch-2" class="p-meanings-2nd"></p><span id="comp_fuel" class="span-figures-2nd">5,1</span> л\мч<br/>ТМЧ</p>
                        </div>
                        <div class="div-meanings">
                            <p id="" class="p-meanings-2nd"><span id="comp_terminals" class="span-figures-2nd">110</span><br/>Терминалов</p>
                        </div>
                    </div>
                </div><!-- END__INFO_COMPANY -->
            </div>
            <!-- END SECTION 1 -->





            <!-- SECTION 2 -->

            <div id="info-department"  class="hide">
                <div class="">
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
                            <p class="trans-auto">Готовы, шт.</p>
                            <p id="OnReady" class="figures">4</p>
                        </div>
                        <div class="item-info">
                            <p id="on-repair" class="trans-auto">На ремонте, шт.</p>
                            <p id="OnRep" class="figures">3</p>
                        </div>
                        <div class="item-info">
                            <p id="on-to" class="trans-auto">На ТО, шт.</p>
                            <p id="onTo" class="figures">4</p>
                        </div>
                        <div class="item-info">
                            <p id="on-line-department" class="trans-auto">На линии, шт.</p>
                            <p id="OnLine" class="figures">124</p>
                        </div>
                    </div>

                    <div class="div-transport">
                        <hr class="hr-trans" />
                        <div id="all" class="item-info transort-department ">
                            <div class="transport-title">
<!--                                <span class="span-h3-filial"><img data-type="0" src="yan/img/auto_icon/point_blue_0.svg" alt="#" class=img-transport></span>-->
                                <p id="passenger-car" class="p-type-transport" style="margin: 0 !important">Все транспортные средства</p>
                            </div>
                        </div>
                        <hr class="hr-trans" />
                        <div id="light" class="item-info transort-department">
                            <div class="transport-title">
                                <span class="span-h3-filial"><img data-type="0" src="yan/img/auto_icon/point_blue_0.svg" alt="#" class=img-transport></span>
                                <p id="passenger-car" class="p-type-transport">Легковые</p>
                            </div>
                            <p id="passCar" class="p-quantity">40</p>
                        </div>
                        <hr class="hr-trans" />
                        <div id="truck" class="item-info transort-department">
                            <div class="transport-title">
                                <span class="span-h3-filial"><img data-type="1" src="yan/img/auto_icon/point_blue_1.svg" alt="#" class="img-transport"></span>
                                <p id="freight" class="p-type-transport">Грузовые</p>
                            </div>
                            <p id="freightCar" class="p-quantity">221</p>
                        </div>
                        <hr class="hr-trans" />
                        <div id="bus" class="item-info transort-department">
                            <div class="transport-title">
                                <span class="span-h3-filial"><img data-type="2" src="yan/img/auto_icon/point_blue_2.svg" alt="#" class="img-transport"></span>
                                <p class="p-type-transport">Автобусы</p>
                            </div>
                            <p id="busCar" class="p-quantity">20</p>
                        </div>
                        <hr class="hr-trans" />
                        <div id="spec" class="item-info transort-department">
                            <div class="transport-title">
                                <span class="span-h3-filial"><img data-type="3" src="yan/img/auto_icon/point_blue_3.svg" alt="#" class=img-transport></span>
                                <p id="spec" class="p-type-transport">Спецтехника</p>
                            </div>
                            <p id="specCar" class="p-quantity">12</p>
                        </div>
                        <hr class="hr-trans">
                    </div>
                </div>

                <div class="">
                    <div id="request-department" class="">
                        <div class="request-title">
                            <span class="img"><img src="yan/img/copy.svg" alt="#" class="span-yan/img-h3-2nd"></span>
                            <span id="title-request" class="text">Заявки</span>
                        </div>
                        <div class="item-info">
                            <p class="trans-auto">Выполнено, шт.</p>
                            <p id="applications_executed" class="figures">311</p>
                        </div>
                        <div class="item-info">
                            <p class="trans-auto">Отменено, шт.</p>
                            <p id="applications_canceled" class="figures">124</p>
                        </div>
                        <div class="item-info">
                            <p id="" class="trans-auto">Переданы на СП, шт.</p>
                            <p id="applications_sub" class="figures">3</p>
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
                            <p id="applications_ac_per" class="p-bar">60%</p>
                            <div id="applications_ac" class="circle"></div>
                        </div>
                        <div class="div-bar-text">
                            <p id="filed" class="p-bar-text">Поданы через АС<br/>"Авто-Контроль", %</p>
                        </div>
                    </div>
                    <div class="item-bar">
                        <div class="ilia" style="display:flex; justify-content:center; align-items:center; position:relative;">
                            <p id="waybills_total_per" class="p-bar">30%</p>
                            <div id="waybills_total" class="circle"></div>
                        </div>
                        <div class="div-bar-text">
                            <p id="processed" class="p-bar-text-str">Обработано ПЛ, %</p>
                        </div>
                    </div>
                    <div class="item-bar">
                        <div class="ilia" style="display:flex; justify-content:center; align-items:center; position:relative;">
                            <p id="accidents_total_per" class="p-bar">90%</p>
                            <div id="accidents_total" class="circle"></div>
                        </div>
                        <div class="div-bar-text">
                            <p id="dtp" class="p-bar-text">ДТП по вине<br/>компании, %</p>
                        </div>
                    </div>
                    <div class="item-bar">
                        <div class="ilia" style="display:flex; justify-content:center; align-items:center; position:relative;">
                            <p id="WB_M_per" class="p-bar">100%</p>
                            <div id="WB_M" class="circle"></div>
                        </div>
                        <div class="div-bar-text">
                            <p id="" class="p-bar-text">Принятый пробег<br/>по БСМТ, %</p>
                        </div>
                    </div>
                </div>
                <div class="indic-bot">
                    <div class="div-pr25">
                        <p id="lmch-2" class="p-meanings-2nd"></p><span id="fuel" class="span-figures-2nd">5,1</span> л\мч<br/>ТМЧ</p>
                    </div>
                    <div class="div-meanings">
                        <p id="" class="p-meanings-2nd"><span id="terminals" class="span-figures-2nd">110</span><br/>Терминалов</p>
                    </div>
                </div>
            </div> <!-- END__INFO__FILIAL__AUTOCOLUMN__SPOT -->
            <!-- END SECTION 2 -->

            <!-- SECTION 3 -->
            <div id="ts-info" class="hide">
                <div class="title-ts d-flex justify-content-start">
                    <div style="width: 2.5vh">
                        <img id="img-ts" src="" alt="" style="">
                    </div>
                    <div>
                        <span id="nameTS" class="" style="color: #005498; margin-left: 1.5vh; font-weight: bold; font-size: 1.4vh">ТС №93 BMW</span>
                    </div>

                </div>
                <div class="main-info-ts">
                    <div id="driver_info" class="item-ts-data">
                        <div>
                            <div class="name-driver d-flex item-ts-data">
                                <div class="d-flex align-items-start icon-ts">
                                    <img src="yan/img/info_auto_icon/user.svg" alt="driver_icon">
                                </div>
                                <div class="d-flex flex-column align-items-start" style="margin-left: 1.5vh">
                                    <span class="title-driver-name title-ts-text">Водитель:</span>
                                    <span id="driver" class="text-ts">Постывый Ярослав Николаевич</span>
                                </div>
                            </div>
                            <div class="phone-driver d-flex align-items-start item-ts-data">
                                <div class="d-flex align-items-start icon-ts">
                                    <img src="yan/img/info_auto_icon/call-answer.svg" alt="phone_driver">
                                </div>
                                <div class="d-flex flex-column align-items-start" style="margin-left: 1.5vh">
                                    <span class="title-ts-text title-driver-phone">Телефон:</span>
                                    <span id="phone" class="text-ts">8-908-235-82-56</span>
                                </div>
                            </div>
                        </div>
                        <hr class="ts-hr"/>
                    </div>

                    <div class="work-time item-ts-data d-flex flex-column">
                        <div class="d-flex">
                            <div class="icon-ts">
                                <img src="yan/img/info_auto_icon/clock.svg" alt="clock_icon">
                            </div>
                            <div class="d-flex flex-column align-items-start" style="margin-left: 1.5vh">
                                <div class="start flex flex-column">
                                    <span class="title-time-start title-ts-text">Время начала:</span>
                                    <div class="time d-flex justify-content-between" style="display: flex;">
                                        <span id="start_time_plan" class="text-ts cross-out">28.02.2019 7:00:00</span>
                                        <span id="start_time_fact" class="text-ts blue-color" style="margin-left: 1vh">28.02.2019 7:05:21</span>
                                    </div>
                                </div>
                                <div class="time-end item-ts-data">
                                    <span class="title-time-end title-ts-text">Время окончания:</span>
                                    <span id="end_time_plan" class="title-time-end text-ts">28.02.2019 19:00:00</span>
                                </div>
                                <div class="fuel-time item-ts-data">
                                    <span class="title-fuel-time title-ts-text">Время работы, часов:</span>
                                    <div class="time d-flex">
                                        <span id="work_time_plan" class="fuel-time-start text-ts cross-out">12</span>
                                        <span id="work_time_fact" class="fuel-time-end text-ts blue-color" style="margin-left: 1vh">11.91</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="ts-hr" style="width: 100%;"/>
                    </div>

                    <div class="dada-ts item-ts-data">
                        <div class="row-1 d-flex flex-row item-ts-data">
                            <div class="dada-ts-item d-flex">
                                <div class="icon-ts">
                                    <img src="yan/img/info_auto_icon/speedometer.svg" alt="" style="height: 1.5vh">
                                </div>
                                <div class="d-flex flex-column align-items-start" style="margin-left: 1.5vh">
                                    <span class="title-ts-text">Пробег, км:</span>
                                    <span id="mileage" class="text-ts">275 км</span>
                                </div>
                            </div>
                            <div class="dada-ts-item">
                                <span class="title-ts-text">Средняя скорость, км/ч:</span>
                                <span id="speed" class="text-ts">37 км/ч</span>
                            </div>
                        </div>
                        <div class="row-2 d-flex flex-row item-ts-data">
                            <div class="dada-ts-item d-flex">
                                <div class="icon-ts">
                                    <img src="yan/img/info_auto_icon/gas-station.svg" alt="">
                                </div>
                                <div class="d-flex flex-column align-items-start" style="margin-left: 1.5vh">
                                    <span class="title-ts-text">Топливо по <br />норме, л:</span>
                                    <span id="fuel_norm" class="text-ts">99.55</span>
                                </div>
                            </div>
                            <div class="dada-ts-item">
                                <span class="title-ts-text">Топливо <br />ДУТ, л:</span>
                                <span id="fuel_" class="text-ts">0</span>
                            </div>
                        </div>
                        <div class="row-3 d-flex flex-row item-ts-data">
                            <div class="dada-ts-item d-flex">
                                <div class="icon-ts">
                                    <img src="yan/img/info_auto_icon/star.svg" alt="">
                                </div>
                                <div class="d-flex flex-column align-items-start" style="margin-left: 1.5vh">
                                    <span class="title-ts-text">Количество <br />нарушений:</span>
                                    <span id="violations_count" class="text-ts">6.0</span>
                                </div>
                            </div>
                            <div class="dada-ts-item">
                                <span class="title-ts-text">Оценка качества <br />вождения:</span>
                                <span id="driver_mark" class="text-ts">6.0</span>
                            </div>
                        </div>
                        <hr class="ts-hr"/>
                    </div>

                    <div class="dada-ts-2">
                        <div class="row-1 d-flex flex-row item-ts-data">
                            <div class="dada-ts-item d-flex" style="width:100%">
                                <div class="icon-ts">
                                    <img src="yan/img/info_auto_icon/percentage-discount.svg" alt="">
                                </div>
                                <div class="d-flex flex-column align-items-start ts-car-data" style="margin-left: 1.5vh; width: 170px">
                                    <span class="title-ts-text">Процент рентабельности за прошлый месяц:</span>
                                    <span id="profitability" class="text-ts">50</span>
                                </div>
                            </div>
                        </div>
                        <div class="row-2 d-flex flex-row item-ts-data">
                            <div class="dada-ts-item d-flex">
                                <div class="icon-ts">
                                    <img src="yan/img/info_auto_icon/tools-and-utensils.svg" alt="">
                                </div>
                                <div class="d-flex flex-column align-items-start ts-car-data" style="margin-left: 1.5vh">
                                    <span class="title-ts-text">Осталось км <br /> до ТО:</span>
                                    <span id="technical_inspection_days" class="text-ts">1500</span>
                                </div>
                            </div>
                            <div class="dada-ts-item ts-car-data">
                                <span class="title-ts-text">Дней до замены <br /> аккумулятора:</span>
                                <span id="battery_change_days" class="text-ts">911</span>
                            </div>
                        </div>
                        <div class="row-3 d-flex flex-row item-ts-data">
                            <div class="dada-ts-item d-flex">
                                <div class="icon-ts">
                                    <img src="yan/img/info_auto_icon/car-wheel.svg" alt="">
                                </div>
                                <div class="d-flex flex-column align-items-start ts-car-data" style="margin-left: 1.5vh">
                                    <span class="title-ts-text">Км до замены шин:</span>
                                    <span id="tire_change_days" class="text-ts">241</span>
                                </div>

                            </div>
                            <div class="dada-ts-item ts-car-data">
                                <span class="title-ts-text">Сезонность шин:</span>
                                <span id="tire_season" class="text-ts">Межсезонные</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- SECTION__3__TS__INFORMATION -->
        </div> <!-- SIDE__BAR -->
    </section>
</div>
    <div id="map" style="position:fixed;width: 80%; height: 100%;right:0;"></div>
    <!--SECOND SECTION -->

<input type="hidden" name="currentElement" id="divineInput" value="company" />