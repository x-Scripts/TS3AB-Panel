<?php
 /**
  * Information
  * @Author: xares
  * @Date:   26-05-2020 11:51
  * @Filename: Delete.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 01:49
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Delete extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permissions = permission(['deleteBots','viewAllBots']);
      if(!$permissions['deleteBots']) {
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

      $this->load->library('TomlEditor');

      $this->tomleditor->deleteTable($req['botID']);
      $save = $this->tomleditor->saveFile();

      if($save['success']) {
        $this->db->query("DELETE FROM `xDashBotRights` WHERE `id` = '{$req['botID']}'");
        $this->db->query("DELETE FROM `xDashBotList` WHERE `id` = '{$req['botID']}'");
        $this->db->query("DELETE FROM `xDashBotsUsers` WHERE `botID` = '{$req['botID']}'");
        return $this->output->set_output(printJson(true,'Pomyślnie usunięto uprawnienia bota!'));
      }

      $this->ts3ab->rightsReload();
      return $this->output->set_output(printJson(false,$save['response']));
    }
  }
 ?>
