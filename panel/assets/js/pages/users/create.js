 /**
  * Information
  * @Author: xares
  * @Date:   13-05-2020 20:52
  * @Filename: create.js
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 13-05-2020 20:52
  *
  * @Copyright(C) 2020 x-Scripts
  */

$(document).on('change','.select-limit',function() {
  if($(this).val() == 'unlimited') {
    $('.limit-bots-show').slideUp('normal');
  } else {
    $('.limit-bots-show').slideDown('normal');
  }
});

$(document).on('change','.select-rights',function() {
  if($(this).val() == 'all') {
    $('.assign-rights').slideUp('normal');
  } else {
    $('.assign-rights').slideDown('normal');
  }
});

$(document).on('click','.show-password',function() {
  pass = $('.password');
  $(this).toggleClass("fa-eye fa-eye-slash");
  if(pass.attr('type') == 'password') {
    pass.attr('type','text');
  } else {
    pass.attr('type','password');
  }
})

$(document).on('click','.check-sub',function() {
  if(!$(this).is(':checked')) {
    $('.' + $(this).data('sub')).prop('checked',false).removeAttr('checked');
  } else {
    $('.' + $(this).data('sub')).prop('checked',true).attr('checked',true);
  }
});

$(document).on('click','.create-user',function() {

  login = $('.login').val();

  if(login == '') {
    helper.alert(false,"Wpisz login!");
    return;
  }

  password = $('.password').val();

  if(password == '') {
    helper.alert(false,"Wpisz hasło!");
    return;
  }
  var rights = '';
  selectRights = $('.select-rights').val();
  if(selectRights == 'all') {
    rights = 'all';
  } else if(selectRights == 'assign') {
    rights = [];
    $('.check').each(function() {
      if($(this).is(':checked')) {
        rights.push($(this).data('name'));
      }
    });
  } else {
    helper.alert(false,'Wybierz uprawnienia przy tworzeniu bota');
    return;
  }

  selectLimit = $('.select-limit').val();
  if(selectLimit == 'unlimited') {
    limit = 'all';
  } else if(selectLimit) {
    if($('.limit-bots').val() == "") {
      helper.alert(false,'Wpisz limit!');
      return;
    }
    limit = $('.limit-bots').val();
  } else {
    helper.alert(false,'Wybierz limit dla konta');
    return;
  }

  const perms = [];
  perms[0] = [];
  perms[1] = [];
  $('.perms').each(function() {
    perms[0].push($(this).is(":checked"));
    perms[1].push($(this).val());
  });

  $.ajax({
    url: base_url + 'api/users/create', type: 'post', data: {
      permsDash: perms,
      login: login,
      password: password,
      limitBots: limit,
      rightsUserBots: rights
    }, success: function(data) {
      helper.alert(data.success,data.message);
    }
  })
})
