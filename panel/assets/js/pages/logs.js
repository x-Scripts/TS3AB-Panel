 /**
  * Information
  * @Author: xares
  * @Date:   30-04-2020 01:49
  * @Filename: logs.js
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 11:09
  *
  * @Copyright(C) 2020 x-Scripts
  */

var cache = '';
var timeout;
$(document).on('click','.load-logs',function() {
  loadingLogs();
});
$(document).on('click','.sidebar-toggle',function() {
  if(cache != '') {
    page = $('.page-content');
    page.removeAttr('style');
    $('.show-log-list').removeClass('animated fadeIn').empty();
    setTimeout(function() {
      page.css({"width": page.width()});
      $('.show-log-list').html(cache).addClass('animated fadeIn');
    },450);
  }
})
$(document).on('click','.clear-show',function() {
  if(cache != '') {
    $('.show-log-list, .date').slideUp('normal',function() {
      $(this).empty();
      cache = '';
      $('.page-content').removeAttr('style');
    });
  }
});

$('.select-logs').change(function() {
  loadingLogs();
});
var del = true;
function loadingLogs() {
  date = $('.select-logs').val();
  if(date == "") {
    helper.alert(false,'Wybierz odpowiedni dzień');
    return;
  }
  $('.show-log-list').slideUp('normal',function() {
    $(this).removeClass('animated fadeIn').empty();
    $('.page-content').removeAttr('style');
  });
  $('.date').html('<h3>Logi z dnia: ' + $('option[value=' + date + ']').html() + '</h3>').slideDown('normal');
  $('.loading').html('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>').slideDown('normal');
  clearTimeout(timeout);
  del = false;
  helper.alert('info','Ładowanie logów',1000);
  timeout = setTimeout(function() {
    $.ajax({
      url: base_url + 'api/logs', type: 'post', data: {
        date: date
      }, success: function(data) {
        if(data.success) {
          cache = data.message;
          del = true;
          page = $('.page-content');
          page.removeAttr('style').css({"width": page.width()});
          $('.loading').slideUp('normal',function() {
            $(this).empty();
          });
          $('.show-log-list').html(data.message).addClass('animated fadeIn').slideDown('normal');
        } else {
          helper.alert(false,data.message);
        }
      }
    });
  },1000);
}

$('.delete-log').click(function() {
  if(!del) {
    helper.alert('info','Aktualnie ładują się logi nie można ich usunąć');
    return;
  }
  date = $('.select-logs').val();
  if(date == "") {
    helper.alert(false,'Wybierz odpowiedni dzień');
    return;
  }
  $.ajax({
    url: base_url + 'api/logs/delete', type: 'post', data: {
      date: date
    }, success: function(data) {
      if(data.success) {
        $('option[value="' + date + '"]').remove();
        if(cache != '') {
          $('.show-log-list, .date').slideUp('normal',function() {
            $(this).empty();
            cache = '';
            $('.page-content').removeAttr('style');
          });
        }
      }
      helper.alert(data.success,data.message);
    }
  })
})
