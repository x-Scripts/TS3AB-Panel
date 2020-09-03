 /**
  * Information
  * @Author: xares
  * @Date:   18-05-2020 23:18
  * @Filename: edit.js
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 21:41
  *
  * @Copyright(C) 2020 x-Scripts
  */

const badges = [];
var editSlider = false;
function edit() {
  setTimeout(function() {
    $.ajax({
      url: base_url + 'api/edit', type: 'post', data: {
        botID: botID
      }, success: function(data) {
        if(data.success) {
          $.each(data.value.checkbox,function(id,val) {
            $('.' + id).prop('checked',val);
          })
          $.each(data.value.text,function(id,val) {
            $('.' + id).val(val);
          })
          $.each(data.value.select, function(id,val) {
            $('option[value="' + val + '"]').prop('selected',true);
          })
          if(typeof data.value.version !== 'undefined') {
            if(data.value.version != '') {
              $('option[data-sign="' + data.value.version + '"]').prop('selected',true);
            }
          }
          if(typeof data.value.textView !== 'undefined') {
            $.each(data.value.textView, function(id,val) {
              $('.' + id).html(val);
            })
          }
          if(typeof data.value.bitrate !== 'undefined') {
            if(data.value.bitrate != '') {
              $('.change-bitrate').val(data.value.bitrate);
              getBitrate(data.value.bitrate);
              $('input[value="' + data.value.bitrate + '"]','.select-bitrate').prop('checked',true);
            }
          }
          if(typeof data.value.rights !== 'undefined') {
            if(data.value.rights != null) {
              $.each(data.value.rights, function(id,val) {
                $('input[data-name="' + val + '"]').prop('checked',true);
              })
              $('.check-sub').each(function() {
                if($(this).is(':checked')) {
                  $('.' + $(this).data('sub')).prop('checked',true);
                }
              })
            }
          }
          if(typeof data.value.badges !== 'undefined') {
            if(data.value.badges != null) {
              list = '';
              $.each(data.value.badges, function(id,val) {
                $('.' + val.name).prop('disabled',true);
                list += '<li class="badges-' + val.name + '">' +
                '<span class="badges-add" data-badges="' + val.name + '"><img src="http://badges-content.teamspeak.com/' + val.name + '/' + val.filename + '_16.png"> ' + val.title + '</span>' +
                  ' <span class="badges-remove" data-id="' + val.name + '" data-toggle="tooltip" data-placement="right" title="Usuń odznakę">x</span>' +
                '</li>';
                badges.push(val.name);
                if(badges.length >= 3) {
                  $('.badges').attr('disabled',true).disabled=true;
                }
                $('.badges-list').html(list);
                $('[data-toggle="tooltip"]').tooltip();
              })
            }
          }
          if(typeof data.value.minMaxVol !== 'undefined') {
            if(data.value.minMaxVol != null) {
              editSlider = true;
              $('.slider-range').slider('values',data.value.minMaxVol);
              editSlider = false;
            }
          }
          if(typeof data.value.aliases !== 'undefined') {
            if(data.value.aliases != null) {
              list = '';
              $.each(data.value.aliases,function(id, val) {
                do {
                  random = Math.floor((Math.random() * 1000) + 1);
                } while($('.alias' + random).length)
                list += '<li class="alias-' + random + '">' +
                '<span class="alias-command" data-alias="' + id + '" data-command="' + val + '">!' + id + ' - "' + val + '"</span>' +
                  ' <span class="alias-remove" data-alias="' + id + '" data-id="' + random + '" data-toggle="tooltip" data-placement="right" title="Usuń komendę">x</span>' +
                '</li>';
              })
              $('.alias-list').html(list);
              $('[data-toggle="tooltip"]').tooltip();
            }
          }
          if(typeof data.value.clients !== 'undefined') {
            if(data.value.clients == null || !Object.keys(data.value.clients).length) {
              $('.clients-list').html('<tr><td></td><td>Brak użytkowników</td><td></td></tr>');
            } else {
              tr = '';
              $.each(data.value.clients,function(nick,uid) {
                tr += '<tr><td>' + nick + '</td><td>' + uid + '</td><td><button class="btn btn-dark show-deleteUser" data-name="' + nick + '"><i class="fa fa-trash-o"></i></button></td></tr>';
              })
              $('.clients-list').html(tr);
            }
          }
          $('.loader').slideUp('normal',function() {
            $('.show').slideDown(1000);
            $(this).remove();
          });
        } else {
          helper.alert(false,data.message);
        }
      }
    })
  },1000)
}
edit();


/**  Simple  **/
$('input[type=checkbox]').change(function() {
  if(typeof $(this).attr('name') !== 'undefined') {
    ajaxResult($(this).attr('name'),$(this).is(':checked'))
  }
})

$('.bot-language').change(function() {
  ajaxResult('botLanguage',$(this).val());
})
$('input[type=text], input[type=number]').blur(function() {
  if(typeof $(this).attr('name') !== 'undefined') {
    ajaxResult($(this).attr('name'),$(this).val(), $(this).attr('name') == 'groupID' ? true : false)
  }
});


$('.edit-rights').click(function() {
  rights = [];
  $('.check').each(function() {
    if($(this).is(':checked')) {
      rights.push($(this).data('name'));
    }
  })
  ajaxResult('editCmd',rights,true);
});


/**  Advanced  **/
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
  ajaxResult('bitrate',select);
});

$('.change-bitrate').mousemove(function() {
  val = $(this).val();
  $('.bitrate').html(val);
  getBitrate(val);
}).change(function() {
  val = $(this).val();
  $('.bitrate').html(val);
  getBitrate(val);
  ajaxResult('bitrate',val);
})

$('.change-volume').mousemove(function() {
  $('.volume').html($(this).val());
}).change(function() {
  $('.volume').html($(this).val());
  ajaxResult('volume',$(this).val());
})

$(document).on('touchmove','.change-volume',function () {
  $('.volume').html($(this).val());
})
$('.change-volume-max').mousemove(function() {
  $('.volume-max').html($(this).val());
}).change(function() {
  $('.volume-max').html($(this).val());
  ajaxResult('maxVolume',$(this).val())
})

$(document).on('touchmove','.change-volume-max',function () {
  $('.volume-max').html($(this).val());
})

$('.client-version').change(function() {
  data = $(this).find(':selected');
  version = {
    "build": data.data('build'),
    "sign": data.data('sign'),
    "platform": data.data('platform')
  }
  ajaxResult('clientVersion',version);
})

$('.edit-badges').click(function() {
  var badges_edit = '';

  if($('.overwolf').is(':checked')) {
    badges_edit += 'overwolf=1';
  } else {
    badges_edit += 'overwolf=0';
  }

  selBadges = $('.badges-add');
  if(selBadges.length >= 1) {
    badges2 = [];
    selBadges.each(function() {
      badges2.push($(this).data('badges'));
    });
    badges_edit += ':badges=' + badges2.join(',');
  }
  ajaxResult('badges',badges_edit);
})

/**  Expert  **/
$( ".slider-range" ).slider({
  range: true,
  min: 0,
  max: 100,
  values: [0,0],
  slide: function( event, ui ) {
    $( "#amount" ).html(ui.values[0] + ' - ' + ui.values[1]);
  },
  change: function(event,ui) {
    if(!editSlider) {
      ajaxResult('minMaxVol',ui.values);
    }
  }
});
$( "#amount" ).html($( ".slider-range" ).slider( "values", 0 ) +
  " - " + $( ".slider-range" ).slider( "values", 1 ) );
var timeDown = {
  "longMessageSplitLimit": "",
  "commandComplexity": ""
};
var timeUp = {
  "longMessageSplitLimit": "",
  "commandComplexity": ""
};
$('.quantity-up-long').click(function() {
  input = $('.' + $(this).data('id'));
  input.val((parseInt(input.val()) + 1));
  clearTimeout(timeUp["longMessageSplitLimit"]);
  timeUp["longMessageSplitLimit"] = setTimeout(function() {
    ajaxResult("longMessageSplitLimit",parseInt(input.val()));
  },1000);
});
$('.quantity-down-long').click(function() {
  input = $('.' + $(this).data('id'));
  val = parseInt(input.val());
  if(input.attr('min') <= val-1) {
    clearTimeout(timeDown["longMessageSplitLimit"]);
    timeDown["longMessageSplitLimit"] = setTimeout(function() {
      ajaxResult("longMessageSplitLimit",(val-1 == 0 ? 1 : val-1));
    },1000);
    input.val(val-1);
  }
});

$('.quantity-up-command').click(function() {
  input = $('.' + $(this).data('id'));
  input.val((parseInt(input.val()) + 1));
  clearTimeout(timeUp["commandComplexity"]);
  timeUp["commandComplexity"] = setTimeout(function() {
    ajaxResult("commandComplexity",parseInt(input.val()));
  },1000);
});
$('.quantity-down-command').click(function() {
  input = $('.' + $(this).data('id'));
  val = parseInt(input.val());
  if(input.attr('min') <= val-1) {
    clearTimeout(timeDown["commandComplexity"]);
    timeDown["commandComplexity"] = setTimeout(function() {
      ajaxResult("commandComplexity",(val-1 == 0 ? 1 : val-1));
    },1000);
    input.val(val-1);
  }
});

$('.select-change').change(function() {
  ajaxResult($(this).attr('name'),$(this).val());
})

$(document).on('click','.alias-remove',function() {
  $('.alias-' + $(this).data('id')).slideUp('normal').removeClass('alias-' + $(this).data('id')).addClass('alias-del');
  setTimeout(function() {
    $('.alias-del').remove();
  }, 1000);
  $('.tooltip').remove();
  ajaxResult('deleteAlias',$(this).data('alias'));
});
$('.alias-add').click(function() {
  alias1 = $('.cmd-alias-1').val();
  alias2 = $('.cmd-alias-2').val();
  if(alias1 == "" || alias2 == "") {
    helper.alert(false,"Wypełnij pola!");
    return;
  }

  $.ajax({
    url: base_url + 'api/edit/addAlias', type: 'post', data: {
      botID: botID,
      value: [alias1,alias2]
    }, success: function(data) {
      if(data.success) {
        do {
          random = Math.floor((Math.random() * 1000) + 1);
        } while($('.alias' + random).length)

        $('.cmd-alias-1, .cmd-alias-2').val('');
        $('.alias-list').append('<li class="alias-' + random + '" style="display: none;">' +
        '<span class="alias-command" data-alias="' + alias1 + '" data-command="' + alias2 + '">!' + alias1 + ' - "!' + alias2 + '"</span>' +
          ' <span class="alias-remove" data-alias="' + alias1 + '" data-id="' + random + '" data-toggle="tooltip" data-placement="right" title="Usuń komendę">x</span>' +
        '</li>');
        $('.alias-' + random).slideDown('normal');
        $('[data-toggle="tooltip"]').tooltip();
      }
      helper.toast(data.success,data.message);
    }
  })
});

/**  Add users  **/
$('.add-user').click(function() {
  $.ajax({
    url: base_url + 'api/rights/addUser', type: 'post', data: {
      botID: botID,
      user: $('.user-nickname').val(),
      uid: $('.user-uid').val()
    }, success: function(data) {
      if(data.success) {
        if(Object.keys(data.value).length > 0) {
          tr = '';
          $.each(data.value,function(nick,uid) {
            tr += '<tr><td>' + nick + '</td><td>' + uid + '</td><td><button class="btn btn-dark show-deleteUser" data-name="' + nick + '"><i class="fa fa-trash-o"></i></button></td></tr>';
          })
          $('.clients-list').html(tr);
        } else {
          $('.clients-list').html('<tr><td></td><td>Brak użytkowników</td><td></td></tr>');
        }
        $('.user-nickname, .user-uid').val('');
      }
      helper.toast(data.success,data.message);
    }
  })
});

$(document).on('click','.show-deleteUser', function() {
  $('.user-name').html($(this).data('name'));
  $('.delete-user').val($(this).data('name'));
  $('#deleteUser').modal('show');
  console.log($(this).data('name'));
})

$('.cancel-deleteUser').click(function() {
  $('#deleteUser').modal('hide').on('hidden.bs.modal', function () {
    $('.delete-user').removeAttr('value');
    $('.user-name').empty();
  })
})

$('.delete-user').click(function() {

  $('#deleteUser').modal('hide').on('hidden.bs.modal', function () {
    $('.delete-user').removeAttr('value');
    $('.user-name').empty();
  })
  $.ajax({
    url: base_url + 'api/rights/deleteUser', type: 'post', data: {
      botID: botID,
      user: $(this).val()
    }, success: function(data) {
      if(data.success) {
        if(Object.keys(data.value).length > 0) {
          tr = '';
          $.each(data.value,function(nick,uid) {
            tr += '<tr><td>' + nick + '</td><td>' + uid + '</td><td><button class="btn btn-dark show-deleteUser" data-name="' + nick + '"><i class="fa fa-trash-o"></i></button></td></tr>';
          })
          $('.clients-list').html(tr);
        } else {
          $('.clients-list').html('<tr><td></td><td>Brak użytkowników</td><td></td></tr>');
        }
      }
      helper.toast(data.success,data.message)
    }
  })
})

/**  Helpers functions  **/
function ajaxResult(request,value, rights = false) {
  edit = rights ? 'rights/' : 'edit/';
  $.ajax({
    url: base_url + 'api/' + edit + request, type: 'post', data: {
      botID: botID,
      value: value
    }, success: function(data) {
      helper.toast(data.success,data.message);
    }
  })
  console.log([edit + request, value]);
}

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
    $('.settings-type').html('Prosty - edytowanie bota');
  } else if(select == 'advanced') {
    $('.expert-one').each(function() {
      if($(this).hasClass('show')) {
        $(this).removeClass('show').slideUp('normal');
      }
    });
    $('.advanced').each(function() {
      if(!$(this).hasClass('show')) {
        $(this).addClass('show').slideDown('normal');
      }
    });
    $('.settings-type').html('Zaawansowany - edytowanie bota');
  } else if(select == 'expert') {
    $('.expert, .expert-one').each(function() {
      if(!$(this).hasClass('show')) {
        $(this).addClass('show').slideDown('normal');
      }
    });
    $('.settings-type').html('Ekspert - edytowanie bota');
  }
}

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

$(document).on('click','.check-sub',function() {
  if(!$(this).is(':checked')) {
    $('.' + $(this).data('sub')).prop('checked',false).removeAttr('checked');
  } else {
    $('.' + $(this).data('sub')).prop('checked',true).attr('checked',true);
  }
});

$(document).on('change','.badges',function() {
  val = $(this).val();
  if(!helper.inArray(val,badges)) {
    file = $(this).find(':selected').data('filename');
    name = $(this).find(':selected').data('name');
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
