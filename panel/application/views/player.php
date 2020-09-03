<!-- <section class="no-padding-top no-padding-bottom animated fadeIn loader">
  <div class="col-md-12">
    <div class="block text-center">
      <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
    </div>
  </div>
</section> -->
<section class="no-padding-top no-padding-bottom animated fadeIn">
  <div class="col-md-12">
    <div class="block">
      <?php if($permission['manageMusic']) { ?>
        <h5 class="title">Zarządzanie muzyką</h5>
        Odtwarzana muzyka: <span class="title title-song">Brak</span>
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <label class="form-control-label"><span class="timeSong">00:00/00:00</span></label>
              <input type="range" class="range seek-song" min="0" max="0" step="1">
            </div>
            <div class="text-center">
              <button class="btn btn-dark repeat" data-status="off"><i id="icon-repeat" class="mdi mdi-repeat-off mdi-24px"></i></button>
              <button class="btn btn-dark random"><i id="icon-random" class="mdi mdi-shuffle-disabled mdi-24px"></i></button>
              <button class="btn btn-dark prev"><i class="mdi mdi-skip-previous mdi-24px"></i></button>
              <button class="btn btn-dark play-pause"><i id="icon-play-pause" class="mdi mdi-play mdi-24px"></i></button>
              <button class="btn btn-dark stop"><i class="mdi mdi-stop mdi-24px"></i></button>
              <button class="btn btn-dark next"><i class="mdi mdi-skip-next mdi-24px"></i></button>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-control-label">Głośność: <span class="volume">0</span></label>
              <input type="range" class="range change-volume" min="0" value="0" max="100" step="1">
            </div>
          </div>
        </div>
      <?php } if($permission['playSong']) { ?>
        <div class="row" style="margin-top: 10px;">

          <div class="col-md-10">
            <p>Odtwarzanie muzyki</p>
            <div class="input-group" style="margin-top: -1%;">
              <input type="text" class="form-control song-url" placeholder="np. https://youtube.com">
              <div class="text-center" style="margin-left: 5px;">
                <button class="btn btn-dark play-song">Odtwórz</button>
                <button class="btn btn-dark add-song">Dodaj do kolejki</button>
              </div>
              <div class="col-md-12 row">
                <label class="form-control-label">Można odtwarzać utwory z youtube,soundcloud,twitch i stacje radiowe</label>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <!-- <div class="col-md-12">
    <div class="block">
      <h5 class="title">Listy odtwarzania</h5>
      <div class="row">

        <div class="col-md-10">
          <div class="input-group" style="margin-top: -1%;">
            <select class="form-control">
              <option value disabled selected></option>
              <option value="yt">yt</option>
            </select>
            <div class="text-center" style="margin-left: 5px;">
              <button class="btn btn-dark list-play">Odtwórz</button>
              <button class="btn btn-dark show-listDelete">Usuń</button>
              <button class="btn btn-dark show-listCreate">Dodaj</button>
              <button class="btn btn-dark show-listEdit">Edytuj</button>
            </div>

          </div>
        </div>
      </div>
      <div class="row" style="margin-top: 10px;">
        <div class="col-md-10">


        </div>
      </div>
    </div>
  </div> -->
</section>
