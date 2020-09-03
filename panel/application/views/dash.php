<section class="no-padding-top no-padding-bottom  animated fadeIn">
  <?php if($permission['startStopApp']) { ?>
    <div class="col-md-12">
      <div class="block">
        <button class="btn btn-dark app-start"><i class="fa fa-power-off power-on"></i> Włącz aplikację</button>
        <button class="btn btn-dark app-stop"><i class="fa fa-power-off power-off"></i> Wyłącz aplikację</button>
        <button class="btn btn-dark app-restart"><i class="fa fa-repeat restart"></i> Restartuj aplikację</button>
      </div>
    </div>
  <?php } ?>
  <div class="col-md-12">
    <div class="block list">

    </div>
  </div>
</section>
<?php if($permission['deleteBots']) { ?>
  <div id="deleteBot" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Usuwanie bota</strong>
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <p>Czy napewno chcesz usunąć bota: <span class="botID text-bold"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary cancel-deleteBot">Anuluj</button>
          <button type="button" class="btn btn-primary delete-bot">Usuń bota</button>
        </div>
      </div>
    </div>
  </div>
<?php } if($permission['addBotUsers']) { ?>
  <div id="addBotUsers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Przypisywanie bota: <span class="bot-name text-bold"></span></strong>
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <p>Lista użytkowników</p>
          <div class="account-list">

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary cancel-addBotUsers">Anuluj</button>
          <button type="button" class="btn btn-primary edit-botUsers">Zapisz</button>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
