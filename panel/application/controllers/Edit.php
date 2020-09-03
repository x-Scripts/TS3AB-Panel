<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 21:56
  * @Filename: Edit.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 01-06-2020 13:39
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Edit extends CI_Controller {
    public function index($id = null) {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login?redirect=edit'));
      }
      if($id != null) {
        $db = $this->db->query("SELECT * FROM `xDashBotList` WHERE `id` = '{$id}'");
        if($db->num_rows()) {
          $permission = permission(['editSimpleBot','editAdvancedBot','editExpertBot','userRights','editRightsBot','addUsersBot']);
          if(!($permission['editSimpleBot'] || $permission['editAdvancedBot'] || $permission['editExpertBot'] || $permission['addUsersBot'] || $permission['editRightsBot'])) {
            $this->session->set_userdata('alert',array('success' => false, 'message' => 'Nie posiadasz dostępu!'));
            redirect(base_url('dash'));
          }
          if($permission['userRights'] == 'all') {
            $data['rights'] = generateAllRightsHtml($this->config->item('allRights'),'','','',false);
          } elseif($permission['userRights'] != null) {
            $data['rights'] = generateUserRightsHtml(json_decode($permission['userRights'],true),false);
          } else {
            $data['rights'] = '';
          }
          $data['permission'] = $permission;
          $this->load->view('header',array('title' => 'Edytowanie bota'));
          $this->load->view('edit',$data);
          $this->load->view('footer',array('loadjs' => 'edit','var' => array('botID' => $id)));
          return;
        }
      }
      $this->session->set_userdata('alert',array('success' => false,'message' => 'Nie odnaleziono danego bota!'));
      redirect(base_url('dash'));
    }
  }
 ?>
