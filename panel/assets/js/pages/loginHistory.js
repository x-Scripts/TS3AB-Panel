
$(document).on('click','.list', function() {
  el = $('.user-' + $(this).data('user'));
  if(el.hasClass('show')) {
    $('.drop-' + $(this).data('user')).removeClass('fa-rotate-90');
    el.removeClass('show').slideUp('normal');
  } else {
    $('.drop').removeClass('fa-rotate-90');
    $('.drop-' + $(this).data('user')).addClass('fa-rotate-90');
    $('.show').slideUp('normal').removeClass('show');
    el.addClass('show').slideDown('normal');
  }
})

function history() {
  $.ajax({
    url: base_url + 'api/loginHistory', type: 'get', success: function(data) {
      if(data.success) {
        table = '<div class="animated fadeIn">';
        if(data.value) {
          table += '<div class="text-center"><h2>Brak historii</h2></div>';
        } else {
          $.each(data.message,function(username,history) {
            table += '<strong class="d-block list" data-user="' + username + '" style="cursor: pointer;"><i class="drop drop-' + username + ' fa fa-chevron-right" style="transition: transform .4s;"></i> ' + username + '</strong>' +
            '<div class="block user-' + username + '" style="display: none;">' +
            '<div class="table-responsive" style="max-height: 500px;"><table class="table table-striped table-hover"><thead><tr><th>Data</th><th>Przeglądarka/Wersja</th><th>IP</th></tr></thead><tbody>';
            $.each(history,function(id,val) {
              table += '<tr><td>' + val.date + '</td><td>' + val.browser + '</td><td>' + val.ip + '</td></tr>'
            });
            table += '</tbody></table></div></div>';
          });
        }
        table += '</div>';
        $('.history').html(table);
      } else {
        helper.alert(false,data.message);
      }
    }
  })
}

$('.clear-history-all').click(function() {
  $.ajax({
    url: base_url + 'api/loginHistory/clearAll', type: 'get', success: function(data) {
      if(data.success) {
        $('.history').html('<div class="animated fadeIn"><div class="text-center"><h2>Brak historii</h2></div></div>');
      }
      helper.alert(data.success,data.message);
    }
  })
})
$('.clear-history-own').click(function() {
  $.ajax({
    url: base_url + 'api/loginHistory/clearOwn', type: 'get', success: function(data) {
      if(data.success) {
        history();
      }
      helper.alert(data.success,data.message);
    }
  })
})

history();
