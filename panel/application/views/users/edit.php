<section class="no-padding-top no-padding-bottom  animated fadeIn">
  <?php if($permission['editAccountTwoAuth'] || $permission['editLimitBots'] || $permission['editAccountLogin'] || $permission['editAccountBotRights'] || $permission['editAccountPassword'] || $permission['editAccountPerms']) { ?>
    <?php if($permission['editAccountTwoAuth'] || $permission['editLimitBots'] || $permission['editAccountLogin'] || $permission['editAccountBotRights'] || $permission['editAccountPassword']) { ?>
      <div class="row">
        <?php if($permission['editAccountLogin'] || $permission['editLimitBots'] || $permission['editAccountTwoAuth']) { ?>
          <div class="col-md-6">
            <?php if($permission['editAccountLogin']) { ?>
              <div class="block loader text-center">
                <div class="lds-ring">
                  <div></div>
                  <div></div>
                  <div></div>
                  <div></div>
                </div>
              </div>
              <div class="block show" style="display: none;">
                <strong class="d-block">Zmiana loginu</strong>
                <div class="form-group" style="margin-top: 10px;">
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-user" style="margin: auto;"></i></span></div>
                    <input type="text" placeholder="Nazwa użytkownika" class="form-control login input-width">
                  </div>
                </div>
                <div class="text-center">
                  <button class="btn btn-dark edit-login">Zmień login</button>
                </div>
              </div>
            <?php } if($permission['editLimitBots']) { ?>
                <div class="block loader text-center">
                  <div class="lds-ring">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                  </div>
                </div>
                <div class="block show" style="display: none;">
                  <strong class="d-block">Zmiana limitu</strong>
                  <div class="form-group" style="margin-top: 10px;">
                    <select class="form-control select-limit">
                      <option disabled selected>Wybierz limit botów</option>
                      <option value="unlimited">Bez limitu</option>
                      <option value="limited">Określony limit</option>
                    </select>
                  </div>
                  <div class="form-group limit-bots-show" style="display: none;">
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-level-up" style="margin: auto;"></i></span></div>
                      <input type="number" min="1" placeholder="Limit botów" class="form-control group-id limit-bots">
                    </div>
                  </div>
                  <div class="text-center">
                    <button class="btn btn-dark edit-limitBots">Zmień limit</button>
                  </div>
                </div>
              <?php } if($permission['editAccountTwoAuth']) { ?>
                <div class="block loader text-center">
                  <div class="lds-ring">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                  </div>
                </div>
                <div class="block show" style="display: none;">
                  <strong class="d-block">Zmiana dwuetapowej weryfikacji</strong>
                  <p class="text-center">Status: <span class="twoAuth-status"></span></p>
                  <div class="text-center twoAuth-buttons" style="overflow: auto;">
                  </div>
                </div>
              <?php } ?>
          </div>
        <?php } if($permission['editAccountPassword'] || $permission['editAccountBotRights']) { ?>
          <div class="col-md-6">
            <?php if($permission['editAccountPassword']) { ?>
              <div class="block loader text-center">
                <div class="lds-ring">
                  <div></div>
                  <div></div>
                  <div></div>
                  <div></div>
                </div>
              </div>
              <div class="block show" style="display: none;">
                <strong class="d-block">Zmiana hasła</strong>
                <div class="form-group" style="margin-top: 10px;">
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-lock" style="margin: auto;"></i></span></div>
                    <input type="password" min="1" placeholder="Nowe hasło" class="form-control password input-width">
                    <div class="input-group-prepend"><span class="input-group-text" style="background: transparent; border: 0px;"><i class="fa fa-eye show-password"></i></span></div>
                  </div>
                </div>
                <div class="text-center" style="overflow: auto;">
                  <div class="btn-group btn-group-toggle select">
                    <button class="btn btn-dark edit-password">Zmień hasło</button>
                    <button class="btn btn-dark" onclick="helper.generatePassword('.password')">Wygeneruj nowe hasło</button>
                  </div>
                </div>
              </div>
            <?php } if($permission['editAccountBotRights']) { ?>
              <div class="block loader text-center">
                <div class="lds-ring">
                  <div></div>
                  <div></div>
                  <div></div>
                  <div></div>
                </div>
              </div>
              <div class="block show" style="display: none;">
                <strong class="d-block">Zmiana uprawnień dla botów</strong>
                <div class="form-group" style="margin-top: 10px;">
                  <select class="form-control select-rights">
                    <option disabled selected>Wybierz typ uprawnień</option>
                    <option value="all">Wszystkie</option>
                    <option value="assign">Przyznaj poszczególne</option>
                  </select>
                </div>
                <div class="block assign-rights" style="display: none;">
                  <div style="overflow-x: hidden; overflow: auto; max-height: 300px;">
                    <?= generateAllRightsHtml($this->config->item('allRights')); ?>
                  </div>
                </div>
                <div class="text-center">
                  <button class="btn btn-dark edit-rights">Zmień uprawnienia dla botów</button>
                </div>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
      </div>
    <?php } if($permission['editAccountPerms']) { ?>
      <div class="row loader">
        <div class="col-md-12">
          <div class="block text-center">
            <div class="lds-ring">
              <div></div>
              <div></div>
              <div></div>
              <div></div>
            </div>
          </div>
        </div>
      </div>
      <div class="row show" style="display: none;">

        <div class="col-md-12">
          <div class="block">
            <h3 class="title text-center">Zmiana uprawnień do panelu</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="block">
            <strong class="d-block">Ogólne</strong>
            <div style="overflow: hidden; height: 200px;">
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="viewLogs">
                <label for="checkboxCustom1">Dostęp do logów</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="startStopApp">
                <label for="checkboxCustom1">Włączenie/wyłączenie aplikacji</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="viewUsage">
                <label for="checkboxCustom1">Wyświetlanie zużycia</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="editSettings">
                <label for="checkboxCustom1">Edytowanie ustawień</label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="block">
            <strong class="d-block">Lista botów</strong>
            <div style="overflow: auto; height: 200px;">
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="viewAllBots">
                <label for="checkboxCustom1">Dostęp do listy wszystich botów</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="deleteBots">
                <label for="checkboxCustom1">Usuwanie botów</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="addBotUsers">
                <label for="checkboxCustom1">Przypisywanie botów użytkownikom</label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="block" style="">
            <strong class="d-block">Edycja botów</strong>
            <div style="overflow: auto; height: 200px;">
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="addUsersBot">
                <label for="checkboxCustom1">Dodawanie użytkowników</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="editSimpleBot">
                <label for="checkboxCustom1">Dostęp do modyfikowania trybu prostego</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="editAdvancedBot">
                <label for="checkboxCustom1">Dostęp do modyfikowania trybu zaawansowanego</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="editExpertBot">
                <label for="checkboxCustom1">Dostęp do modyfikowania trybu ekspert</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="editRightsBot">
                <label for="checkboxCustom1">Modyfikowanie uprawnień bota</label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="block">
            <strong class="d-block">Player Botów</strong>
            <div style="overflow: auto; height: 150px;">
              <!-- <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="managePlaylist">
                <label for="checkboxCustom1">Zarządzanie listami odtwarzania</label>
              </div> -->
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="playSong">
                <label for="checkboxCustom1">Uruchamianie utworów</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="manageMusic">
                <label for="checkboxCustom1">Zarządzanie muzyką</label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="block">
            <strong class="d-block">Tworzenie bota</strong>
            <div style="overflow: auto; height: 150px;">
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="createSimple">
                <label for="checkboxCustom1">Dostęp do trybu prostego</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="createAdvanced">
                <label for="checkboxCustom1">Dostęp do trybu zaawansowanego</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="createExpert">
                <label for="checkboxCustom1">Dostęp do trybu ekspert</label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="block">
            <strong class="d-block">Historia logowań</strong>
            <div style="overflow: auto; height: 150px;">
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="viewAllHistory">
                <label for="checkboxCustom1">Dostęp do wszystkich historii</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="clearHistory">
                <label for="checkboxCustom1">Usuwanie wszystkich historii</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="viewFullIP">
                <label for="checkboxCustom1">Pokazywanie całego ip</label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="block">
            <strong class="d-block">Użytkownicy</strong>
            <div style="overflow: auto; height: 200px;">
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="viewAccountsList">
                <label for="checkboxCustom1">Dostęp do listy użytkowników</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="deleteAccount">
                <label for="checkboxCustom1">Usuwanie użytkowników</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="addAccount">
                <label for="checkboxCustom1">Dodawanie użytkowników</label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="block">
            <strong class="d-block">Edycja użytkowników</strong>
            <div style="overflow: auto; height: 200px;">
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="editAccountPassword">
                <label for="checkboxCustom1">Zmiana hasła</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="editAccountLogin">
                <label for="checkboxCustom1">Zmiana loginu</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="editLimitBots">
                <label for="checkboxCustom1">Zmiana limitu botów</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="editAccountTwoAuth">
                <label for="checkboxCustom1">Zmiana autoryzacji dwuetapowej</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="editAccountBotRights">
                <label for="checkboxCustom1">Modyfikowanie uprawnień do botów</label>
              </div>
              <div class="i-checks one-line">
                <input type="checkbox" class="checkbox-template perms" value="editAccountPerms">
                <label for="checkboxCustom1">Modyfikowanie uprawnień do panelu</label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 text-center">
          <div class="block">
            <button class="btn btn-dark edit-permsDash">Zmień uprawnienia do panelu</button>
          </div>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
  <div class="col-md-12">
    <div class="block">
      <div class="alert alert-danger text-center">
        Nie posiadasz dostępu!
      </div>
    </div>
  </div>
  <?php } ?>
</section>
<?php if($permission['editAccountTwoAuth']) { ?>
  <div id="showTwoAuth" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Weryfikacja dwuetapowa</strong>
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body text-center">
          <img class="qrTwoAuth" style="width: 200px; height: 200px;" src="">
          <p class="keyTwoAuth"></p>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-secondary">Zamknij</button>
        </div>
      </div>
    </div>
  </div>
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
<?php } ?>
