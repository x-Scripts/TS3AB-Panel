<?php
$url = parse_url(base_url());
$url = '<a href="'.$url['scheme'].'://'.$url['host'].'">'.$url['host'].'</a>';
?>

      <footer class="footer">
        <div class="footer__block block no-margin-bottom">
          <div class="container-fluid text-center">
            <!-- Please do not remove the backlink to us unless you support us at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
            <p class="no-margin-bottom">&copy; <script>document.write(new Date().getFullYear())</script> <?= $url; ?></script> | Font-end & Back-end <a href="https://github.com/x-Scripts">x-Scripts.pl</a></p>
          </div>
        </div>
      </footer>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/jquery/jquery.toast.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/jquery/jquery-ui.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/popper.js/umd/popper.min.js'); ?>"> </script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/jquery.cookie/jquery.cookie.js'); ?>"> </script>
    <script src="<?= base_url('assets/vendor/chart.js/Chart.min.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/jquery-validation/jquery.validate.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/front.js'); ?>"></script>
    <script src="<?= base_url('assets/js/mobile-detect.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/helpers/main_helper.js?'.uniqid()); ?>"></script>
    <script>
      function logged() {
        $.ajax({
          url: "<?= base_url('api/logged'); ?>", type: 'get', success: function(data) {
            if(!data.success) {
              window.location.href=data.message;
            }
          }
        })
      }

      logged();
      setInterval(function() {
        logged();
      },5000);

      <?php if($this->session->userdata('alert') != false) { $success = ($this->session->userdata('alert')['success'] ? 'true' : 'false');?>
      helper.alert(<?= $success ?>,"<?= $this->session->userdata('alert')['message']; ?>");
      <?php $this->session->unset_userdata('alert'); } ?>
      var base_url = "<?= base_url(); ?>";
      <?php if(isset($var)) {
        foreach($var as $item => $index) {
          echo "var {$item} = '{$index}';".PHP_EOL;
        }
      }
      if(isset($const)) {
        foreach($const as $index => $item) {
          echo "const {$index} = {$item};".PHP_EOL;
        }
      }
      ?>
    </script>
    <?php if(isset($loadjs)) {
      if(is_array($loadjs)) {
        foreach($loadjs as $js) {
    ?>
    <script src="<?= base_url('assets/js/pages/'.$js.'.js?'.uniqid()); ?>"></script>
    <?php
        }
      } else {
    ?>
    <script src="<?= base_url('assets/js/pages/'.$loadjs.'.js?'.uniqid()); ?>"></script>
    <?php }
    } ?>
  </body>
</html>
