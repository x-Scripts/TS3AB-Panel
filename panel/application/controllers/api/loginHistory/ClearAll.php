<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 21:10
  * @Filename: ClearAll.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 10-05-2020 21:11
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class ClearAll extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $permissions = permission(['viewAllHistory','clearHistory']);
      
      if(!$permissions['viewAllHistory'] || !$permissions['clearHistory']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu'));
      }
      $this->db->query("DELETE FROM `xDashLoginHistory`");
      return $this->output->set_output(printJson(true,'Pomyślnie wyczyszczono całą historię'));
    }
  }
 ?>
