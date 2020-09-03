<?php
 /**
  * Information
  * @Author: xares
  * @Date:   21-05-2020 21:55
  * @Filename: Player.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 24-05-2020 19:35
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Player extends CI_Controller {
    public function index($id = null) {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login?redirect=player'));
      }
      $permissions = permission(['playSong','manageMusic']);
      if(!($permissions['playSong'] || $permissions['manageMusic'])) {
        $this->session->set_userdata('alert',array('success' => false, 'message' => 'Nie posiadasz dostępu!'));
        redirect(base_url('dash'));
      }
      if($id == null || !$this->db->query("SELECT * FROM `xDashBotList` WHERE `id` = '$id'")->num_rows()) {
        $this->session->set_userdata('alert',array('success' => false, 'message' => 'Nie znaleziono bota!'));
        redirect(base_url('dash'));
      }
      $this->load->view('header',array('title' => 'Odtwarzacz bota'));
      $this->load->view('player',array('permission' => $permissions));
      $this->load->view('footer',array('loadjs' => 'player','var' => array('botID' => $id),'const' => array('permission' => json_encode($permissions))));
    }
  }
 ?>
