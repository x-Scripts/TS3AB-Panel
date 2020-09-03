<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 21:41
  * @Filename: LoginHistory.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 10-05-2020 21:45
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class LoginHistory extends CI_Controller {
    public function index() {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login?redirect=loginHistory'));
      }
      $this->load->view('header',array('title' => 'Historia Logowań'));
      $this->load->view('loginHistory');
      $this->load->view('footer',array('loadjs' => 'loginHistory'));
    }
  }
 ?>
