var chartcpu = '';
var chartram = '';
var chartcpuServer = '';
var chartramServer = '';
var chartdiskUsage = '';

function usage() {
  $.ajax({
    url: base_url + 'api/usage', type: 'get', success: function(data) {
      if(data.success) {
        if(chartram != '' && chartcpu != '' && chartcpuServer != '' && chartramServer != '' && chartdiskUsage != '') {
          $.each(data.value,function(id,val) {
            eval("chart" + id + ".data.datasets[0].data=[\"" + val.usage[0] + "\",\"" + val.usage[1] + "\"];");
            eval("chart" + id + ".update();");
          });
        } else {
          $.each(data.value,function(id,val) {
            eval("chart" + id + " = new Chart('" + id + "', {type: 'doughnut',data: {datasets: [{data: [\"" + val.usage[0] + "\",\"" + val.usage[1] + "\"],backgroundColor: [\"" + val.color[0] + "\", \"#6c757d\"],borderColor: \"#2d3035\",hoverBackgroundColor: [\"" + val.color[1] + "\", \"#8d9194\"]}],labels: ['Zajęte','Wolne'],},options: {legend: {display: false},tooltips: {enabled: true,mode: 'single',callbacks: {label: function(items, data) {return data.labels[items.index] + ': ' + data.datasets[0].data[items.index] + (helper.inArray('" + id + "',['cpu','cpuServer']) ? '%' : 'MB');}}}}})");
          })
          $('.loader').slideUp(500,function() {
            $(this).remove();
            $('.show').removeAttr('style').addClass('animated fadeIn');
          })
        }
        console.log(data.value);
      } else {
        helper.alert(false,data.message);
      }
    }
  })
}

usage();

setTimeout(function() {
  usage();
},1000*60);
