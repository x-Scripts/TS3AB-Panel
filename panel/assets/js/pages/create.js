 /**
  * Information
  * @Author: xares
  * @Date:   26-04-2020 14:02
  * @Filename: create.js
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 21:43
  *
  * @Copyright(C) 2020 x-Scripts
  */

$(document).on('click','.select',function() {
  selectTypeCreate(false);
})
selectTypeCreate(true);
function selectTypeCreate(first) {
  if(first) {
    select = $('.check-type').first().data('id');
  } else {
    $('.check-type').each(function() {
      if($(this).is(':checked')) {
        select = $(this).data('id');
      }
    })
  }
  if(select == 'simple') {
    $('.advanced, .expert, .expert-one').each(function() {
      if($(this).hasClass('show')) {
        $(this).removeClass('show').slideUp('normal');
      }
    });
    $('.create-bot').removeClass('create-expert create-advanced').addClass('create-simple');
    $('.settings-type').html('Prosty - tworzenie bota');
  } else if(select == 'advanced') {
    $('.expert-one').each(function() {
      if($(this).hasClass('show')) {
        $(this).removeClass('show').slideUp('normal');
      }
    });
    $('.create-bot').removeClass('create-expert create-simple').addClass('create-advanced');
    $('.advanced').each(function() {
      if(!$(this).hasClass('show')) {
        $(this).addClass('show').slideDown('normal');
      }
    });
    $('.settings-type').html('Zaawansowany - tworzenie bota');
  } else if(select == 'expert') {
    $('.expert, .expert-one').each(function() {
      if(!$(this).hasClass('show')) {
        $(this).addClass('show').slideDown('normal');
      }
    });
    $('.create-bot').removeClass('create-simple create-advanced').addClass('create-expert');
    $('.settings-type').html('Ekspert - tworzenie bota');
  }
}

$('.change-volume').mousemove(function() {
  $('.volume').html($(this).val());
}).change(function() {
  $('.volume').html($(this).val());
})
$(document).on('touchmove','.change-volume',function () {
  $('.volume').html($(this).val());
})
$('.change-volume-max').mousemove(function() {
  $('.volume-max').html($(this).val());
}).change(function() {
  $('.volume-max').html($(this).val());
})
$(document).on('touchmove','.change-volume-max',function () {
  $('.volume-max').html($(this).val());
})

$('.change-bitrate').mousemove(function() {
  val = $(this).val();
  $('.bitrate').html(val);
  getBitrate(val);
}).change(function() {
  val = $(this).val();
  $('.bitrate').html(val);
  getBitrate(val);
})
$(document).on('touchmove','.change-bitrate',function () {
  val = $(this).val();
  $('.bitrate').html(val);
  getBitrate(val);
})

$(document).on('click','.select-bitrate',function() {
  $('.get-bitrate').each(function() {
    if($(this).is(':checked')) {
      select = $(this).val();
    }
  });
  $('.change-bitrate').val(select);
  $('.bitrate').html(select);
});

function getBitrate(val) {
  if(val == 16) {
    el = $('.poor');
    if(!el.hasClass('active')) {
      el.addClass('active')
    }
  } else if(val == 24) {
    el = $('.very-poor');
    if(!el.hasClass('active')) {
      el.addClass('active')
    }
  } else if(val == 32) {
    el = $('.okey');
    if(!el.hasClass('active')) {
      el.addClass('active')
    }
  } else if(val == 48) {
    el = $('.good');
    if(!el.hasClass('active')) {
      el.addClass('active')
    }
  } else if(val == 64) {
    el = $('.very-good');
    if(!el.hasClass('active')) {
      el.addClass('active')
    }
  } else if(val == 96) {
    el = $('.best');
    if(!el.hasClass('active')) {
      el.addClass('active')
    }
  } else {
    $('.very-poor, .poor, .okey, .good, .very-good, .best').each(function() {
      if($(this).hasClass('active')) {
        $(this).removeClass('active');
      }
    });
  }
}
$( ".slider-range" ).slider({
  range: true,
  min: 0,
  max: 100,
  values: [0,100],
  slide: function( event, ui ) {
    $( "#amount" ).html(ui.values[0] + ' - ' + ui.values[1]);
  }
});
$( "#amount" ).html($( ".slider-range" ).slider( "values", 0 ) +
  " - " + $( ".slider-range" ).slider( "values", 1 ) );

$('.quantity-up').click(function() {
  input = $('.' + $(this).data('id'));
  input.val((parseInt(input.val()) + 1));

});
$('.quantity-down').click(function() {
  input = $('.' + $(this).data('id'));
  val = parseInt(input.val());
  if(input.attr('min') <= val-1) {
    input.val(val-1);
  }
});
$(document).on('click','.alias-remove',function() {
  $('.alias-' + $(this).data('id')).slideUp('normal').removeClass('alias-' + $(this).data('id')).addClass('alias-del');
  setTimeout(function() {
    $('.alias-del').remove();
  }, 1000);
  $('.tooltip').remove();
});
$('.alias-add').click(function() {
  alias1 = $('.cmd-alias-1').val();
  alias2 = $('.cmd-alias-2').val();
  if(alias1 == "" || alias2 == "") {
    helper.alert(false,"Wypełnij pola!");
    return;
  }
  do {
    random = Math.floor((Math.random() * 1000) + 1);
  } while($('.alias' + random).length)
  $('.alias-list').append('<li class="alias-' + random + '" style="display: none;">' +
  '<span class="alias-command" data-alias="' + alias1 + '" data-command="' + alias2 + '">!' + alias1 + ' - "!' + alias2 + '"</span>' +
    ' <span class="alias-remove" data-id="' + random + '" data-toggle="tooltip" data-placement="right" title="Usuń komendę">x</span>' +
  '</li>');
  $('[data-toggle="tooltip"]').tooltip();
  $('.alias-' + random).slideDown('normal');
  $('.cmd-alias-1, .cmd-alias-2').val('');
});
const badges = [];
$(document).on('change','.badges',function() {
  val = $(this).val();
  if(!helper.inArray(val,badges)) {
    file = $('.' + val).data('filename');
    name = $('.' + val).data('name');
    $('.' + val).attr('disabled',true).disabled = true;
    $('.badges-list').append('<li class="badges-' + val + '" style="display: none;">' +
    '<span class="badges-add" data-badges="' + val + '"><img src="http://badges-content.teamspeak.com/' + val + '/' + file + '_16.png"> ' + name + '</span>' +
      ' <span class="badges-remove" data-id="' + val + '" data-toggle="tooltip" data-placement="right" title="Usuń odznakę">x</span>' +
    '</li>');
    badges.push(val);
    if(badges.length >= 3) {
      $('.badges').attr('disabled',true).disabled=true;
    }
    $('[data-toggle="tooltip"]').tooltip();
    $('.badges-' + val).slideDown('normal');
  } else {
    helper.alert(false,helper.inArray(val,badges));
  }
})
$(document).on('click','.badges-remove',function() {
  val = $(this).data('id');
  $('.badges-' + val).slideUp('normal').removeClass('badges-' + val).addClass('badges-del-' + val);
  $('.' + val).removeAttr('disabled').disabled=false;
  index = badges.indexOf(val);
  if (index > -1) {
      badges.splice(index, 1);
  }
  if(badges.length < 3) {
    $('.badges').removeAttr('disabled').disabled=false;
  }
  setTimeout(function() {
    $('.badges-del-' + val).remove();
  }, 1000);
  $('.tooltip').remove();
});

//create bot
$(document).on('click','.create-simple',function() {
  $.ajax({
    url: base_url + 'api/create/simple', type: 'post', data: {
      runBot: $('.start-bot-application').is(':checked'),
      loadAvatar: $('.load-avatar').is(':checked'),
      loadTitleSongToDesc: $('.title-song-desc').is(':checked'),
      langBot: $('.bot-language').val(),
      botName: $('.bot-name').val(),
      channelID: $('.channel-id').val(),
      serverIP: $('.server-ip').val(),
      groupID: $('.group-id').val()
    }, success: function(data) {
      if(data.success) {
        rights(data.value,data.message);
      } else {
        helper.alert(data.success, data.message);
      }
    }
  })
});

$(document).on('click','.create-advanced',function() {
  selVersion = $('.client-version').find(':selected');
  version = {
    "build": selVersion.data('build'),
    "sign": selVersion.data('sign'),
    "platform": selVersion.data('platform')
  };

  var badges = '';

  if($('.overwolf').is(':checked')) {
    badges += 'overwolf=1';
  } else {
    badges += 'overwolf=0';
  }

  selBadges = $('.badges-add');
  if(selBadges.length >= 1) {
    badges2 = [];
    selBadges.each(function() {
      badges2.push($(this).data('badges'));
    });
    badges += ':badges=' + badges2.join(',');
  }

  $.ajax({
    url: base_url + 'api/create/advanced', type: 'post', data: {
      runBot: $('.start-bot-application').is(':checked'),
      loadAvatar: $('.load-avatar').is(':checked'),
      loadTitleSongToDesc: $('.title-song-desc').is(':checked'),
      langBot: $('.bot-language').val(),
      botName: $('.bot-name').val(),
      channelID: $('.channel-id').val(),
      serverIP: $('.server-ip').val(),
      groupID: $('.group-id').val(),
      channelPassword: $('.channel-password').val(),
      channelPasswordAutoHash: $('.channel-password-autohash').is(':checked'),
      channelPasswordHash: $('.channel-password-hash').is(':checked'),
      serverPassword: $('.server-password').val(),
      serverPasswordAutoHash: $('.server-password-autohash').is(':checked'),
      serverPasswordHash: $('.server-password-hash').is(':checked'),
      clientVersion: version,
      defaultVolumeSong: $('.change-volume').val(),
      maxVolumeUser: $('.change-volume-max').val(),
      setBitrate: $('.change-bitrate').val(),
      badges: badges
    }, success: function(data) {
      if(data.success) {
        rights(data.value,data.message);
      } else {
        helper.alert(data.success, data.message);
      }
    }
  })
  console.log(version,badges);
});

$(document).on('click','.create-expert',function() {
  selVersion = $('.client-version').find(':selected');
  version = {
    "build": selVersion.data('build'),
    "sign": selVersion.data('sign'),
    "platform": selVersion.data('platform')
  };

  var badges = '';

  if($('.overwolf').is(':checked')) {
    badges += 'overwolf=1';
  } else {
    badges += 'overwolf=0';
  }

  selBadges = $('.badges-add');
  if(selBadges.length >= 1) {
    badges2 = [];
    selBadges.each(function() {
      badges2.push($(this).data('badges'));
    });
    badges += ':badges=' + badges2.join(',');
  }

  aliases = {};

  $('.alias-command').each(function() {
    aliases[$(this).data('alias')] = $(this).data('command');
  })


  $.ajax({
    url: base_url + 'api/create/expert', type: 'post', data: {
      runBot: $('.start-bot-application').is(':checked'),
      loadAvatar: $('.load-avatar').is(':checked'),
      loadTitleSongToDesc: $('.title-song-desc').is(':checked'),
      langBot: $('.bot-language').val(),
      botName: $('.bot-name').val(),
      channelID: $('.channel-id').val(),
      serverIP: $('.server-ip').val(),
      groupID: $('.group-id').val(),
      channelPassword: $('.channel-password').val(),
      channelPasswordAutoHash: $('.channel-password-autohash').is(':checked'),
      channelPasswordHash: $('.channel-password-hash').is(':checked'),
      serverPassword: $('.server-password').val(),
      serverPasswordAutoHash: $('.server-password-autohash').is(':checked'),
      serverPasswordHash: $('.server-password-hash').is(':checked'),
      clientVersion: version,
      defaultVolumeSong: $('.change-volume').val(),
      maxVolumeUser: $('.change-volume-max').val(),
      setBitrate: $('.change-bitrate').val(),
      badges: badges,
      coloredChat: $('.colored-chat').is(':checked'),
      minMaxVol: $('.slider-range').slider('values'),
      commandMatcher: $('.command-matcher').val(),
      longMessage: $('.long-message').val(),
      longMessageSplitLimit: $('.long-message-split-limit').val(),
      commandComplexity: $('.command-complexity').val(),
      aliases: aliases,
      onConnect: $('.onconnect').val(),
      onDisconnect: $('.ondisconnect').val(),
      onIdle: $('.onidle').val(),
      idleTime: $('.idletime').val(),
      onAlone: $('.onalone').val(),
      aloneDelay: $('.alone-delay').val(),
      onParty: $('.onparty').val(),
      partyDelay: $('.party-delay').val()
    }, success: function(data) {
      if(data.success) {
        rights(data.value,data.message);
      } else {
        helper.alert(data.success, data.message);
      }
    }
  })
});

$(document).on('click','.check-sub',function() {
  if(!$(this).is(':checked')) {
    $('.' + $(this).data('sub')).prop('checked',false).removeAttr('checked');
  } else {
    $('.' + $(this).data('sub')).prop('checked',true).attr('checked',true);
  }
});
$('.check-sub').each(function() {
  if($(this).is(':checked')) {
    $('.' + $(this).data('sub')).prop('checked',true);
  }
})
var result;
function rights(botID,message) {
  const rules = [];
  $('.check').each(function() {
    if($(this).is(':checked')) {
      rules.push($(this).data('name'));
    }
  })
  $.ajax({
    url: base_url + 'api/rights/create', type: 'post', data: {
      botID: botID,
      groups: $('.group-id').val(),
      rules: rules
    }, success: function(data) {
      if(data.success) {
        helper.alert(true,message);
      } else {
        helper.alert(false,data.message);
      }
    }
  })
  return result;
}
