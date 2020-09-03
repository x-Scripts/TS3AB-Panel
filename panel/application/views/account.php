<section class="no-padding-top no-padding-bottom">
  <div class="row" style="margin-left: 0; margin-right: 0;">
    <div class="col-md-6">
      <div class="block">
        <strong class="d-block">Zmiana hasła</strong>
        <div class="form-group" style="margin-top: 10px;">
          <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-lock" style="margin: auto;"></i></span></div>
            <input type="password" min="1" placeholder="Aktualne hasło" class="form-control password input-width">
            <div class="input-group-prepend"><span class="input-group-text" style="background: transparent; border: 0px;"><i class="fa fa-eye show-password" data-input=".password"></i></span></div>
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-lock" style="margin: auto;"></i></span></div>
            <input type="password" min="1" placeholder="Nowe hasło" class="form-control new-password input-width">
            <div class="input-group-prepend"><span class="input-group-text" style="background: transparent; border: 0px;"><i class="fa fa-eye show-password" data-input=".new-password"></i></span></div>
          </div>
        </div>

        <div class="text-center" style="overflow: auto;">
          <div class="btn-group btn-group-toggle select">
            <button class="btn btn-dark edit-password">Zmień hasło</button>
            <button class="btn btn-dark" onclick="helper.generatePassword('.new-password')">Wygeneruj nowe hasło</button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="block">
        <strong class="d-block">Zmiana dwuetapowej weryfikacji</strong>
        <p class="text-center">Status: <span class="twoAuth-status"><?= $twoAuth['status']; ?></span></p>
        <div class="text-center twoAuth-buttons" style="overflow: auto;">
          <?php if($twoAuth['statusBool']) { ?>
            <button class="btn btn-dark" data-toggle="modal" data-target="#deleteTwoAuth">Usuń weryfikację</button>
          <?php } else { ?>
            <button class="btn btn-dark show-addTwoAuth">Dodaj weryfikację</button>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="block">
        <strong class="d-block">Zapamiętane urządzenia</strong>
        <div class="table-responsive">
          <table class="table table-striped table-hover" style="overflow: auto;">
            <thead>
              <tr>
                <th>Platforma</th>
                <th>Przeglądarka</th>
                <th>Opcje</th>
              </tr>
            </thead>
            <tbody class="remember-list">
              <?php if($remember->num_rows()) {
                foreach($remember->result_array() as $index) {
              ?>
                <tr>
                  <td><?= $cookie == $index['id'] ? 'Ta sesja' : $index['platform']; ?></td>
                  <td><?= $cookie == $index['id'] ? 'Ta sesja' : $index['browser']; ?></td>
                  <td><button class="btn btn-dark"><i class="fa fa-trash-o delete-remember" data-id="<?= $index['id']; ?>"></i></button></td>
                </tr>
              <?php
                  }
                } else { ?>
                <tr>
                  <td></td>
                  <td>Brak</td>
                  <td></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<div id="addTwoAuth" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade">
  <div role="document" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Dodawanie weryfikacji dwuetapowej</strong>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body alert-show"></div>
      <div class="modal-body text-center">
        <img class="qrAddTwoAuth" style="width: 200px; height: 200px;" src="">
        <p class="keyAddTwoAuth"></p>
      </div>
      <div class="modal-body text-center">
        <input type="number" class="form-control tokenAddTwoAuth" placeholder="Wpisz token z aplikacji">
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-secondary">Anuluj</button>
        <button type="button" class="btn btn-primary add-twoAuth">Dodaj weryfikację</button>
      </div>
    </div>
  </div>
</div>
<div id="deleteTwoAuth" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade">
  <div role="document" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Usuwanie weryfikacji dwuetapowej</strong>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <p>Czy napewno chcesz usunąć weryfikację?</p>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-secondary">Anuluj</button>
        <button type="button" data-dismiss="modal" class="btn btn-primary delete-twoAuth">Usuń weryfikację</button>
      </div>
    </div>
  </div>
</div>
