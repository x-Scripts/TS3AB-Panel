<?php
 /**
  * Information
  * @Author: xares
  * @Date:   26-05-2020 11:53
  * @Filename: EditCmd.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 27-05-2020 23:32
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class EditCmd extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permissions = permission(['editRightsBot','viewAllBots']);
      if(!$permissions['editRightsBot']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['botID'],['Podaj id bota!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if(!$permissions['viewAllBots']) {
        if(!$this->db->query("SELECT * FROM `xDashBotsUsers` WHERE `username` = '{$this->session->userdata('login')}' AND `botID` = '{$req['botID']}'")->num_rows()) {
          return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
        }
      }

      if(!$this->db->query("SELECT * FROM `xDashBotList` WHERE `id` = '{$req['botID']}'")->num_rows()) {
        return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
      }

      if(empty($req['value'])) {
        $req['value'] = array();
      }

      $this->load->library('TomlEditor');

      $this->tomleditor->editRightsBot($req['botID'],$req['value']);
      $save = $this->tomleditor->saveFile();
      if(!$save['success']) {
        return $this->output->set_output(printJson(false,$save['response']));
      }

      $users = $this->db->query("SELECT * FROM `xDashBotRights` WHERE `id` = '{$req['botID']}'");

      if($users->num_rows()) {
        $this->db->query("UPDATE `xDashBotRights` SET `rightsCmd` = '".json_encode($req['value'],JSON_PRETTY_PRINT)."' WHERE `id` = '{$req['botID']}'");
      } else {
        $this->db->query("INSERT INTO `xDashBotRights` (`id`,`clientFiles`,`clientPanel`,`groups`,`rightsCmd`) VALUES ('{$req['botID']}','[]','[]','','".json_encode($req['value'],JSON_PRETTY_PRINT)."')");
      }
      $this->ts3ab->rightsReload();
      return $this->output->set_output(printJson(true,'Zapisano'));
    }
  }
 ?>
