<section class="no-padding-top no-padding-bottom  animated fadeIn">

<?php if($logs['success']) { ?>
  <div class="col-md-10">
    <div class="block">
      <div class="form-group row">
        <label class="col-sm-4 form-control-label">
          <select class="form-control select-logs">
            <option value selected>Wybierz dzień</option>
              <?= $logs['response']; ?>
          </select>
        </label>
        <div class="col-sm-8">
          <div style="overflow: auto;">
            <div class="btn-group btn-group-toggle select">
              <button class="btn btn-dark load-logs">Załaduj</button>
              <button class="btn btn-dark clear-show">Wyczyść wyświetlanie</button>
              <button class="btn btn-dark delete-log">Usuń logi</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .show-log-list {
      height: 500px;
      overflow: scroll;
    }
  </style>
    <div class="col-md-12 block-list">
      <div class="block show-logs">
        <div class="title date" style="display: none;">

        </div>
        <div class="loading" style="text-align: center; display: none;">

        </div>
        <pre class="show-log-list" style="display: none;"></pre>
      </div>
    </div>
<?php } else { ?>
  <div class="col-md-12">
    <div class="block">
      <div class="alert alert-danger text-center">
        <?= $logs['response']; ?>
      </div>
    </div>
  </div>
<?php } ?>


</section>
