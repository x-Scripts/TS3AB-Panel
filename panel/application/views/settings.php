<section class="no-padding-top no-padding-bottom animated fadeIn">
  <div class="row" style="margin-left: 0; margin-right: 0;">
    <div class="col-md-6">
      <div class="block">
        <strong class="d-block">Typ plików aplikacji</strong>
        <select class="form-group form-control select-apiType">
          <option value="localhost" <?= $settings['apiType'] == 'localhost' ? 'selected' : ''; ?>>Lokalny serwer</option>
          <option value="externalhost" <?= $settings['apiType'] == 'externalhost' ? 'selected' : ''; ?>>Zewnętrzny serwer</option>
        </select>
        <div class="externalhost" style="margin-top: 10px;<?= $settings['apiType'] == 'externalhost' ? '' : ' display: none;'; ?>">
          <div class="form-group">
            <label class="form-control-label">Adres api</label>
            <input type="url" class="form-control api-local" placeholder="http://127.0.0.1/api/" value="<?= $settings['apiLocal']; ?>">
          </div>
          <div class="form-group">
            <label class="form-control-label">Klucz api</label>
            <input type="text" class="form-control api-key" placeholder="Wygeneruj klucz" value="<?= $settings['apiKey'] == null ? '' : $settings['apiKey']; ?>">
          </div>
          <div class="form-group text-center">
            <button class="btn btn-dark" onclick="helper.generatePassword('.api-key',30);">Stwórz nowy klucz</button>
            <button class="btn btn-dark copy-key">Skopiuj klucz</button>
          </div>

        </div>
        <div class="text-center">
          <button class="btn btn-dark edit-apiFile">Zapisz</button>
        </div>
      </div>
      <div class="block">
        <strong class="d-block">Połączenie z aplikacją</strong>
        <div class="i-checks"><input type="checkbox" class="checkbox-template appApi-usage"<?= $settings['appApi'] ? ' checked' : ''; ?>><label for="checkboxCustom1">Użyj api plików</label></div>
        <div class="form-group appApi-usage-show"<?= $settings['appApi'] ? ' style="display: none;"' : ''; ?>>
          <label class="form-control-label">Adres serwera aplikacji</label>
          <input type="text" class="form-control app-local" placeholder="np. 127.0.0.1" value="<?= $settings['host'] == null ? '' : $settings['host']; ?>">
        </div>
        <div class="form-group">
          <label class="form-control-label">Port aplikacji</label>
          <input type="text" class="form-control app-port" placeholder="Port domyślny 58913" value="<?= $settings['port'] == null ? '' : $settings['port']; ?>">
        </div>
        <div class="form-group">
          <label class="form-control-label">Token aplikacji</label>
          <input type="text" class="form-control app-token" placeholder="Zawiera id użytkowinika i token" value="<?= $settings['apiKey'] == null ? '' : $settings['apiToken']; ?>">
        </div>
        <div class="form-group">
          <label class="form-control-label">Czas zapytania(sekundy)</label>
          <input type="number" class="form-control app-timeout" placeholder="np. 5" value="<?= $settings['timeout']; ?>">
        </div>
        <div class="text-center">
          <button class="btn btn-dark edit-apiApp">Zapisz</button>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="block">
        <strong class="d-block">Wszystkie uprawnienia do wszystkich botów</strong>
        <div class="form-group">
          <label class="form-control-label">Id grup uprawnionych</label>
          <input type="text" class="form-control edit-ownerGroup" placeholder="np. 34,24" value="<?= implode(',',$settings['ownerGroup']); ?>">
        </div>
        <p>Unikalne ID użytkowników</p>
        <button class="btn btn-dark" data-toggle="modal" data-target="#addOwnerUser">Dodaj użytkownika</button>
        <div class="table-responsive text-center">
          <table class="table table-striped table-hover" style="overflow: auto;">
            <thead>
              <tr>
                <th>Unikalne ID</th>
                <th>Opcje</th>
              </tr>
            </thead>
            <tbody class="list-ownerUsers">
              <?php foreach($settings['ownerUserUID'] as $index) { ?>
                <tr>
                  <td><?= $index; ?></td>
                  <td><button class="btn btn-dark delete-ownerUser" data-id="<?= $index; ?>"><i class="fa fa-trash-o"></i></button></td>
                </tr>
              <?php } if(!count($settings['ownerUserUID'])) { ?>
                <tr>
                  <td>Brak</td>
                  <td></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="block">
        <strong class="d-block">Uprawnienia dla użytkowników do wszystkich botów</strong>
        <button class="btn btn-dark" data-toggle="modal" data-target="#addUser">Dodaj użytkownika</button>
        <p>Unikalne ID użytkowników</p>
        <div class="table-responsive text-center">
          <table class="table table-striped table-hover" style="overflow: auto;">
            <thead>
              <tr>
                <th>Unikalne ID</th>
                <th>Opcje</th>
              </tr>
            </thead>
            <tbody class="list-adminUsers">
              <?php foreach($settings['adminUsers'] as $userid => $index) { ?>
                <tr>
                  <td><?= $userid; ?></td>
                  <td><button class="btn btn-dark show-edit" data-type="adminUser" data-id="<?= $userid; ?>" data-rights="<?= implode(',',$index); ?>"><i class="fa fa-pencil"></i></button> <button class="btn btn-dark delete-adminUser" data-id="<?= $userid; ?>"><i class="fa fa-trash-o"></i></button></td>
                </tr>
              <?php } if(!count($settings['adminUsers'])) { ?>
                <tr>
                  <td>Brak</td>
                  <td></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="block">
        <strong class="d-block">Uprawnienia dla danych grup do wszystkich botów</strong>
        <button class="btn btn-dark" data-toggle="modal" data-target="#addGroup">Dodaj grupę</button>
        <div class="table-responsive text-center">
          <table class="table table-striped table-hover" style="overflow: auto;">
            <thead>
              <tr>
                <th>ID grupy</th>
                <th>Opcje</th>
              </tr>
            </thead>
            <tbody class="list-adminGroups">
              <?php foreach($settings['adminGroups'] as $groupID => $index) { ?>
                <tr>
                  <td><?= $groupID; ?></td>
                  <td><button class="btn btn-dark show-edit" data-type="adminGroup" data-id="<?= $groupID; ?>" data-rights="<?= implode(',',$index); ?>"><i class="fa fa-pencil"></i></button> <button class="btn btn-dark delete-adminGroup" data-id="<?= $groupID; ?>"><i class="fa fa-trash-o"></i></button></td>
                </tr>
              <?php } if(!count($settings['adminGroups'])) { ?>
                <tr>
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

<div id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade">
  <div role="document" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Edytowanie: <span class="id-edit"></span></strong>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div style="height: 400px; overflow: auto;">
          <?= generateAllRightsHtml($this->config->item('allRights')); ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cancel-edit">Anuluj</button>
        <button type="button" class="btn btn-primary edit">Edytuj</button>
      </div>
    </div>
  </div>
</div>
<div id="addGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade">
  <div role="document" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Dodawanie grupy</strong>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="alert-add"></div>
        <div class="form-group">
          <label class="form-control-label">ID grupy</label>
          <input class="form-control group-id" placeholder="np. 23">
        </div>
        <div style="height: 400px; overflow: auto;">
          <?= generateAllRightsHtml($this->config->item('allRights'),'','','check-addGroup'); ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
        <button type="button" class="btn btn-primary addGroup">Dodaj grupę</button>
      </div>
    </div>
  </div>
</div>
<div id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade">
  <div role="document" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Dodawanie użytkownika</strong>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="alert-user"></div>
        <div class="form-group">
          <label class="form-control-label">Unikalne ID użytkownika</label>
          <input class="form-control user-id" placeholder="np. cDhs6cfnGgOdvifIfxrxIjnuFRk=">
        </div>
        <div style="height: 400px; overflow: auto;">
          <?= generateAllRightsHtml($this->config->item('allRights'),'','','check-addUser'); ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
        <button type="button" class="btn btn-primary addUser">Dodaj użytkownika</button>
      </div>
    </div>
  </div>
</div>
<div id="addOwnerUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade">
  <div role="document" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Dodawanie użytkownika</strong>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="alert-ownerUser"></div>
        <div class="form-group">
          <label class="form-control-label">Unikalne ID użytkownika</label>
          <input class="form-control ownerUser-id" placeholder="np. cDhs6cfnGgOdvifIfxrxIjnuFRk=">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
        <button type="button" class="btn btn-primary addOwnerUser">Dodaj użytkownika</button>
      </div>
    </div>
  </div>
</div>
