<?php
 /**
  * Information
  * @Author: xares
  * @Date:   26-05-2020 10:45
  * @Filename: Settings.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 31-05-2020 23:27
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Settings extends CI_Controller {
    public function apiFile() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      if(!permission('editSettings')) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['type','host','key'],['Podaj typ hostowania plików!','Podaj host plików!','Podaj klucz api!']);

      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      $this->db->query("UPDATE `xDashSettings` SET `value` = '{$req['type']}' WHERE `id` = 'apiType'");
      if($req['type'] == 'externalhost') {
        $this->db->query("UPDATE `xDashSettings` SET `value` = '{$req['host']}' WHERE `id` = 'apiLocal'");
        $this->db->query("UPDATE `xDashSettings` SET `value` = '{$req['key']}' WHERE `id` = 'apiKey'");
      }

      return $this->output->set_output(printJson(true,'Zapisano'));
    }

    public function apiApp() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      if(!permission('editSettings')) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['ip','port','token','timeout'],['Podaj ip serwera!','Podaj port aplikacji!','Podaj token dostępu!','Podaj czas zapytania!']);

      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if(empty($req['apiFileUsage'])) {
        $req['apiFileUsage'] = 0;
      }

      $this->db->query("UPDATE `xDashSettings` SET `value` = '{$req['ip']}' WHERE `id` = 'host'");
      $this->db->query("UPDATE `xDashSettings` SET `value` = '{$req['port']}' WHERE `id` = 'port'");
      $this->db->query("UPDATE `xDashSettings` SET `value` = '{$req['token']}' WHERE `id` = 'apiToken'");
      $this->db->query("UPDATE `xDashSettings` SET `value` = '{$req['timeout']}' WHERE `id` = 'timeout'");
      $this->db->query("UPDATE `xDashSettings` SET `value` = '{$req['apiFileUsage']}' WHERE `id` = 'appApi'");

      return $this->output->set_output(printJson(true,'Zapisano'));
    }
  }
 ?>
