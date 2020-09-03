<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TS3AudioBot - Login</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css'); ?>">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/font-awesome.min.css'); ?>">
    <!-- Custom Font Icons CSS-->
    <link rel="stylesheet" href="<?= base_url('assets/css/font.css'); ?>">
    <!-- Google fonts - Muli-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.red.css'); ?>" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/jquery.toast.css'); ?>">
    <!-- Favicon-->
    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.ico'); ?>">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
    <div class="login-page">
      <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">

            <!-- Logo & Information Panel-->

            <!-- Form Panel    -->
            <div class="login-bar col-sm-12">
              <div class="form align-items-center animated fadeInDownBig" style="display: table-cell;">
                <div class="block">
                    <div class="title">
                      <strong class="d-block text-center">TS3AudioBot - Panel zarządzania</strong>
                    </div>
                    <div class="content text-center">
                      <div class="form-group login-inputs">
                        <input type="text" name="Nazwa użykownia" required data-msg="Proszę podać nazwę użytkownika" class="input-material username">
                        <label for="login-username" class="label-material user">Nazwa użytkownika</label>
                      </div>
                      <div class="form-group token-input" style="display: none;">
                        <input type="text" name="Token weryfikacyjny" required data-msg="Proszę podać token weryfikacyjny" class="input-material token-auth">
                        <label for="login-token" class="label-material token">Token z aplikacji</label>
                      </div>
                      <div class="form-group login-inputs">
                        <input type="password" name="Hasło" required data-msg="Proszę podać hasło" class="input-material password">
                        <label for="login-password" class="label-material pass">Hasło</label>
                        <span class="show-password"><i class="fa fa-eye show-pass" data-toggle="tooltip" data-placement="top" title="Pokaż/ukryj hasło"></i></span>
                      </div>
                      <div class="i-checks" style="text-align: left;">
                        <input type="checkbox" class="checkbox-template remember">
                        <label for="checkboxCustom2">Zapamiętaj</label>
                      </div>
                      <button class="btn btn-primary auth">Zaloguj</button>
                    </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
      <div class="copyrights text-center">
         <p>&copy; <script>document.write(new Date().getFullYear())</script> <a href="<?= base_url(); ?>"><?= parse_url(base_url())['host']; ?></a></script> | Font-end & Back-end <a href="https://github.com/x-Scripts">x-Scripts.pl</a></p>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/jquery/jquery.toast.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/popper.js/umd/popper.min.js'); ?>"> </script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/jquery.cookie/jquery.cookie.js'); ?>"> </script>
    <script src="<?= base_url('assets/vendor/jquery-validation/jquery.validate.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/front.js'); ?>"></script>
    <script src="<?= base_url('assets/js/mobile-detect.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/helpers/main_helper.js?'.uniqid()); ?>"></script>
    <script>
    var md = new MobileDetect(window.navigator.userAgent);
    if(md.mobile()) {
      $('.login-bar').attr('style','width: 100%;');
    }
      $(document).on('click','.auth', function() {
        auth();
      });
      function auth() {
        $.ajax({
          url: "<?= base_url('api/auth'); ?>", type: "POST", data: {
            login: $('.username').val(),
            password: $('.password').val(),
            remember: $('.remember').is(':checked'),
            redirect: "<?= $redirect; ?>"
          }, success: function(data) {
            if(data.value == 'all') {
              $('.username, .password').addClass('is-invalid');
            } else if(data.value == 'login') {
              $('.username').addClass('is-invalid');
              $('.password').removeClass('is-invalid');
            } else if(data.value == 'password') {
              $('.password').addClass('is-invalid');
              $('.username').removeClass('is-invalid');
            } else {
              $('.username, .password').removeClass('is-invalid');
            }
            if(data.value == true) {
              $('.login-inputs').slideUp('normal');
              $('.token-input').slideDown('normal');
              $('.auth').toggleClass('auth verify-token').html('Zweryfikuj');
            } else if(data.success) {
              setTimeout('window.location.href="' + data.value + '"',2000);
            }

            helper.alert(data.success,data.message);
          }
        })
      }
      $(document).on('click','.verify-token',function() {
        $.ajax({
          url: "<?= base_url('api/auth/verifyCode'); ?>", type: 'post', data: {
            token: $('.token-auth').val(),
            remember: $('.remember').is(':checked'),
            redirect: "<?= $redirect; ?>"
          }, success: function(data) {
            if(data.success) {
              setTimeout('window.location.href="' + data.value + '"',2000);
            }
            helper.alert(data.success,data.message);
          }
        })
      })
      $(document).on('keypress',function(key) {
        if(key.keyCode == 13) {
          auth();
        }
      })
      $('.show-pass').click(function() {
        pass = $('.password');
        $(this).toggleClass("fa-eye fa-eye-slash");
        if(pass.attr('type') == 'password') {
          pass.attr('type','text');
        } else {
          pass.attr('type','password');
        }
      });
      $('.user').click(function() {
        $(this).addClass('active');
        $('.username').focus();
      });
      $('.pass').click(function() {
        $(this).addClass();
        $('.password').focus();
      })
    </script>
  </body>
</html>
