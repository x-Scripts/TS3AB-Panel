 /**
  * Information
  * @Author: xares
  * @Date:   11-05-2020 20:55
  * @Filename: edit.js
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 24-05-2020 22:38
  *
  * @Copyright(C) 2020 x-Scripts
  */

/**
 * Select create and manage limit bots
 *
 */
$(document).on('change','.select-limit',function() {
  if($(this).val() == 'unlimited') {
    $('.limit-bots-show').slideUp('normal');
  } else {
    $('.limit-bots-show').slideDown('normal');
  }
});

/**
 * Select rights bots
 *
 * @return {[type]} [description]
 */
$(document).on('change','.select-rights',function() {
  if($(this).val() == 'all') {
    $('.assign-rights').slideUp('normal');
  } else {
    $('.assign-rights').slideDown('normal');
  }
});

/**
 * Show password user
 *
 */
$(document).on('click','.show-password',function() {
  pass = $('.password');
  $(this).toggleClass("fa-eye fa-eye-slash");
  if(pass.attr('type') == 'password') {
    pass.attr('type','text');
  } else {
    pass.attr('type','password');
  }
})

/**
 * Checked sub rights
 *
 */
$(document).on('click','.check-sub',function() {
  if(!$(this).is(':checked')) {
    $('.' + $(this).data('sub')).prop('checked',false).removeAttr('checked');
  } else {
    $('.' + $(this).data('sub')).prop('checked',true).attr('checked',true);
  }
});

/**
 * Loading value edit users
 *
 */
function edit() {
  $.ajax({
    url: base_url + 'api/users/edit', type: 'post', data: {
      userID: userID
    }, success: function(data) {
      if(data.success) {
        if(data.value.rights != null) {
          if(data.value.rights != 'all') {
            $('option[value=assign]').prop('selected',true);
            $('.assign-rights').slideDown('normal');
            $.each(data.value.rights,function(id,val) {
              $('input[data-name="' + val + '"]').prop('checked',true);
            });
          } else {
            $('option[value=all]').prop('selected',true);
          }
        }
        if(data.value.limitBots != null) {
          if(data.value.limitBots != 0) {
            $('option[value=limited]').prop('selected',true);
            $('.limit-bots-show').slideDown('normal');
            $('.limit-bots').val(data.value.limitBots);
          } else {
            $('option[value=unlimited]').prop('selected',true);
          }
        }
        if(data.value.permsDash != null) {
          $.each(data.value.permsDash,function(id,val) {
            $('input[value=' + id + ']').prop('checked',parseInt(val));
          })
        }

        if(data.value.twoauth != null) {
          $('.twoAuth-status').html('włączony');
          $('.twoAuth-buttons').html('<div class="btn-group btn-group-toggle"><button class="btn btn-dark" data-toggle="modal" data-target="#deleteTwoAuth">Usuń weryfikację</button><button class="btn btn-dark" data-toggle="modal" data-target="#showTwoAuth">Pokaż klucz</button></div>');
          $('.qrTwoAuth').attr('src',data.value.twoauth.img);
          $('.keyTwoAuth').html(data.value.twoauth.key);
        } else {
          $('.twoAuth-status').html('wyłączony');
          $('.twoAuth-buttons').html('<button class="btn btn-dark show-addTwoAuth">Dodaj weryfikację</button>');
        }

        $('.loader').slideUp('normal',function() {
          $('.show').slideDown('normal');
          $(this).remove();
        });
        if(data.value.username != null) {
          $('.login').val(data.value.username);
        }
      } else {
        helper.alert(false,data.message);
      }
    }
  })
}

setTimeout(function() {
  edit();
},1000);

/**
 * Show modal add two Authentication
 *
 * @return {[type]} [description]
 */
$(document).on('click','.show-addTwoAuth', function() {
  $.ajax({
    url: base_url + 'api/users/edit/generateTwoAuth', type: 'post', data: {
      userID: userID
    }, success: function(data) {
      if(data.success) {
        $('.qrAddTwoAuth').attr('src',data.value.img);
        $('.keyAddTwoAuth').html(data.value.key);
        $('#addTwoAuth').modal('show');
      } else {
        helper.alert(false,data.message);
      }
    }
  })
})

/**
 * Add Two Authentication
 *
 */
$(document).on('click','.add-twoAuth',function() {
  $.ajax({
    url: base_url + 'api/users/edit/verifyTwoAuth', type: 'post', data: {
      userID: userID,
      secretKey: $('.keyAddTwoAuth').html(),
      tokenAuth: $('.tokenAddTwoAuth').val()
    }, success: function(data) {
      if(data.success) {
        helper.alert(true,data.message);
        $('.twoAuth-status').html('włączony');
        $('.twoAuth-buttons').html('<div class="btn-group btn-group-toggle"><button class="btn btn-dark" data-toggle="modal" data-target="#deleteTwoAuth">Usuń weryfikację</button><button class="btn btn-dark" data-toggle="modal" data-target="#showTwoAuth">Pokaż klucz</button></div>');
        $('.qrTwoAuth').attr('src',$('.qrAddTwoAuth').attr('src'));
        $('.keyTwoAuth').html($('.keyAddTwoAuth').html());
        $('#addTwoAuth').modal('hide').on('hidden.bs.modal',function() {
          $('.keyAddTwoAuth').empty();
          $('.qrAddTwoAuth').removeAttr('src');
        });
      } else {
        $('.alert-show').html('<div class="alert alert-danger text-center animated zoomIn">' + data.message + '</div>');
      }
    }
  })
})

/**
 * Delete Two Authentication
 *
 */
$(document).on('click','.delete-twoAuth', function() {
  $.ajax({
    url: base_url + 'api/users/edit/deleteTwoAuth', type: 'post', data: {
      userID: userID
    }, success: function(data) {
      if(data.success) {
        $('.twoAuth-status').html('wyłączony');
        $('.twoAuth-buttons').html('<button class="btn btn-dark show-addTwoAuth">Dodaj weryfikację</button>');
        $('.qrTwoAuth').removeAttr('src');
        $('.keyTwoAuth').empty();
      }
      helper.alert(data.success,data.message);
    }
  })
})

$(document).on('click','.edit-login',function() {
  $.ajax({
    url: base_url + 'api/users/edit/login', type: 'post', data: {
      userID: userID,
      login: $('.login').val()
    }, success: function(data) {
      if(data.success) {
        setTimeout(function() {
          window.location.href=data.value;
        },1000);
      }
      helper.alert(data.success,data.message);
    }
  })
})

$(document).on('click','.edit-password',function() {
  $.ajax({
    url: base_url + 'api/users/edit/password', type: 'post', data: {
      userID: userID,
      password: $('.password').val()
    }, success: function(data) {
      helper.alert(data.success,data.message);
    }
  })
})

$(document).on('click','.edit-limitBots',function() {
  if($('.select-limit').val() == 'unlimited') {
    limit = 0;
  } else {
    limit = $('.limit-bots').val();
    if(!$.isNumeric(limit)) {
      helper.alert(false,'Podaj limit botów!');
      return;
    }
  }
  $.ajax({
    url: base_url + 'api/users/edit/limitBots', type: 'post', data: {
      userID: userID,
      limitBots: limit
    }, success: function(data) {
      helper.alert(data.success,data.message);
    }
  })
});

$(document).on('click','.edit-rights',function() {
  if($('.select-rights').val() == 'all') {
    rights = 'all';
  } else {
    rights = [];
    $('.check').each(function() {
      if($(this).is(':checked')) {
        rights.push($(this).data('name'));
      }
    });
    rights = JSON.stringify(rights);
  }

  $.ajax({
    url: base_url + 'api/users/edit/rights', type: 'post', data: {
      userID: userID,
      rights: rights
    }, success: function(data) {
      helper.alert(data.success,data.message);
    }
  })
})

$(document).on('click','.edit-permsDash',function() {
  perms = new Object();
  $('.perms').each(function() {
    perms[$(this).val()] = Number($(this).is(':checked'));
  });
  $.ajax({
    url: base_url + 'api/users/edit/permsDash', type: 'post', data: {
      userID: userID,
      permsDash: perms
    }, success: function(data) {
      helper.alert(data.success,data.message);
    }
  })
});
