 /**
  * Information
  * @Author: xares
  * @Date:   24-05-2020 16:37
  * @Filename: main_helper.js
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 01:44
  *
  * @Copyright(C) 2020 x-Scripts
  */

helper = {
  alert: function(type,message,time = 5000) {
    // if(type == true || type == false) {
    //   type = type ? 'success' : 'danger';
    // }
    // $.notify({
    //   message: message
    // },{
    //   type: type,
    //   timer: time
    // });
    if(type == true || type == false) {
      type = type ? 'success' : 'error';
    }
    $.toast({
      text: message,
      icon: type,
      hideAfter: parseInt(time),
      position: "top-center"
    })
  },
  toast: function(success,message) {
    $.toast({
      text: message,
      icon: success ? 'success' : 'error',
      hideAfter: success ? 1000 : 5000,
      position: "top-center"
    })
  },
  inArray: function(value,array) {
    for (var i = 0; i < array.length; i++) {
      if(array[i] == value) {
        return true;
      }
    }
    return false;
  },
  removeItemArray: function(value,array) {
    index = array.indexOf(value);
    if (index > -1) {
        array.splice(index, 1);
    }
    return array;
  },
  generatePassword: function(element,length = 16) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i <= length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    $(element).val(result);
  },
  convertTime: function(seconds) {
    seconds = Number(seconds);
    d = Math.floor(seconds / (3600*24));
    h = Math.floor(seconds % (3600*24) / 3600);
    m = Math.floor(seconds % 3600 / 60);
    s = Math.floor(seconds % 60);

    result = [];
    if(d != 0) {
      if(d >= 1 && d <= 9) {
        result.push('0' + d);
      } else {
        result.push(d);
      }
    }

    if(h != 0) {
      if(h >= 0 && h <= 9) {
        result.push('0' + h);
      } else {
        result.push(h);
      }
    }

    if(m >= 0 && m <= 9) {
      result.push('0' + m);
    } else {
      result.push(m);
    }

    if(s >= 0 && s <= 9) {
      result.push('0' + s);
    } else {
      result.push(s);
    }
    return result.join(':');
  }
}
