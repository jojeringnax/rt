const LongBarColors = {
    oil: "yellow",
    tiers: 'blue',
    akb: 'red',
    to: 'green'
};



function circleBar(name, value) {
    let per = name + "_per";
    if(value === "NaN") {
        value = 0;
    }
    document.getElementById(per).innerHTML = '';
    document.getElementById(per).innerHTML = value*100 + '%';
    document.getElementById(name).innerHTML = '';
    let bar = new ProgressBar.Circle('#' + name, {
        strokeWidth: 12,
        easing: 'easeInOut',
        duration: 1400,
        color: '#27AE60',
        trailColor: '#eee',
        trailWidth: 12,
        svgStyle: null
    });

    bar.animate(value);  // Number from 0.0 to 1.0
}


function circlesCompanyFillFromObject(obj) {

    let values = {
        comp_applications_ac: (obj.applications_ac/obj.applications_total).toFixed(2),
        comp_waybills_total: (obj.waybills_processed/obj.waybills_total).toFixed(2),
        comp_accidents_total: (obj.accidents_guilty/obj.accidents_total).toFixed(2),
        comp_WB_M: (Math.round(obj.WB_M)/obj.WB_ALL).toFixed(2)
    };

    let divCompanyIndicators = $('div#info-company > div#indicators');
    let divsForChange = divCompanyIndicators.children('.item-bar');
    divsForChange.each(function() {
        let cirk = $(this).children('.cirk');
        let p = cirk.children('p');
        let pID = p.attr('id');
        let div = cirk.children('div');
        div.bar = new ProgressBar.Circle('#' + div.attr('id'), {
            strokeWidth: 12,
            easing: 'easeInOut',
            duration: 1400,
            color: '#27AE60',
            trailColor: '#eee',
            trailWidth: 12,
            svgStyle: null
        });
        div.bar.animate(values[div.attr('id')]);
        p.html(values[pID.substr(0, pID.length - 4)]*100 + '%');
    });
}

function longBar(name, value, color) {
    var longbar = new ProgressBar.Line('#'+name, {
      strokeWidth: 5,
      easing: 'easeInOut',
      duration: 1400,
      color: LongBarColors[color],
      trailColor: '#eee',
      trailWidth: 5,
      svgStyle: {'border-radius': '7px'}
    });
    longbar.animate(value);  // Number from 0.0 to 1.0
}

function applicationAdd(id, value) {
    if (value === "" || value === null) {
        value = 'н/д';
    }

    document.getElementById(id).innerHTML = value;
}