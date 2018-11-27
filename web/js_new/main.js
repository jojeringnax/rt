
var bar1 = new ProgressBar.Circle('#circle1', {
  strokeWidth: 12,
  easing: 'easeInOut',
  duration: 1400,
  color: '#27AE60',
  trailColor: '#eee',
  trailWidth: 12,
  svgStyle: null
});

bar1.animate(0.6);  // Number from 0.0 to 1.0

var bar2 = new ProgressBar.Circle('#circle2', {
  strokeWidth: 12,
  easing: 'easeInOut',
  duration: 1400,
  color: '#27AE60',
  trailColor: '#eee',
  trailWidth: 12,
  svgStyle: null
});

bar2.animate(0.3);  // Number from 0.0 to 1.0

var bar3 = new ProgressBar.Circle('#circle3', {
  strokeWidth: 12,
  easing: 'easeInOut',
  duration: 1400,
  color: '#27AE60',
  trailColor: '#eee',
  trailWidth: 12,
  svgStyle: null
});

bar3.animate(0.9);  // Number from 0.0 to 1.0

var bar4 = new ProgressBar.Circle('#circle4', {
  strokeWidth: 12,
  easing: 'easeInOut',
  duration: 1400,
  color: '#27AE60',
  trailColor: '#eee',
  trailWidth: 12,
  svgStyle: null
});
//
bar4.animate(1.0);  // Number from 0.0 to 1.0

var bar5 = new ProgressBar.Circle('#circle5', {
  strokeWidth: 12,
  easing: 'easeInOut',
  duration: 1400,
  color: '#27AE60',
  trailColor: '#eee',
  trailWidth: 12,
  svgStyle: null
});

bar5.animate(1.0);  // Number from 0.0 to 1.0

function dBar(obj) {
  var div;
  for (key in obj) {
    for (key in obj) {

    }
  }
}
//
// //longbar
//
// var longbar1 = new ProgressBar.Line('#oilLongbar', {
//   strokeWidth: 5,
//   easing: 'easeInOut',
//   duration: 1400,
//   color: '#27AE60',
//   trailColor: '#eee',
//   trailWidth: 5,
//   svgStyle: {'border-radius': '7px'}
// });
//
// // longbar1.animate(0.6);  // Number from 0.0 to 1.0
//
// var longbar2 = new ProgressBar.Line('#wheelLongbar', {
//   strokeWidth: 5,
//   easing: 'easeInOut',
//   duration: 1400,
//   color: 'yellow',
//   trailColor: '#eee',
//   trailWidth: 5,
//   svgStyle: {'border-radius': '7px'}
// });
//
// // longbar2.animate(0.3);  // Number from 0.0 to 1.0
//
// var longbar3 = new ProgressBar.Line('#totalLongbar', {
//   strokeWidth: 5,
//   easing: 'easeInOut',
//   duration: 1400,
//   color: 'red',
//   trailColor: '#eee',
//   trailWidth: 5,
//   svgStyle: {'border-radius': '7px'}
// });
//
// // longbar3.animate(0.9);  // Number from 0.0 to 1.0
//
// var longbar4 = new ProgressBar.Line('#repairLongbar', {
//   strokeWidth: 5,
//   easing: 'easeInOut',
//   duration: 1400,
//   color: 'blue',
//   trailColor: '#eee',
//   trailWidth: 5,
//   svgStyle: {'border-radius': '7px'}
// });

// longbar4.animate(1.0);  // Number from 0.0 to 1.0

function $(e) {
  return document.querySelector(e);
};

// console.log($('.result'));
// let key = 'result';
// console.log('hey',$('.'+key));

//nameCompany:Компания, compAmOfTs: 123, compOnLine: 343, compOnRep:123, compOnTo:1123, compReqDone: 123, compReqCans :123,compReqTransf:111,cbp1,cbp2,cbp3,cbp4
function newInfoCompany(obj) {
  for (key in obj) {
    if (key =="cpb1") {
      var bar1 = new ProgressBar.Circle('#'+key, {
        strokeWidth: 12,
        easing: 'easeInOut',
        duration: 1400,
        color: '#27AE60',
        trailColor: '#eee',
        trailWidth: 12,
        svgStyle: null
      });
      bar1.animate(obj[key]);
    } else if(key =="cpb2") {
      var bar2 = new ProgressBar.Circle('#'+key, {
        strokeWidth: 12,
        easing: 'easeInOut',
        duration: 1400,
        color: '#27AE60',
        trailColor: '#eee',
        trailWidth: 12,
        svgStyle: null
      });
      bar2.animate(obj[key]);
    } else if(key =="cpb3") {
      var bar3 = new ProgressBar.Circle('#'+key, {
        strokeWidth: 12,
        easing: 'easeInOut',
        duration: 1400,
        color: '#27AE60',
        trailColor: '#eee',
        trailWidth: 12,
        svgStyle: null
      });
      bar3.animate(obj[key]);
    } else if(key =="cpb4") {
      var bar4 = new ProgressBar.Circle('#'+key, {
        strokeWidth: 12,
        easing: 'easeInOut',
        duration: 1400,
        color: '#27AE60',
        trailColor: '#eee',
        trailWidth: 12,
        svgStyle: null
      });
      bar4.animate(obj[key]);
    } else if(key =="cpb5") {
      var bar5 = new ProgressBar.Circle('#'+key, {
        strokeWidth: 12,
        easing: 'easeInOut',
        duration: 1400,
        color: '#27AE60',
        trailColor: '#eee',
        trailWidth: 12,
        svgStyle: null
      });
      bar4.animate(obj[key]);
    } else {
      $('#'+key).innerHTML(obj[key]);
    }
  }
}

//nameofDST: Филиал(Автоколонна, участок,ТС); totTs: 123; OnLine: 232; OnRep:123; OnTo:987; passCar:123; freightCar:123; busCar:123; specCar:766; ReqDone:87; ReqCans:123; ReqTransf:123; bps1:0.2; bps2:0.; ,bps3:1.0; bps4:0.5

function newInfoDASTs(obj) {
  for (key in obj) {
    if (key === 'spb') {
      for (bar in obj[key]) {
          if (bar == "spb1") {
              let bar1 = new ProgressBar.Circle('#' + bar, {
                  strokeWidth: 12,
                  easing: 'easeInOut',
                  duration: 1400,
                  color: '#27AE60',
                  trailColor: '#eee',
                  trailWidth: 12,
                  svgStyle: null
              });
              bar1.animate(obj[key][bar]);
          } else if (bar == "spb2") {
              let bar2 = new ProgressBar.Circle('#' + bar, {
                  strokeWidth: 12,
                  easing: 'easeInOut',
                  duration: 1400,
                  color: '#27AE60',
                  trailColor: '#eee',
                  trailWidth: 12,
                  svgStyle: null
              });
              bar2.animate(obj[key][bar]);
          } else if (bar == "spb3") {
              let bar3 = new ProgressBar.Circle('#' + bar, {
                  strokeWidth: 12,
                  easing: 'easeInOut',
                  duration: 1400,
                  color: '#27AE60',
                  trailColor: '#eee',
                  trailWidth: 12,
                  svgStyle: null
              });
              bar3.animate(obj[key][bar]);
          } else if (bar == "spb4") {
              let bar4 = new ProgressBar.Circle('#' + bar, {
                  strokeWidth: 12,
                  easing: 'easeInOut',
                  duration: 1400,
                  color: '#27AE60',
                  trailColor: '#eee',
                  trailWidth: 12,
                  svgStyle: null
              });
              bar4.animate(obj[key][bar]);
          } else if (bar == "spb5") {
              let bar5 = new ProgressBar.Circle('#' + bar, {
                  strokeWidth: 12,
                  easing: 'easeInOut',
                  duration: 1400,
                  color: '#27AE60',
                  trailColor: '#eee',
                  trailWidth: 12,
                  svgStyle: null
              });
              bar5.animate(obj[key][bar]);
          } else {
              $('#' + bar).innerHTML(obj[key][bar]);
          }
      }
    }
  }
}

//nameTS:"BMW "; oilChangeDist: 23 000 км; tireChangeDist: 43 000 км; accChangeDist: 34 ч; toChangeDist: 456722 км; lb1: 0.1; lb2:0.4; lb3: 0.7; lb4: 0.9;

  function newInfoTs(obj) {
    var div;
    for (key in obj) {
      div = $('div#'+key);
      if (key == "lb1") {
        if(div.children.length === 0) {
            var longbar1 = new ProgressBar.Line('#' + key, {
                strokeWidth: 5,
                easing: 'easeInOut',
                duration: 1400,
                color: '#27AE60',
                trailColor: '#eee',
                trailWidth: 5,
                svgStyle: {'border-radius': '7px'}
            });
            longbar1.animate(obj[key]);
            div.longbar = longbar1;
        } else {
            div.longbar.animate(obj[key]);
        }
      } else if(key == "lb2") {
          if (div.children.length === 0) {
              var longbar2 = new ProgressBar.Line('#' + key, {
                  strokeWidth: 5,
                  easing: 'easeInOut',
                  duration: 1400,
                  color: 'yellow',
                  trailColor: '#eee',
                  trailWidth: 5,
                  svgStyle: {'border-radius': '7px'}
              });
              longbar2.animate(obj[key]);
              div.longbar = longbar2;
          } else {
              div.longbar.animate(obj[key]);
          }
      } else if(key == "lb3") {
          if (div.children.length === 0) {
              var longbar3 = new ProgressBar.Line('#' + key, {
                  strokeWidth: 5,
                  easing: 'easeInOut',
                  duration: 1400,
                  color: 'red',
                  trailColor: '#eee',
                  trailWidth: 5,
                  svgStyle: {'border-radius': '7px'}
              });
              longbar3.animate(obj[key]);
              div.longbar = longbar3;
          } else {
              div.longbar.animate(obj[key]);
          }
      } else if(key == "lb4") {
          if (div.children.length === 0) {
              var longbar4 = new ProgressBar.Line('#'+key, {
                  strokeWidth: 5,
                  easing: 'easeInOut',
                  duration: 1400,
                  color: 'blue',
                  trailColor: '#eee',
                  trailWidth: 5,
                  svgStyle: {'border-radius': '7px'}
              });
              longbar4.animate(obj[key]);
              div.longbar = longbar4;
          } else {
              div.longbar.animate(obj[key]);
          }
      } else {
          $('#'+key).innerHTML = obj[key];
      }
    }
  }
