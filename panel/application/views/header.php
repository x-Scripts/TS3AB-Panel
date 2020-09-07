<?php

$page = $this->router->fetch_class();
$permission = permission([
  'createSimple','createAdvanced','createExpert','viewLogs',
  'viewAccountsList','deleteAccount','addAccount','editAccountPassword',
  'editAccountLogin','editAccountBotRights','editLimitBots','editAccountTwoAuth',
  'editAccountPerms','viewUsage','editSettings'
]);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TS3AudioBot - <?= $title; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <script src="https://kit.fontawesome.com/206c66deb9.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css'); ?>">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.2.45/css/materialdesignicons.min.css">
    <!-- Custom Font Icons CSS-->
    <link rel="stylesheet" href="<?= base_url('assets/css/font.css'); ?>">
    <!-- Google fonts - Muli-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.red.css'); ?>" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/jquery.toast.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/jquery-ui.css'); ?>">
    <!-- Favicon-->
    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.ico'); ?>">
  </head>
  <body>
    <header class="header">
      <nav class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex align-items-center justify-content-between">
          <div class="navbar-header">
            <!-- Navbar Header-->
            <a href="<?= base_url(''); ?>" class="navbar-brand">
            <div class="brand-text brand-big visible text-uppercase"><strong class="text-primary">TS3</strong><strong>AudioBot</strong></div>
            <div class="brand-text brand-sm"><strong class="text-primary">T</strong><strong>AB</strong></div></a>
            <!-- Sidebar Toggle Btn-->
            <button class="sidebar-toggle"><i class="fa fa-long-arrow-left"></i></button>
          </div>
          <div class="list-inline-item dropdown">
            <a id="accountSettings" style="cursor: pointer;" rel="nofollow" data-target="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
              Konto
            </a>
            <div aria-labelledby="accountSettings" class="dropdown-menu">
              <a rel="nofollow" href="<?= base_url('account'); ?>" class="dropdown-item">
                <span><i class="icon-settings"></i> Ustawienia konta</span>
              </a>
              <a rel="nofollow" href="<?= base_url('logout'); ?>" class="dropdown-item">
                <span><i class="icon-logout"></i> Wyloguj</span>
              </a>
            </div>
          </div>
        </div>
      </nav>
    </header>
    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      <nav id="sidebar" class="sidenav">
        <!-- Sidebar Header-->
        <div class="sidebar-header d-flex align-items-center">
          <div class="avatar"><img src="<?= $this->session->userdata('avatar') == null ? base_url('assets/img/custom.png') : 'data:image/png;base64,'.$this->session->userdata('avatar'); ?>" alt="..." class="img-fluid rounded-circle"></div>
          <div class="title">
            <h1 class="h5"><?= $this->session->userdata('login'); ?></h1>
          </div>
        </div>
        <!-- Sidebar Navidation Menus-->
        <ul class="list-unstyled">
          <li<?= $page == 'dash' ? ' class="active"' : ''; ?>>
            <a href="<?= base_url('dash'); ?>"> <i class="icon-home"></i>Lista botów</a>
          </li>
          <?php if($permission['createSimple'] || $permission['createAdvanced'] || $permission['createExpert']) { ?>
            <li<?= $page == 'create' ? ' class="active"' : ''; ?>>
              <a href="<?= base_url('create'); ?>"> <i class="fas fa-plus"></i>Stwórz bota</a>
            </li>
          <?php } if($permission['viewLogs']) { ?>
            <li<?= $page == 'logs' ? ' class="active"' : ''; ?>>
              <a href="<?= base_url('logs'); ?>"> <i class="icon-new-file"></i>Logi</a>
            </li>
          <?php } ?>
          <li<?= $page == 'loginHistory' ? ' class="active"' : ''; ?>>
            <a href="<?= base_url('loginHistory'); ?>"> <i class="fa fa-history"></i>Historia logowań</a>
          </li>
          <?php if($permission['addAccount'] || $permission['deleteAccount'] || $permission['viewAccountsList'] || $permission['editAccountPassword'] || $permission['editLimitBots'] || $permission['editAccountPerms'] || $permission['editAccountTwoAuth'] || $permission['editAccountBotRights'] || $permission['editAccountLogin']) { ?>
            <li<?= $page == 'users' ? ' class="active"' : ''; ?>>
              <a href="<?= base_url('users'); ?>"> <i class="icon-user"></i>Użytkownicy</a>
            </li>
          <?php } if($permission['editSettings']) { ?>
            <li<?= $page == 'settings' ? ' class="active"' : ''; ?>>
              <a href="<?= base_url('settings'); ?>"> <i class="icon-settings"></i>Ustawienia</a>
            </li>
          <?php } if($permission['viewUsage']) { ?>
            <li<?= $page == 'usage' ? ' class="active"' : ''; ?>>
              <a href="<?= base_url('usage'); ?>"> <i class="icon-pie-chart-1"></i>Zużycie</a>
            </li>
          <?php } ?>
        </ul>
      </nav>
      <!-- Sidebar Navigation end-->
      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom"><?= $title; ?></h2>
          </div>
        </div>
