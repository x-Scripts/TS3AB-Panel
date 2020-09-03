<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 21:58
  * @Filename: Users.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 12-05-2020 11:26
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Users extends CI_Controller {
    public function index() {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login?redirect=users'));
      }
      $permission = permission(['editAccountLogin','editAccountPerms','editAccountTwoAuth','editAccountPassword','editAccountBotRights','editLimitBots','addAccount','deleteAccount','viewAccountsList']);
      if(!($permission['editAccountLogin'] || $permission['editAccountPerms'] || $permission['editAccountTwoAuth'] || $permission['editAccountPassword'] || $permission['editAccountBotRights'] || $permission['editLimitBots'] || $permission['addAccount'] || $permission['deleteAccount'] || $permission['viewAccountsList'])) {
        $this->session->set_userdata('alert',array('success' => false,'message' => 'Nie posiadasz dostępu!'));
        redirect(base_url('dash'));
        return;
      }
      $this->load->view('header',array('title' => 'Użytkownicy'));
      $this->load->view('users/home',array('permission' => $permission));
      $this->load->view('footer',array('loadjs' => 'users/home'));
    }
    public function edit($user = null) {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login?redirect=users/edit/'.$user));
      }
      $permission = permission(['editAccount','editAccountLogin','editAccountPerms','editLimitBots','editAccountTwoAuth','editAccountPassword','editAccountBotRights']);
      if(!$this->db->query("SELECT * FROM `xDashAccounts` WHERE `username` = '{$user}'")->num_rows()) {
        $this->session->set_userdata('alert',array('success' => false, 'message' => 'Nie znaleziono danego użytkownika!'));
        redirect(base_url('users'));
      }
      $this->load->view('header',array('title' => 'Edytowanie użytkownika: '.$user));
      $this->load->view('users/edit',array('permission' => $permission));
      $this->load->view('footer',array('loadjs' => 'users/edit', 'var' => array('userID' => $user)));
    }
    public function create() {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login?redirect=users/create'));
      }
      $permission = permission(['addAccount']);
      if(!$permission['addAccount']) {
        $this->session->set_userdata('alert',array('success' => false,'message' => 'Nie posiadasz dostępu!'));
        redirect(base_url('users'));
        return;
      }
      $this->load->view('header',array('title' => 'Tworzenie użytkownika'));
      $this->load->view('users/create');
      $this->load->view('footer',array('loadjs' => 'users/create'));
    }
  }
 ?>
