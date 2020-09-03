var perms = [];
function list() {

  $.ajax({
    url: base_url + 'api/users', type: 'get', success: function(data) {
      if(data.success) {
        table = '<table class="table table-striped animated fadeIn"><thead><tr><th>Nazwa użytkownika</th><th>Limit botów</th><th>Dwuetapowa autoryzacja</th>';
        if(data.value.perms.edit || data.value.perms.delete) {
          table += '<th>Opcje</th>';
        }
        table += '</tr></thead><tbody>';
        $.each(data.value.list,function(id,val) {
          table += '<tr><td>' + val.username + '</td><td>' + val.limitBots + '</td><td>' + val.twoauth + '</td>';
          button = '';
          if(data.value.perms.delete) {
            button += '<button class="btn btn-dark delete-show" data-toggle="tooltip" data-placement="top" data-username="' + val.username + '" title="Usuń użytkownika"><i class="fas fa-trash"></i></button> ';
          }
          if(data.value.perms.edit) {
            button += '<button class="btn btn-dark edit-user" data-toggle="tooltip" data-placement="top" data-username="' + val.username + '" title="Edytuj użytkownika"><i class="fa fa-pencil"></i></button>';
          }
          if(button != '') {
            table += '<td>' + button + '</td>';
          }
          table += '</tr>';
        });
        table += '</tbody></table>';
        $('.loader').slideUp('normal',function() {
          $(this).remove();
        })
        $('.list').html(table).slideDown('normal');
        perms = data.value.perms;
      } else {
        if(!data.value) {
          helper.alert(false,data.message);
        }
      }
    }
  })
}

setTimeout(function() {
  list();
},1000);

$(document).on('click','.delete-show',function() {
  if(perms.delete) {
    $('.username').html($(this).data('username'));
    $('#deleteUser').modal('show');
  } else {
    helper.alert(false,'Nie posiadasz dostępu!');
  }
});

$(document).on('click','.delete-user',function() {
  if(perms.delete) {
    $.ajax({
      url: base_url + 'api/users/delete', type: 'post', data: {
        login: $('.username').html()
      }, success: function(data) {
        if(data.success) {
          list();
        }
        helper.alert(data.success,data.message);
      }
    });
    $('.username').empty();
    $('#deleteUser').modal('hide');
  } else {
    helper.alert(false,'Nie posiadasz dostępu!');
  }
});

$(document).on('click','.cancel-delete-user',function() {
  helper.alert('info','Anulowano usuwanie użytkownika');
  $('#deleteUser').modal('hide');
  $('.username').empty();
})

$(document).on('click','.edit-user',function() {
  if(perms.edit) {
    window.location.href=base_url + 'users/edit/' + $(this).data('username');
  } else {
    helper.alert(false,'Nie posiadasz dostępu!');
  }
});
