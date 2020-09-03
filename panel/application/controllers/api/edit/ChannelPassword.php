<?php
 /**
  * Information
  * @Author: xares
  * @Date:   21-05-2020 12:53
  * @Filename: ChannelPassword.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 21-05-2020 12:53
  *
  * @Copyright(C) 2020 x-Scripts
  */


  class ChannelPassword extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $permissions = permission(['editExpertBot','editAdvancedBot','viewAllBots']);
      if(!($permissions['editExpertBot'] || $permissions['editAdvancedBot'])) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['botID'],['Podaj id bota!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if(!array_key_exists('value', $req)) {
        $req['value'] = '';
      }

      if(!$permissions['viewAllBots']) {
        if(!$this->db->query("SELECT * FROM `xDashBotsUsers` WHERE `username` = '{$this->session->userdata('login')}' AND `botID` = '{$req['botID']}'")->num_rows()) {
          return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
        }
      }

      if(!$this->db->query("SELECT * FROM `xDashBotList` WHERE `id` = '{$req['botID']}'")->num_rows()) {
        return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
      }



      $run = json_decode($this->ts3ab->command("settings/bot/set/{$req['botID']}/connect.channel_password.pw/\"{$req['value']}\""),true);
      if(isset($run['ErrorMessage'])) {
        return $this->output->set_output(printJson(false,$run['ErrorMessage']));
      }


      return $this->output->set_output(printJson(true,'Zapisano'));
    }
  }
 ?>
