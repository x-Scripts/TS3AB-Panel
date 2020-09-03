<?php
 /**
  * Information
  * @Author: xares
  * @Date:   24-05-2020 21:51
  * @Filename: Account.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 21:13
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Account extends CI_Controller {
    public function index() {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login?redirect=account'));
      }

      if($this->session->userdata('twoAuth') == null) {
        $data['twoAuth'] = array(
          'status' => 'wyłączony',
          'statusBool' => false
        );
      } else {
        $data['twoAuth'] = array(
          'status' => 'włączony',
          'statusBool' => true
        );
      }
      $this->load->helper('cookie');
      $data['remember'] = $this->db->query("SELECT * FROM `xDashRemembers` WHERE `username` = '{$this->session->userdata('login')}'");
      $data['cookie'] = get_cookie('xDashRemember');

      $this->load->view('header',array('title' => 'Ustawienia konta'));
      $this->load->view('account',$data);
      $this->load->view('footer',array('loadjs' => 'account'));
    }
  }
 ?>
