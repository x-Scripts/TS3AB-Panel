<section class="no-padding-top no-padding-bottom  animated fadeIn">
  <div class="col-md-12">
    <div class="block">
      <p>Wybierz tryb tworzenia</p>
      <div style="overflow: auto;">
        <div class="btn-group btn-group-toggle select" data-toggle="buttons">
          <?php if($permission['createSimple']) { ?>
            <label class="btn btn-info active">
              <input type="radio" name="options" class="check-type" data-id="simple" autocomplete="off" checked> Prosty
            </label>
          <?php } if($permission['createAdvanced']) { ?>
            <label class="btn btn-warning">
              <input type="radio" name="options" class="check-type" data-id="advanced" autocomplete="off"> Zaawansowany
            </label>
          <?php } if($permission['createExpert']) { ?>
            <label class="btn btn-primary">
              <input type="radio" name="options" class="check-type" data-id="expert" autocomplete="off"> Ekspert
            </label>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="block">
      <div class="title">
        <strong class="d-block">Tryb <strong class="settings-type">Prosty - tworzenie bota</strong></strong>
      </div>
      <div class="col-md-12">
        <strong class="d-block">Generalne ustawienia</strong>
        <div class="block">

          <div class="i-checks">
            <input type="checkbox" class="checkbox-template start-bot-application" checked>
            <label for="checkboxCustom1">Uruchamiaj bota przy starcie aplikacji</label>
          </div>
          <div class="i-checks">
            <input type="checkbox" class="checkbox-template load-avatar" checked>
            <label for="checkboxCustom1">Ładowanie okładki piosenki do awataru</label>
          </div>
          <div class="i-checks">
            <input type="checkbox" class="checkbox-template title-song-desc" checked>
            <label for="checkboxCustom1">Nazwa piosenki w opisie bota</label>
          </div>
          <p>Język bota</p>
          <select class="form-control mb-3 col-sm-3 bot-language" style="margin-top: -10px;">
            <option value="pl">Polski</option>
            <option value="cs">Czeski</option>
            <option value="da">Duński</option>
            <option value="en">Angielski</option>
            <option value="fr">Francuski</option>
            <option value="de">Niemiecki</option>
            <option value="hu">Węgierski</option>
            <option value="ru">Rosyjski</option>
            <option value="es">Hiszpański</option>
            <option value="es-ar">Hiszpański (Argentyna)</option>
            <option value="th">Tajski</option>
          </select>
        </div>
        <strong class="d-block">Połączenie z serwerem</strong>
        <div class="block">
          <div class="form-group row">
            <div class="col-sm-6">
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-pencil" style="margin: auto;"></i></span></div>
                <input type="text" placeholder="Nazwa bota" maxlength="32" class="form-control bot-name">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-6">
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-user" style="margin: auto;"></i></span></div>
                <input type="number" min="1" placeholder="ID grupy uprawnionej do korzystania z bota" class="form-control group-id input-width">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-6">
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-home" style="margin: auto;"></i></span></div>
                <input type="number" min="1" placeholder="ID domyślnego kanału bota (opcjonalnie)" class="form-control channel-id input-width">
              </div>
            </div>

          </div>
          <?php if($permission['createAdvanced'] || $permission['createExpert']) { ?>
            <div class="form-group row advanced expert" style="display: none;">
              <div class="col-sm-6">
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-lock" style="margin: auto;"></i></span></div>
                  <input type="text" placeholder="Hasło kanału (opcjonalnie)" class="form-control channel-password input-width">
                </div>
              </div>
              <div class="col-sm-2">
                <div class="i-checks">
                  <input type="checkbox" class="checkbox-template channel-password-autohash">
                  <label for="checkboxCustom1">AutoHash</label>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="i-checks">
                  <input type="checkbox" class="checkbox-template channel-password-hash">
                  <label for="checkboxCustom1">Hash</label>
                </div>
              </div>
            </div>
          <?php } ?>
          <div class="form-group row">
            <div class="col-sm-6">
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fab fa-teamspeak" style="margin: auto;"></i></span></div>
                <input type="text" placeholder="Adres serwera" class="form-control server-ip">
              </div>
            </div>
          </div>
          <?php if($permission['createAdvanced'] || $permission['createExpert']) { ?>
            <div class="form-group row advanced expert" style="display: none;">
              <div class="col-sm-6">
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-lock" style="margin: auto;"></i></span></div>
                  <input type="text" placeholder="Hasło serwera (opcjonalnie)" class="form-control server-password">
                </div>
              </div>
              <div class="col-sm-2">
                <div class="i-checks">
                  <input type="checkbox" class="checkbox-template server-password-autohash">
                  <label for="checkboxCustom1">AutoHash</label>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="i-checks">
                  <input type="checkbox" class="checkbox-template server-password-hash">
                  <label for="checkboxCustom1">Hash</label>
                </div>
              </div>
            </div>
            <div class="advanced expert" style="display: none;">
              <p>Wersja klienta ts3 bota</p>
              <select class="form-control col-sm-6 client-version" style="margin-top: -10px;">
                <?= build_versions(); ?>
              </select>
              <p style="margin-top: 10px;">Odznaki (opcjonalnie)</p>
              <div class="i-checks" style="margin-top: -10px;">
                <input type="checkbox" class="checkbox-template overwolf">
                <label for="checkboxCustom1">Overwolf</label>
              </div>
              <select class="form-control mb-3 col-sm-3 badges">
                <?= build_badges(); ?>
              </select>
              <ul class="badges-list" style="list-style-type: square;">
              </ul>
            </div>
          <?php } ?>
        </div>
        <strong class="d-block">Uprawnienia do bota</strong>
        <div class="block">
          <div class="col-sm-6 row" style="overflow: auto; max-height: 500px;">
            <?= $rights; ?>
          </div>
        </div>
        <?php if($permission['createAdvanced'] || $permission['createExpert']) { ?>
          <div class="advanced expert" style="display: none;">
            <strong class="d-block">Ustawienia audio</strong>
            <div class="block">
              <div class="form-group row">
                <div class="col-sm-6">
                  <p>Domyślna głośność muzyki: <span class="volume text-color">25</span></p>
                  <input type="range" min="1" max="100" value="25" class="range change-volume">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-6">
                  <p style="margin-top: 2%;">Maskymalna głośność dostępna dla użytkownika: <span class="volume-max text-color">100</span></p>
                  <input type="range" min="1" max="100" value="100" class="range change-volume-max">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-6">
                  <p style="margin-top: 2%;">Jakość dźwięku: <span class="text-color"><span class="bitrate">48</span> bitów</span></p>
                  <input type="range" min="1" max="96" value="48" class="range change-bitrate">
                </div>
              </div>
              <div style="overflow: auto; margin-top: 1%;">
                <div class="btn-group btn-group-toggle select-bitrate" data-toggle="buttons">
                  <label class="btn btn-primary poor">
                    <input type="radio" name="options" class="get-bitrate" value="16" autocomplete="off"> Bardzo słaba
                  </label>
                  <label class="btn btn-primary very-poor">
                    <input type="radio" name="options" class="get-bitrate" value="24" autocomplete="off"> Słaba
                  </label>
                  <label class="btn btn-warning okey">
                    <input type="radio" name="options" class="get-bitrate" value="32" autocomplete="off"> W porządku
                  </label>
                  <label class="btn btn-warning good active">
                    <input type="radio" name="options" class="get-bitrate" value="48" autocomplete="off" checked> Dobra
                  </label>
                  <label class="btn btn-success very-good">
                    <input type="radio" name="options" class="get-bitrate" value="64" autocomplete="off"> Bardzo dobra
                  </label>
                  <label class="btn btn-success best">
                    <input type="radio" name="options" class="get-bitrate" value="96" autocomplete="off"> Najlepsza
                  </label>
                </div>
              </div>
              <?php if($permission['createExpert']) { ?>
                <div class="expert-one" style="display: none;">
                  <p style="margin-top: 2%;">
                    Dozwolny przedział głośności: <span id="amount" class="text-color"></span>
                  </p>
                  <div class="form-group row" style="margin-top: -1%;">
                    <div class="col-sm-6">
                      <div class="slider-range"></div>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        <?php } if($permission['createExpert']) { ?>
          <div class="expert-one" style="display: none;">
            <strong class="d-block">Komendy</strong>
            <div class="block">
              <div class="i-checks">
                <input type="checkbox" class="checkbox-template colored-chat" checked>
                <label for="checkboxCustom1">Kolorowy chat</label>
              </div>
              <p>Dopasowanie komend</p>
              <div class="form-group row">
                <div class="col-sm-3">
                  <select class="form-control command-matcher" style="margin-top: -10px;">
                    <option value="ic3">ic3</option>
                  </select>
                </div>
              </div>
              <p>Długość wiadomości</p>
              <div class="form-group row">
                <div class="col-sm-3">
                  <select class="form-control long-message" style="margin-top: -10px;">
                    <option value="split">Split</option>
                    <option value="drop">Drop</option>
                  </select>
                </div>
              </div>
              <p>Limit podziału długich wiadomości</p>
              <div class="form-group row">
                <div class="col-sm-3" style="margin-top: -10px;">
                <div class="quantity">
                    <input type="number" class="form-control long-message-split-limit" min="1" value="1">
                    <div class="quantity-nav">
                      <div class="quantity-button quantity-up" data-id="long-message-split-limit">+</div>
                      <div class="quantity-button quantity-down" data-id="long-message-split-limit">-</div>
                    </div>
                  </div>
                </div>
              </div>
              <p>Złożoność poleceń</p>

              <div class="form-group row">
                <div class="col-sm-3" style="margin-top: -10px;">
                  <div class="quantity">
                    <input type="number" class="form-control command-complexity" min="1" value="64">
                    <div class="quantity-nav">
                      <div class="quantity-button quantity-up" data-id="command-complexity">+</div>
                      <div class="quantity-button quantity-down" data-id="command-complexity">-</div>
                    </div>
                  </div>
                </div>
              </div>
              <p>Własne komendy</p>
              <button class="btn btn-dark" data-toggle="modal" data-target="#aliasAdd" style="margin-top: -10px;">+ Dodaj własną komendę</button>
              <ul class="alias-list" style="list-style-type: square;">
              </ul>
            </div>
            <strong class="d-block">Eventy (opcjonalnie)</strong>
            <div class="block">
              <div class="form-group row">
                <div class="col-sm-6">
                  <label>Połączenie bota z serwerem</label>
                  <input type="text" placeholder="np. !bot commander on" class="form-control onconnect">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-6">
                  <label>Rozłączenie bota z serwerem</label>
                  <input type="text" placeholder="np. !bot commander off" class="form-control ondisconnect">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-6">
                  <label>Bezczynność bota</label>
                  <input type="text" placeholder="np. !bot description set zzz" class="form-control onidle">
                </div>
                <div class="col-sm-6">
                  <label>Czas bezczynności(ISO-8601)</label>
                  <input type="text" placeholder="np. 30s" class="form-control idletime">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-6">
                  <label>Ostani klient opuszcza kanał</label>
                  <input type="text" placeholder="np. !stop" class="form-control onalone">
                </div>
                <div class="col-sm-6">
                  <label>Opóźnienie od opuszczenia(ISO-8601)</label>
                  <input type="text" placeholder="np. 1m" class="form-control alone-delay">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-6">
                  <label>Pierwszy klient łączy się z kanałem</label>
                  <input type="text" placeholder="np. !play <url>" class="form-control onparty">
                </div>
                <div class="col-sm-6">
                  <label>Opóźnienie od połączenia się klienta(ISO-8601)</label>
                  <input type="text" placeholder="np. 3m30s" class="form-control party-delay">
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
      <div class="offset-md-5 col-md-4">
        <button class="btn btn-dark create-bot create-simple">Stwórz bota</button>
      </div>
    </div>
  </div>
</section>
<?php if($permission['createExpert']) { ?>
  <div id="aliasAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Dodawanie komendy</strong>
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text"><apan style="margin: auto;">!</apan></span></div>
                  <input type="text" placeholder="Własna komenda" class="form-control cmd-alias-1">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text"><apan style="margin: auto;">!</apan></span></div>
                  <input type="text" placeholder="Wykonanie komendy" class="form-control cmd-alias-2">
                </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-secondary">Zamknij</button>
          <button type="button" data-dismiss="modal" class="btn btn-primary alias-add">Dodaj komendę</button>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
