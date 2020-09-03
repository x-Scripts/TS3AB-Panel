 /**
  * Information
  * @Author: xares
  * @Date:   24-05-2020 21:55
  * @Filename: account.js
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 25-05-2020 00:15
  *
  * @Copyright(C) 2020 x-Scripts
  */

  $(document).on('click','.show-password',function() {
    pass = $($(this).data('input'));
    $(this).toggleClass("fa-eye fa-eye-slash");
    if(pass.attr('type') == 'password') {
      pass.attr('type','text');
    } else {
      pass.attr('type','password');
    }
  })

  $(document).on('click','.show-addTwoAuth', function() {
    $.ajax({
      url: base_url + 'api/account/generateTwoAuth', type: 'get', success: function(data) {
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

  $(document).on('click','.add-twoAuth',function() {
    $.ajax({
      url: base_url + 'api/account/verifyTwoAuth', type: 'post', data: {
        secretKey: $('.keyAddTwoAuth').html(),
        tokenAuth: $('.tokenAddTwoAuth').val()
      }, success: function(data) {
        if(data.success) {
          helper.alert(true,data.message);
          $('.twoAuth-status').html('włączony');
          $('.twoAuth-buttons').html('<button class="btn btn-dark" data-toggle="modal" data-target="#deleteTwoAuth">Usuń weryfikację</button>');
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

  $(document).on('click','.delete-twoAuth', function() {
    $.ajax({
      url: base_url + 'api/account/deleteTwoAuth', type: 'get', success: function(data) {
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

  $(document).on('click','.edit-password',function() {
    $.ajax({
      url: base_url + 'api/account/editPassword', type: 'post', data: {
        password: $('.password').val(),
        newPassword: $('.new-password').val()
      }, success: function(data) {
        helper.alert(data.success,data.message);
      }
    })
  })

  $(document).on('click','.delete-remember',function() {
    $.ajax({
      url: base_url + 'api/account/deleteRemember', type: 'post', data: {
        id: $(this).data('id')
      }, success: function(data) {
        if(data.success) {
          if(new Object(data.value).length) {
            tr = '';
            $.each(data.value,function(id,val) {
              tr += '<tr><td>' + val.platform + '</td><td>' + val.browser + '</td><td><button class="btn btn-dark delete-remember" data-id="' + val.id + '"><i class="fa fa-trash-o"></i></button></td></tr>';
            })
            $('.remember-list').html(tr);
          } else {
            $('.remember-list').html('<tr><td></td><td>Brak</td><td></td></tr>');
          }
        }
        helper.alert(data.success,data.message);
      }
    })
  })
