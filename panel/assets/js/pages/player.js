 /**
  * Information
  * @Author: xares
  * @Date:   21-05-2020 23:04
  * @Filename: player.js
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 18:15
  *
  * @Copyright(C) 2020 x-Scripts
  */

//var audiotype = '';
var position = 0;
var length = 0;
var timeResult = '';
var playerStatus = '';
var seekTime = false;
var dataVol;

function time() {
  seek = $('.seek-song');
  position = (Math.round(position) + 1);

  if(length == 0) {
    if(!seek.is(':disabled')) {
      seek.prop({disabled: true, max: 0, value: 0});
    }
  } else {
    if(position <= length) {
      if(!seekTime) {
        seek.prop({disabled: false, max: length, value: position});
      }
    } else {
      clearInterval(timeResult);
      $('.timeSong').html('00:00/00:00');
      $('.title-song').html('Brak');
      $('.random, .repeat, .play-pause, .stop, .prev, .next, .seek-song').prop('disabled',true);
      seek.prop({disabled: true, max: 0, value: 0});
      return;
    }
  }
  if(!seekTime) {
    $('.timeSong').html(helper.convertTime(position) + '/' + helper.convertTime(length));
  }
}

if(permission.manageMusic) {
  player();
  playerStatus = setInterval(function() {
    player();
  },5000);
}

function player() {
  $.ajax({
    url: base_url + 'api/player', type: 'post', data: {
      botID: botID
    }, success: function(data) {
      if(data.success) {
        if(data.value[0] == null) {
          $('.random, .repeat, .play-pause, .stop, .prev, .next, .seek-song').prop('disabled',true);
          $('.timeSong').html('00:00/00:00');
          $('.title-song').html('Brak');
          clearInterval(timeResult);
        } else {
          $('.random, .repeat, .play-pause, .stop, .prev, .next').prop('disabled',false);
          playPause = $('#icon-play-pause');
          if(data.value[0].Paused) {
            if(playPause.hasClass('mdi-pause')) {
              playPause.removeClass('mdi-pause').addClass('mdi-play');
            }
          } else {
            if(playPause.hasClass('mdi-play')) {
              playPause.removeClass('mdi-play').addClass('mdi-pause');
            }
          }
          position = data.value[0].Position;
          length = data.value[0].Length;
          if(length == 0) {
            $('.seek-song').prop({max: 0, value: 0, disabled: true});
          } else if(!seekTime) {
            $('.seek-song').prop({max: length, value: position, disabled: false});
          }
          if(data.value[0].Title == '') {
            data.value[0].Title = 'Brak tytułu';
          }
          $('.title-song').html('<a href="' + data.value[0].Link + '" target="_blank">' + data.value[0].Title + '</a>');
          if(!seekTime) {
            $('.timeSong').html(helper.convertTime(data.value[0].Position) + '/' + helper.convertTime(data.value[0].Length));
          }
          clearInterval(timeResult);
          if(!data.value[0].Paused) {
            time();
            timeResult = setInterval(function() {
              time();
            },1000);
          }
        }
        if(data.value[1] != null) {
          dataVol = Math.round(data.value[1]);
          if(parseInt($('.change-volume').attr('value')) != dataVol) {
            $('.volume').html(dataVol);
            $('.change-volume').val(data.value[1]).attr('value',dataVol);
          }
        }
        if(data.value[2] != null) {
          random = $('#icon-random');
          if(data.value[2]) {
            if(random.hasClass('mdi-shuffle-disabled')) {
              random.removeClass('mdi-shuffle-disabled').addClass('mdi-shuffle');
            }
          } else {
            if(random.hasClass('mdi-shuffle')) {
              random.removeClass('mdi-shuffle').addClass('mdi-shuffle-disabled');
            }
          }
        }
        if(data.value[3] != null) {
          repeat = $('#icon-repeat');
          if(data.value[3] == 0) {
            repeat.removeClass('mdi-repeat-once mdi-repeat').addClass('mdi-repeat-off');
            $('.repeat').data('status','off');
          } else if(data.value[3] == 1) {
            repeat.removeClass('mdi-repeat mdi-repeat-off').addClass('mdi-repeat-once');
            $('.repeat').data('status','one');
          } else {
            repeat.removeClass('mdi-repeat-once mdi-repeat-off').addClass('mdi-repeat');
            $('.repeat').data('status','all');
          }
        }
      } else {
        $('.random, .repeat, .play-pause, .stop, .prev, .next, .play-song, .add-song, .change-volume, .seek-song, .song-url').prop('disabled',true);
        helper.alert(false,data.message);
      }
      console.log(data.value);
    }
  })
}

$('.repeat').click(function() {
  status = $(this).data('status');
  if(status == 'off') {
    $('#icon-repeat').toggleClass('mdi-repeat-off mdi-repeat-once');
    $(this).data('status','one');
    ajaxResult('repeat','one');
  } else if(status == 'one') {
    $('#icon-repeat').toggleClass('mdi-repeat-once mdi-repeat');
    $(this).data('status','all');
    ajaxResult('repeat','all');
  } else if(status == 'all') {
    $('#icon-repeat').toggleClass('mdi-repeat mdi-repeat-off');
    $(this).data('status','off');
    ajaxResult('repeat','off');
  }
})

$('.random').click(function() {
  el = $('#icon-random');
  if(el.hasClass('mdi-shuffle')) {
    el.toggleClass('mdi-shuffle-disabled mdi-shuffle');
    ajaxResult('random','off');
  } else if(el.hasClass('mdi-shuffle-disabled')) {
    el.toggleClass('mdi-shuffle mdi-shuffle-disabled');
    ajaxResult('random','on');
  }
})

$('.play-pause').click(function() {
  el = $('#icon-play-pause');
  if(el.hasClass('mdi-play')) {
    el.toggleClass('mdi-play mdi-pause');
    ajaxResult('pause',false);
    timeResult = setInterval(function() {
      time();
    },1000)
  } else if(el.hasClass('mdi-pause')) {
    el.toggleClass('mdi-play mdi-pause');
    ajaxResult('pause',true);
    clearInterval(timeResult);
  }
})

$('.next').click(function() {
  ajaxResult('next',true,true);
})

$('.prev').click(function() {
  ajaxResult('prev',true,true);
})

$('.stop').click(function() {
  clearInterval(timeResult);
  $('.timeSong').html('00:00/00:00');
  $('.title-song').html('Brak');
  $('.random, .repeat, .play-pause, .stop, .prev, .next, .seek-song').prop('disabled',true);
  seek.prop({disabled: true, max: 0, value: 0});
  ajaxResult('stop',true);
})

$('.change-volume').mousemove(function() {
  $('.volume').html($(this).val());
}).change(function() {
  $('.volume').html($(this).val());
  $(this).attr('value',$(this).val());
  ajaxResult('volume',$(this).val().split('.')[0]);
});

$('.seek-song').mousemove(function() {
  seekTime = true;
  $('.timeSong').html(helper.convertTime($(this).val()) + '/' + helper.convertTime(length));
  setTimeout(function() {
    seekTime = false;
  },500);
}).change(function() {
  position = parseInt($(this).val());
  $('.timeSong').html(helper.convertTime($(this).val()) + '/' + helper.convertTime(length));
  ajaxResult('seek',$(this).val());
})

$('.play-song').click(function() {
  url = $('.song-url').val();
  $.ajax({
    url: base_url + 'api/player/playSong', type: 'post', data: {
      botID: botID,
      url: url
    }, success: function(data) {
      if(data.success) {
        clearInterval(playerStatus);
        player();
        playerStatus = setInterval(function() {
          player();
        },5000);
      }
      helper.toast(data.success,data.message);
    }
  })
})
$('.add-song').click(function() {
  ajaxResult('addSong',$('.song-url').val(),true);
})

/**  Helper functions  **/

function ajaxResult(request, value, showMessage = false) {
  $.ajax({
    url: base_url + 'api/player/' + request, type: 'post', data: {
      botID: botID,
      value: value
    },success: function(data) {
      if(data.success) {
        if(showMessage) {
          helper.toast(true,data.message);
        }
      } else {
        helper.toast(false,data.message);
      }
    }
  })
  //console.log([request, value, showMessage]);
}
