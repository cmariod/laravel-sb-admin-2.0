var cline = document.getElementById("cline");
if (cline !== null) {
    new Chart(cline, {
        "type": "line",
        "data": {
            "labels": ["January","February","March","April","May","June","July"],
            "datasets": [{
                "label": "My First Dataset",
                "data": [65,59,80,81,56,55,40],
                "fill": false,
                "borderColor": "rgb(75, 192, 192)",
                "lineTension": 0.1
            }]
        },
        "options": {
            "responsive": true
        }
    });
}

var cpie = document.getElementById("cpie");
if (cpie !== null) {
    new Chart(cpie, {
        "type": "pie",
        "data": {
            "labels": ["Red","Blue","Yellow"],
            "datasets": [{
                "label": "My First Dataset",
                "data": [300,50,100],
                "backgroundColor": ["rgb(255, 99, 132)","rgb(54, 162, 235)","rgb(255, 205, 86)"]
            }]
        },
        "options": {
            "responsive": true
        }
    });
}

var cdonut = document.getElementById("cdonut");
if (cdonut !== null) {
    new Chart(cdonut, {
        "type": "doughnut",
        "data": {
            "labels": ["Red","Blue","Yellow"],
            "datasets": [{
                "label": "My First Dataset",
                "data": [300,50,100],
                "backgroundColor": ["rgb(255, 99, 132)","rgb(54, 162, 235)","rgb(255, 205, 86)"]
            }],
            "cutoutPercentage": 33
        },
        "options": {
            "responsive": true
        }
    });
}

var cbar = document.getElementById("cbar");
if (cbar !== null) {
    new Chart(cbar, {
        "type": "bar",
        "data": {
            "labels": ["January","February","March","April","May","June","July"],
            "datasets": [{
                "label": "My First Dataset",
                "data": [65,59,80,81,56,55,40],
                "fill": false,
                "backgroundColor": ["rgba(255, 99, 132, 0.2)","rgba(255, 159, 64, 0.2)","rgba(255, 205, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(54, 162, 235, 0.2)","rgba(153, 102, 255, 0.2)","rgba(201, 203, 207, 0.2)"],
                "borderColor": ["rgb(255, 99, 132)","rgb(255, 159, 64)","rgb(255, 205, 86)","rgb(75, 192, 192)","rgb(54, 162, 235)","rgb(153, 102, 255)","rgb(201, 203, 207)"],
                "borderWidth": 1
            }]
        },
        "options": {
            "scales": {
                "yAxes": [{
                    "ticks": {
                        "beginAtZero": true
                    }
                }]
            },
            "responsive": true
        }
    });
}

$(document).ready(function() {
  
  $('input[name="daterange"]').daterangepicker({
      "showDropdowns": true,
      "autoApply": true,
      locale: {
        format: 'YYYY-MM-DD'
      },
      "ranges": {
          Today: [moment(), moment()],
          Yesterday: [moment().subtract("days", 1), moment().subtract("days", 1)],
          "Last 7 Days": [moment().subtract("days", 6), moment()],
          "Last 30 Days": [moment().subtract("days", 29), moment()],
          "This Month": [moment().startOf("month"), moment().endOf("month")],
          "Last Month": [moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")]
      },
      "startDate": moment().subtract("month", 1).startOf("month"),
      "endDate": moment().subtract("month", 1).endOf("month"),
      "minDate": "2017/05/04",
      "maxDate": "2017/12/31",
      "opens": "center"
  }, function(start, end, label) {
    // console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
  });
  
});