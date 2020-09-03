<?php
 /**
  * Information
  * @Author: xares
  * @Date:   21-05-2020 12:28
  * @Filename: ClientVersion.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 21-05-2020 12:46
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class ClientVersion extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $permissions = permission(['editExpertBot','editAdvancedBot','viewAllBots']);
      if(!($permissions['editExpertBot'] || $permissions['editAdvancedBot'])) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['botID','value'],['Podaj id bota!','Podaj odznaki!']);
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

      $json = array('json/merge');

      $json[] = "(/settings/bot/set/{$req['botID']}/connect.client_version.build/\"".rawurlencode($req['value']['build'])."\")";
      $json[] = "(/settings/bot/set/{$req['botID']}/connect.client_version.sign/\"".rawurlencode($req['value']['sign'])."\")";
      $json[] = "(/settings/bot/set/{$req['botID']}/connect.client_version.platform/\"".rawurlencode($req['value']['platform'])."\")";

      $run = json_decode($this->ts3ab->command(implode('/',$json)),true);

      if(isset($run[0]['ErrorMessage'])) {
        return $this->output->set_output(printJson(false,$run[0]['ErrorMessage']));
      }


      return $this->output->set_output(printJson(true,'Zapisano'));
    }
  }
 ?>
