<section class="no-padding-top no-padding-bottom animated fadeIn loader">
  <div class="col-md-12">
    <div class="block text-center">
      <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
    </div>
  </div>
</section>
<section class="no-padding-top no-padding-bottom">
  <div class="col-md-12 show" style="display: none;">
    <div class="block">
      <div class="text-center">
        <h2 class="title">Zużycie aplikacji</h2>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="line-chart chart text-center">
            <div class="title"><strong>Zużycie cpu</strong></div>

            <canvas id="cpu"></canvas>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="line-chart chart text-center">
            <div class="title"><strong>Zużycie ram</strong></div>

            <canvas id="ram"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12 show" style="display: none;">
    <div class="block">
      <div class="text-center">
        <h2 class="title">Zużycie serwera</h2>
      </div>
      <div class="row">
        <div class="col-lg-4">
          <div class="line-chart chart text-center">
            <div class="title"><strong>Zużycie cpu</strong></div>

            <canvas id="cpuServer"></canvas>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="line-chart chart text-center">
            <div class="title"><strong>Zużycie ram</strong></div>

            <canvas id="ramServer"></canvas>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="line-chart chart text-center">
            <div class="title"><strong>Zużycie dysku</strong></div>

            <canvas id="diskUsage"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
