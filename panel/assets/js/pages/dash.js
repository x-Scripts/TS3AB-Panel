 /**
  * Information
  * @Author: xares
  * @Date:   19-05-2020 13:32
  * @Filename: dash.js
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 01-06-2020 13:35
  *
  * @Copyright(C) 2020 x-Scripts
  */

var first = 1;
var refresh = '';
var twosec = 0;
var perms = [];
var users;
function dash() {
  $.ajax({
    url: base_url + "api/dash", type: 'get', success: function(data) {
      if(data.success) {
        if(data.value == 'list') {
          tr = '';
          perms = data.message.perms;
          $.each(data.message.list,function (id,val) {
            if(first) {
              tr += '<tr class="animated fadeIn">';
            } else {
              tr += '<tr>';
            }
            tr += '<th scope="row">' + (id+1) + '</th>';
            tr += '<td>' + val.name + '</td>';
            tr += '<td>' + val.server + '</td>';
            if(val.status == 0) {
              tr += '<td><button class="btn btn-danger">Wyłączony</button></td>';
              buttonOnOff = '<button class="btn btn-dark start-bot" data-id="' + val.id + '" data-toggle="tooltip" data-placement="top" title="Włącz bota"><i class="fa fa-power-off power-on"></i></button>';
            } else if(val.status == 1) {
              if(twosec == 0) {
                twosec = 1;
              }
              tr += '<td><button class="btn btn-warning">Włączanie</button></td>';
              buttonOnOff = '<button class="btn btn-dark stop-bot" data-id="' + val.id + '" data-toggle="tooltip" data-placement="top" title="Wyłącz bota"><i class="fa fa-power-off power-off"></i></button>';
            } else {
              tr += '<td><button class="btn btn-success">Włączony</button></td>';
              buttonOnOff = '<button class="btn btn-dark stop-bot" data-id="' + val.id + '" data-toggle="tooltip" data-placement="top" title="Wyłącz bota"><i class="fa fa-power-off power-off"></i></button>';
            }
            button = '';
            if(data.message.perms.player) {
              button += '<button class="btn btn-dark player-bot" data-id="' + val.id + '" data-toggle="tooltip" data-placement="top" title="Odtwarzacz bota"' + (val.status == 2 ? '' : ' disabled') + '><i class="fa fa-play-circle"></i></button> ';
            }
            if(data.message.perms.editBots) {
              button += '<button class="btn btn-dark edit-bot" data-id="' + val.id + '" data-toggle="tooltip" data-placement="top" title="Ustawienia bota"><i class="fa fa-pencil"></i></button> ';
            }
            if(data.message.perms.deleteBots) {
              button += '<button class="btn btn-dark show-deleteBot" data-id="' + val.id + '" data-name="' + val.name + '" data-toggle="tooltip" data-placement="top" title="Usuń bota"><i class="fa fa-trash-o"></i></button> ';
            }
            if(data.message.perms.addBotUsers) {
              button += '<button class="btn btn-dark show-addBotUsers" data-users="' + val.users + '" data-id="' + val.id + '" data-name="' + val.name + '" data-toggle="tooltip" data-placement="top" title="Przypisywanie bota uzytkownikom"><i class="fa fa-user"></i></button> ';
            }
            tr += '<td>' + button + buttonOnOff + '</td>'
            tr += '</tr>';
          });

          if(first) {
            users = data.message.users;
            usersList();
            first = 0;
          }
          if(twosec) {
            clearInterval(refresh);
            refresh = '';
            setTimeout(function() {
              dash();
            },2000);
            twosec = 0;
          } else if(refresh == '') {
            refresh = setInterval(function() { dash(); },1000*60*2);
          }
          $('button[data-toggle=tooltip]').tooltip('hide');
          $('.list').html('<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>#</th><th>Nazwa bota</th><th>Serwer</th><th>Status</th><th>Opcje</th></tr></thead><tbody>' + tr + '</tbody></table></div>');
          $('button[data-toggle=tooltip]').tooltip();
        } else {
          if(!first) {
            first = 1;
          }
          $('.list').html('<div class="animated fadeIn text-center">' + data.message + '</div>');
        }
      } else {
        helper.alert(false,data.message);
      }
    }
  });
}
function usersList() {
  checkbox = '';
  $.each(users,function(id,val) {
    checkbox += '<div class="i-checks"><input type="checkbox" class="checkbox-template account account-' + val + '" data-user="' + val + '"><label for="checkboxCustom1">' + val + '</label></div>'
  })
  $('.account-list').html(checkbox);
}
dash();
refresh = setInterval(function() { dash(); },1000*60*2);

$(document).on('click','.stop-bot',function() {
  $.ajax({
    url: base_url + 'api/bot/stop', type: 'POST', data: {
      botID: $(this).data('id')
    }, success: function(data) {
      if(data.value) {
        clearInterval(refresh);
        refresh = setInterval(function() { dash(); },1000*60*2);
        dash();
      }
      helper.alert(data.success,data.message);
    }
  })
})
$(document).on('click','.start-bot',function() {
  $.ajax({
    url: base_url + 'api/bot/start', type: 'POST', data: {
      botID: $(this).data('id')
    }, success: function(data) {
      if(data.value) {
        clearInterval(refresh);
        refresh = setInterval(function() { dash(); },1000*60*2);
        dash();
      }
      helper.alert(data.success,data.message);
    }
  })
})

$(document).on('click','.edit-bot',function() {
  if(!perms.editBots) {
    helper.alert(false,'Nie posiadasz dostępu!');
    return;
  }
  window.location.href=base_url + 'edit/' + $(this).data('id');
})

$(document).on('click','.show-deleteBot', function() {
  if(!perms.deleteBots) {
    helper.alert(false,'Nie posiadasz dostępu!');
    return;
  }
  $('.botID').html($(this).data('name'));
  $('.delete-bot').val($(this).data('id'));
  $('#deleteBot').modal('show');
})

$(document).on('click','.show-addBotUsers', function() {
  if(!perms.addBotUsers) {
    helper.alert(false,'Nie posiadasz dostępu!');
    return;
  }
  $.each($(this).data('users').split(','),function(id,val) {
    $('.account-' + val).prop('checked',true);
  })
  $('.bot-name').html($(this).data('name'));
  $('.edit-botUsers').val($(this).data('id'));
  $('#addBotUsers').modal('show');
})

$(document).on('click','.cancel-addBotUsers', function() {
  if(!perms.addBotUsers) {
    helper.alert(false,'Nie posiadasz dostępu!');
    return;
  }
  $('#addBotUsers').modal('hide');
})
$('#addBotUsers').on('hidden.bs.modal', function (e) {
  $('.bot-name').empty();
  $('.edit-botUsers').removeAttr('value');
  $('.account').each(function() {
    if($(this).is(':checked')) {
      $(this).prop('checked',false);
    }
  })
});

$(document).on('click','.cancel-deleteBot',function() {
  if(!perms.deleteBots) {
    helper.alert(false,'Nie posiadasz dostępu!');
    return;
  }
  $('#deleteBot').modal('hide');
})
$('#deleteBot').on('hidden.bs.modal', function (e) {
  $('.botID').empty();
  $('.delete-bot').removeAttr('value');
});

$(document).on('click','.delete-bot', function() {
  if(!perms.deleteBots) {
    helper.alert(false,'Nie posiadasz dostępu!');
    return;
  }
  id = $(this).val();
  $.ajax({
    url: base_url + 'api/bot/delete', type: 'post', data: {
      botID: id
    }, success: function(data) {
      $('#deleteBot').modal('hide').on('hidden.bs.modal', function (e) {
        $('.botID').empty();
        $('.delete-bot').removeAttr('value');
      });
      helper.alert(data.success,data.message);
      if(data.success) {
        delRights(id);
        clearInterval(refresh);
        refresh = setInterval(function() { dash(); },1000*60*2);
        dash();
      }
    }
  })
})
$(document).on('click','.player-bot',function() {
  if(!perms.player) {
    helper.alert(false,'Nie posiadasz dostępu!');
    return;
  }
  window.location.href=base_url + 'player/' + $(this).data('id');
})

function delRights(botID) {
  $.ajax({
    url: base_url + 'api/rights/delete', type: 'post', data: {
      botID: botID
    }, success: function(data) {
        helper.alert(data.success,data.message);
    }
  })
}

$('.app-start').click(function() {
  $.ajax({
    url: base_url + 'api/app/start', type: 'get', success: function(data) {
      helper.alert(data.success,data.message);
    }
  })
})

$('.app-stop').click(function() {
  $.ajax({
    url: base_url + 'api/app/stop', type: 'get', success: function(data) {
      helper.alert(data.success,data.message);
    }
  })
})

$('.app-restart').click(function() {
  $.ajax({
    url: base_url + 'api/app/restart', type: 'get', success: function(data) {
      helper.alert(data.success,data.message);
    }
  })
})

$('.edit-botUsers').click(function() {
  userEdit = [];
  $('.account').each(function() {
    if($(this).is(':checked')) {
      userEdit.push($(this).data('user'));
    }
  });
  $.ajax({
    url: base_url + 'api/bot/editUsers', type: 'post', data: {
      botID: $(this).val(),
      users: userEdit
    }, success: function(data) {
      $('#addBotUsers').modal('hide');
      if(data.success) {
        clearInterval(refresh);
        refresh = setInterval(function() { dash(); },1000*60*2);
        dash();
      }
      helper.alert(data.success,data.message);
    }
  })
})
