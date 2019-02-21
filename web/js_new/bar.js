LongBarColors = {
    oil: "yellow",
    tiers: 'blue',
    akb: 'red',
    to: 'green'
};

function circleBar(name, value) {
    //console.log(name, value, 'sss');
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
    document.getElementById(id).innerHTML = value;
}