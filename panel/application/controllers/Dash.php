<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 21:23
  * @Filename: Dash.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 18:53
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Dash extends CI_Controller {
    public function index() {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login?redirect=dash'));
      }
      $this->load->view('header',array('title' => 'Lista botów'));
      $this->load->view('dash',array('permission' => permission(['startStopApp','deleteBots','addBotUsers'])));
      $this->load->view('footer',array('loadjs' => 'dash'));
    }
  }
 ?>
