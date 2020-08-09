(function (global) {
  "use strict";
  Apex.colors = [theme.color.primary, theme.color.success, theme.color.info, theme.color.warning, theme.color.danger];

  var init = function(){
  	var backend     = "http://localhost/employees/employee/public/api/employees/";
  	var to_paid     = backend+"top_paid_employees?page=1&limit=5";
  	var avg_by_age  = backend+"employees_salary_by_age?page=1&limit=5";

  	/********* get top paid employees*/
      var top_paid_employees_names=[];
      var top_paid_employees_salaries=[];

      $.ajax({url: to_paid, success: function(result){

              result.data.forEach(function(item,index) {

                  top_paid_employees_names.push(item['first_name']);
                  top_paid_employees_salaries.push(item['salary']);
              });
          }});
    // column
	var Top_paid_options = {
        chart: {
            height: 240,
            type: 'bar',
            toolbar: {
                show: false
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: [ {
            name: 'Revenue',
            data: top_paid_employees_salaries
        }],
        xaxis: {
            categories: top_paid_employees_names,
        },
        yaxis: {
            title: {
                text: '$ (thousands)'
            }
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$ " + val + " thousands"
                }
            }
        }
    }

      var chart = new ApexCharts(
          document.querySelector("#Top-paid-chart"),
          Top_paid_options
      );
      // column
      var Top_paid_options = {
          chart: {
              height: 240,
              type: 'bar',
              toolbar: {
                  show: false
              },
          },
          plotOptions: {
              bar: {
                  horizontal: false,
                  columnWidth: '55%',
                  endingShape: 'rounded'
              },
          },
          dataLabels: {
              enabled: false
          },
          stroke: {
              show: true,
              width: 2,
              colors: ['transparent']
          },
          series: [ {
              name: 'Revenue',
              data: top_paid_employees_salaries
          }],
          xaxis: {
              categories: top_paid_employees_names,
          },
          yaxis: {
              title: {
                  text: '$ (thousands)'
              }
          },
          fill: {
              opacity: 1

          },
          tooltip: {
              y: {
                  formatter: function (val) {
                      return "$ " + val + " thousands"
                  }
              }
          }
      }
      chart.render();


      /********* get averge paid by age*/
      var employees_ages=[];
      var employees_salaries=[];

      $.ajax({url: avg_by_age, success: function(result){

              result.data.forEach(function(item,index) {
                  employees_ages.push(item['age']);
                  employees_salaries.push(item['salary']);
              });
          }});

      // column
      var avg_age_options = {
          chart: {
              height: 240,
              type: 'bar',
              toolbar: {
                  show: false
              },
          },
          plotOptions: {
              bar: {
                  horizontal: false,
                  columnWidth: '55%',
                  endingShape: 'rounded'
              },
          },
          dataLabels: {
              enabled: false
          },
          stroke: {
              show: true,
              width: 2,
              colors: ['transparent']
          },
          series: [ {
              name: 'Revenue',
              data: employees_salaries
          }],
          xaxis: {
              categories: employees_ages,
          },
          yaxis: {
              title: {
                  text: '$ (thousands)'
              }
          },
          fill: {
              opacity: 1

          },
          tooltip: {
              y: {
                  formatter: function (val) {
                      return "$ " + val + " thousands"
                  }
              }
          }
      }
      var chart = new ApexCharts(
          document.querySelector("#age-chart"),
          avg_age_options
      );

      chart.render();



  }

  global.apexcharts = {init: init};

})(this);
