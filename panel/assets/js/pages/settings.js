  /**
   * Information
   * @Author: xares
   * @Date:   25-05-2020 13:48
   * @Filename: settings.js
   * @Project: xDashTS3AudioBot
   *
   * Contact
   * @Email: xares.scripts@gmail.com
   * @TeamSpeak: x-scripts.pl, jutuby.net
   *
   * Modify
   * @Last Modified by:   xares
   * @Last Modified time: 29-05-2020 14:11
   *
   * @Copyright(C) 2020 x-Scripts
   */

 // var defRights = [];
 $('.check-sub').each(function() {
   if($(this).is(':checked')) {
     $('.' + $(this).data('sub')).prop('checked',true);
   }
 })

 $('.copy-key').click(function() {

   copyText = $('.api-key');

   copyText.select();
   document.execCommand("copy");
   copyText.blur();
   helper.toast(true,'Skopiowano klucz')
 })
 $(document).on('click','.check-sub',function() {
   if(!$(this).is(':checked')) {
     $('.' + $(this).data('sub')).prop('checked',false).removeAttr('checked');
   } else {
     $('.' + $(this).data('sub')).prop('checked',true).attr('checked',true);
   }
 });

 $(document).on('click','.show-edit',function() {
   $('.check, .check-sub').prop('checked',false);
   $.each($(this).data('rights').split(','),function(id,val) {
     $('input[data-name="' + val + '"]').prop('checked',true).attr('checked',true);
   })
   $('.id-edit').html($(this).data('id'));
   $('.edit').val($(this).data('type'));
   $('#edit').modal('show');
 })

 $('.cancel-edit').click(function() {
   $('#edit').modal('hide').on('hidden.bs.modal',function() {
     console.log('del');
     $('.check, .check-sub').each(function() {
       if($(this).is(':checked')) {
         $(this).prop('checked',false).attr('checked',false);
       }
     })
     $.each(defRights,function(id,val) {
       $('input[data-name="' + val + '"]').prop('checked',true).attr('checked',true);

     });
   });
 })

 $('#edit').on('hidden.bs.modal',function() {
   console.log('del');
   $('.check, .check-sub').prop('checked',false);
 })

 $('.select-apiType').change(function() {
   if($(this).val() == 'externalhost') {
     $('.externalhost').slideDown('normal');
   } else {
     $('.externalhost').slideUp('normal');
   }
 })

 $('.edit-ownerGroup').blur(function() {
   $.ajax({
     url: base_url + 'api/rights/editOwnerGroup', type: 'post', data: {
       groups: $(this).val()
     }, success: function(data) {
       helper.alert(data.success,data.message);
     }
   })
 })

 $('.addGroup').click(function() {
   rights = [];
   $('.check-addGroup').each(function() {
     if($(this).is(':checked')) {
       rights.push($(this).data('name'));
     }
   })
   $.ajax({
     url: base_url + 'api/rights/addAdminGroup', type: 'post', data: {
       group: $('.group-id').val(),
       rights: rights
     }, success: function(data) {
       if(data.success) {
         $('#addGroup').modal('hide');
         tr = '';
         if(Object.keys(data.value).length) {
           $.each(data.value,function(id,val) {
             tr += '<tr><td>' + id + '</td><td><button class="btn btn-dark show-edit" data-type="adminGroup" data-id="' + id + '" data-rights="' + val.join(',') + '"><i class="fa fa-pencil"></i></button> <button class="btn btn-dark delete-adminGroup" data-id="' + id + '"><i class="fa fa-trash-o"></i></button></td></tr>'
           });
         } else {
           tr += '<tr><td>Brak</td><td></td></tr>'
         }
         $('.list-adminGroups').html(tr);
         helper.alert(data.success,data.message);
       } else {
         $('.alert-add').html('<div class="alert alert-danger text-center animated fadeIn">' + data.message + '</div>');
       }

     }
   })
 })

 $('#addGroup').on('hidden.bs.modal',function() {
   $('.check-addGroup').each(function() {
     if($(this).is(':checked')) {
       $(this).prop('checked',false);
     }
   })
   $.each(defRights,function(id,val) {
     $('input[data-name="' + val + '"]').prop('checked',true);
   })
   $('.check-sub').each(function() {
     if($(this).is(':checked')) {
       $('.' + $(this).data('sub')).prop('checked',true);
     }
   })
   $('.group-id').val('');
   $('.alert-add').empty();
 })

 $('.addUser').click(function() {
   rights = [];
   $('.check-addUser').each(function() {
     if($(this).is(':checked')) {
       rights.push($(this).data('name'));
     }
   })
   $.ajax({
     url: base_url + 'api/rights/addAdminUser', type: 'post', data: {
       user: $('.user-id').val(),
       rights: rights
     }, success: function(data) {
       if(data.success) {
         $('#addUser').modal('hide');
         tr = '';
         if(Object.keys(data.value).length) {
           $.each(data.value,function(id,val) {
             tr += '<tr><td>' + id + '</td><td><button class="btn btn-dark show-edit" data-type="adminUser" data-id="' + id + '" data-rights="' + val.join(',') + '"><i class="fa fa-pencil"></i></button> <button class="btn btn-dark delete-adminUser" data-id="' + id + '"><i class="fa fa-trash-o"></i></button></td></tr>'
           });
         } else {
           tr += '<tr><td>Brak</td><td></td></tr>'
         }
         $('.list-adminUsers').html(tr);
         helper.alert(data.success,data.message);
       } else {
         $('.alert-user').html('<div class="alert alert-danger text-center animated fadeIn">' + data.message + '</div>');
       }

     }
   })
 })

 $('#addUser').on('hidden.bs.modal',function() {
   $('.check-addUser').each(function() {
     if($(this).is(':checked')) {
       $(this).prop('checked',false);
     }
   })
   $.each(defRights,function(id,val) {
     $('input[data-name="' + val + '"]').prop('checked',true);
   })
   $('.check-sub').each(function() {
     if($(this).is(':checked')) {
       $('.' + $(this).data('sub')).prop('checked',true);
     }
   })
   $('.user-id').val('');
   $('.alert-user').empty();
 })

 $('.addOwnerUser').click(function() {
   $.ajax({
     url: base_url + 'api/rights/addOwnerUser', type: 'post', data: {
       user: $('.ownerUser-id').val(),
     }, success: function(data) {
       if(data.success) {
         $('#addOwnerUser').modal('hide');
         tr = '';
         if(Object.keys(data.value).length) {
           $.each(data.value,function(id,val) {
             tr += '<tr><td>' + val + '</td><td><button class="btn btn-dark delete-ownerUser" data-id="' + val + '"><i class="fa fa-trash-o"></i></button></td></tr>'
           });
         } else {
           tr += '<tr><td>Brak</td><td></td></tr>'
         }
         $('.list-ownerUsers').html(tr);
         helper.alert(data.success,data.message);
       } else {
         $('.alert-ownerUser').html('<div class="alert alert-danger text-center animated fadeIn">' + data.message + '</div>');
       }

     }
   })
 })

 $('#addOwnerUser').on('hidden.bs.modal',function() {
   $('.ownerUser-id').val('');
   $('.alert-ownerUser').empty();
 })

 $(document).on('click','.delete-ownerUser', function() {
   $.ajax({
     url: base_url + 'api/rights/deleteOwnerUser', type: 'post', data: {
       user: $(this).data('id')
     }, success: function(data) {
       if(data.success) {
         tr = '';
         if(Object.keys(data.value).length) {
           $.each(data.value,function(id,val) {
             tr += '<tr><td>' + val + '</td><td><button class="btn btn-dark delete-ownerUser" data-id="' + val + '"><i class="fa fa-trash-o"></i></button></td></tr>'
           });
         } else {
           tr += '<tr><td>Brak</td><td></td></tr>'
         }
         $('.list-ownerUsers').html(tr);
       }
       helper.alert(data.success,data.message);
     }
   })
 })

 $(document).on('click','.delete-adminUser',function() {
   $.ajax({
     url: base_url + 'api/rights/deleteAdminUser', type: 'post', data: {
       user: $(this).data('id')
     }, success: function(data) {
       if(data.success) {
         tr = '';
         if(Object.keys(data.value).length) {
           $.each(data.value,function(id,val) {
             tr += '<tr><td>' + id + '</td><td><button class="btn btn-dark show-edit" data-type="adminUser" data-id="' + id + '" data-rights="' + val.join(',') + '"><i class="fa fa-pencil"></i></button> <button class="btn btn-dark delete-adminUser" data-id="' + id + '"><i class="fa fa-trash-o"></i></button></td></tr>'
           });
         } else {
           tr += '<tr><td>Brak</td><td></td></tr>'
         }
         $('.list-adminUsers').html(tr);
       }
       helper.alert(data.success,data.message);
     }
   })
 })

 $(document).on('click','.delete-adminGroup',function() {
   $.ajax({
     url: base_url + 'api/rights/deleteAdminGroup', type: 'post', data: {
       group: $(this).data('id')
     }, success: function(data) {
       if(data.success) {
         tr = '';
         if(Object.keys(data.value).length) {
           $.each(data.value,function(id,val) {
             tr += '<tr><td>' + id + '</td><td><button class="btn btn-dark show-edit" data-type="adminGroup" data-id="' + id + '" data-rights="' + val.join(',') + '"><i class="fa fa-pencil"></i></button> <button class="btn btn-dark delete-adminGroup" data-id="' + id + '"><i class="fa fa-trash-o"></i></button></td></tr>'
           });
         } else {
           tr += '<tr><td>Brak</td><td></td></tr>'
         }
         $('.list-adminGroups').html(tr);
       }
       helper.alert(data.success,data.message);
     }
   })
 })

 $('.edit-apiFile').click(function() {
   $.ajax({
     url: base_url + 'api/settings/apiFile', type: 'post', data: {
       type: $('.select-apiType').val(),
       host: $('.api-local').val(),
       key: $('.api-key').val()
     }, success: function(data) {
       helper.alert(data.success,data.message);
     }
   })
 })

 $('.edit-apiApp').click(function() {
   $.ajax({
     url: base_url + 'api/settings/apiApp', type: 'post', data: {
       ip: $('.app-local').val(),
       port: $('.app-port').val(),
       token: $('.app-token').val(),
       timeout: $('.app-timeout').val()
     }, success: function(data) {
       helper.alert(data.success,data.message);
     }
   })
 })

 $('.edit').click(function() {
   if($(this).data('type') == 'adminGroup') {

   }
 });

 function editGroup(group,rights) {
   $.ajax({
     url: base_url + 'api/rights/editAdminGroup', type: 'post', data: {
       group: group,
       rights: rights
     }, success: function(data) {
       helper.alert(data.success,data.message);
     }
   })
 }
