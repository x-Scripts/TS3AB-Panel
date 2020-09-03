<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 21:57
  * @Filename: Usage.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 27-05-2020 23:21
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Usage extends CI_Controller {
    public function index() {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login?redirect=usage'));
      }
      if(!permission('viewUsage')) {
        $this->session->set_userdata('alert',array('success' => false,'message' => 'Nie posiadasz dostępu!'));
        redirect(base_url('dash'));
        return;
      }
      $this->load->view('header',array('title' => 'Zużycie'));
      $this->load->view('usage');
      $this->load->view('footer',array('loadjs' => 'usage'));
    }
  }
 ?>
