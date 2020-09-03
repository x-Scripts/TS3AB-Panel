<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 21:43
  * @Filename: Logout.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 10-05-2020 21:44
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Logout extends CI_Controller {
    public function index() {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login'));
      }
      $this->session->sess_destroy();
      $this->load->helper('cookie');
      $cookie = get_cookie('xDashRemember');
      if($cookie != null) {
        delete_cookie('xDashRemember');
        $this->db->query("DELETE FROM `xDashRemembers` WHERE `id` = '$cookie'");
      }
      redirect(base_url('login'));
    }
  }
 ?>
