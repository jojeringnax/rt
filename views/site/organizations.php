<script>
    ymaps.ready( function() {


        <?php
        $organizations = \app\models\Organization::getActives();
        foreach ($organizations as $organization) {
            $organizationPrettyID = $organization->getIdWithoutNumbers();
            $organizationCars = $organization->getCarsNumberWithStatuses();
        ?>
        var OrgLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="bb"><span class="bb-num-org"><?= $organizationCars['total'] ?></span><span class="bb-name"><?= $organization->getTown() ?></span></div>'
        );
        o_pm = new ymaps.Placemark([<?= $organization->x_pos ?>, <?= $organization->y_pos ?>], {
            iconCaption : '<?= $organization->getTown() ?>',
            //hintContent: '<?= $organization->getTown() ?>'
        }, {
            iconLayout: 'default#imageWithContent',
            iconImageHref: '',
            iconImageSize: [62, 67.5],
            iconContentOffset: [-74, 83],
            iconImageOffset: [-24, -24],
            preset: 'islands#greenDotIconWithCaption',
            iconContentLayout: OrgLayout
        });
        o_array.push(o_pm);
        o_pm.breadcrumps = '<a href="#">' + '<?= 'Филиал '.$organization->getTown() ?>' + '</a>';
        o_pm.events.add('click', function(o) {
            changeInfo(
                <?= $organizationCars['total'] ?>,
                <?= $organizationCars["statuses_count"]["G"] ?>,
                <?= $organizationCars["statuses_count"]["R"] ?>,
                <?= $organizationCars["statuses_count"]["TO"] ?>,
                <?= $organizationCars["inline"] ?>,
                <?= $organizationCars["types"][\app\models\Car::LIGHT] ?>,
                <?= $organizationCars["types"][\app\models\Car::TRUCK] ?>,
                <?= $organizationCars["types"][\app\models\Car::BUS] ?>,
                <?= $organizationCars["types"][\app\models\Car::SPEC] ?>
            );

            $('#info-company').addClass('hide');
            $('#ts-info').addClass('hide');
            $('#info-department').removeClass('hide');
            removeAllDontNeed();

            //ajax request -> ORGANIZATION
            idOfCurrentElement['organizations'] = "<?= $organization->id ?>";

            $.ajax({
                url: "index.php?r=site/get-organization-statistic&organization_id=" + "<?= $organization->id ?>",
                type: 'get',
                success: function(res) {
                    let terminals = JSON.parse(res)['terminals'];
                    let data = JSON.parse(res)['statistic'];

                    let waybills_total = (data['waybills_processed']/data['waybills_total']).toFixed(2);
                    let accidents_total = (data['accidents_guilty']/data['accidents_total']).toFixed(2);
                    let applications_ac = (data['waybills_processed']/data['waybills_total']).toFixed(2);
                    let fuel = (Math.round(data['fuel'])/data['time']).toFixed(2);
                    let WB_M = (Math.round(data['WB_M'])/data['WB_ALL']).toFixed(2);

                    dataLevel['organization'] = {
                        totalCars: <?= $organizationCars['total'] ?>,
                        onReady: <?= $organizationCars["statuses_count"]["G"] ?>,
                        onRep: <?= $organizationCars["statuses_count"]["R"] ?>,
                        onTO: <?= $organizationCars["statuses_count"]["TO"] ?>,
                        onLine:  <?= $organizationCars["inline"] ?>,
                        light: <?= $organizationCars["types"][\app\models\Car::LIGHT] ?>,
                        truck: <?= $organizationCars["types"][\app\models\Car::TRUCK] ?>,
                        bus: <?= $organizationCars["types"][\app\models\Car::BUS] ?>,
                        spec: <?= $organizationCars["types"][\app\models\Car::SPEC] ?>,
                        applications_executed: data['applications_executed'],
                        applications_canceled: data['applications_canceled'],
                        applications_sub: data['applications_sub'],
                        applications_ac: applications_ac,
                        waybills_total: waybills_total,
                        accidents_total: accidents_total,
                        WB_M: WB_M,
                        fuel: fuel ,
                        terminals: terminals
                    };

                    //example of data = {
                    //{"id":null,
                    // "spot_id":null,
                    // "autocolumn_id":null,
                    // "applications_total":289,
                    // "applications_executed":260,
                    // "applications_canceled":11,
                    // "applications_sub":3,
                    // "applications_ac":0,
                    // "applications_mp":0,
                    // "waybills_total":3769,
                    // "waybills_processed":3438,
                    // "accidents_total":0,
                    // "accidents_guilty":0,
                    // "time":91025.12,
                    // "fuel":25134.369999999995,
                    // "WB_M":1988,"WB_ALL":2013}

                    //executed_app
                    applicationAdd('applications_executed',data['applications_executed']);

                    //canceled_app
                    applicationAdd('applications_canceled',data['applications_canceled']);

                    //sub_app
                    applicationAdd('applications_sub',data['applications_sub']);

                    //ac_app
                    circleBar('applications_ac', (data['applications_ac']/data['applications_total']).toFixed(2));

                    //waybills = waybills_processed / waybills_total
                    circleBar('waybills_total', (data['waybills_processed']/data['waybills_total']).toFixed(2));

                    //accidents = accidents_guilty / accidents_total
                    circleBar('accidents_total', (data['accidents_guilty']/data['accidents_total']).toFixed(2));

                    //GetWBMonitoring =  CountM/CountAll
                    circleBar('WB_M', (Math.round(data['WB_M'])/data['WB_ALL']).toFixed(2));

                    // TMCH = fuel/time
                    applicationAdd('fuel', (Math.round(data['fuel'])/data['time']).toFixed(2));

                    //terminals =
                    applicationAdd('terminals', terminals);
                }
            }); //end ajax request

    <?php } ?>


    }); //ymaps.ready()
</script>