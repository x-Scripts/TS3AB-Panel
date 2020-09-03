<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 21:25
  * @Filename: Create.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 18-05-2020 22:57
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Create extends CI_Controller {
    public function index() {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login?redirect=create'));
      }
      $permission = permission(['userRights','createSimple','createAdvanced','createExpert']);
      if(!($permission['createSimple'] || $permission['createAdvanced'] || $permission['createExpert'])) {
        $this->session->set_userdata('alert',array('success' => false, 'message' => 'Nie posiadasz dostępu!'));
        redirect(base_url('dash'));
        return;
      }
      if($permission['userRights'] == 'all') {
        $data['rights'] = generateAllRightsHtml($this->config->item('allRights'));
      } elseif($permission['userRights'] != null) {
        $data['rights'] = generateUserRightsHtml(json_decode($permission['userRights'],true));
      } else {
        $data['rights'] = '';
      }
      $data['permission'] = $permission;
      $this->load->view('header',array('title' => 'Tworzenie bota'));
      $this->load->view('create',$data);
      $this->load->view('footer',array('loadjs' => 'create'));
    }
  }
 ?>
