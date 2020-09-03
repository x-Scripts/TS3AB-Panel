
<section class="no-padding-top no-padding-bottom  animated fadeIn">
  <?php if($permission['addAccount']) { ?>
    <div class="col-md-12">
      <div class="block">
        <button class="btn btn-dark" onclick="window.location.href=base_url + 'users/create'"><i class="fa fa-plus"></i> Dodaj użytkownika</button>
      </div>
    </div>
  <?php } if($permission['viewAccountsList'] || $permission['editAccountLogin'] || $permission['editAccountPerms'] || $permission['editAccountTwoAuth'] || $permission['editAccountPassword'] || $permission['editAccountBotRights'] || $permission['editLimitBots'] || $permission['deleteAccount']) { ?>
    <div class="col-md-12">
      <div class="block text-center">
        <div class="loader">
          <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        </div>
        <div class="table-responsive list" style="display: none;">
        </div>
      </div>
    </div>
  <?php } ?>
</section>
<?php if($permission['deleteAccount']) { ?>
  <div id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Usuwanie użytkownika</strong>
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <p>Czy napewno chcesz usunąć użytkownika: <span class="username text-bold"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary cancel-delete-user">Anuluj</button>
          <button type="button" class="btn btn-primary delete-user">Usuń użytkownika</button>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
