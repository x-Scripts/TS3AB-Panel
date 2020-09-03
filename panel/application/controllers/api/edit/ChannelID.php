<?php
 /**
  * Information
  * @Author: xares
  * @Date:   21-05-2020 12:05
  * @Filename: ChannelID.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 21-05-2020 12:45
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class ChannelID extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $permissions = permission(['editExpertBot','editAdvancedBot','editSimpleBot','viewAllBots']);
      if(!($permissions['editExpertBot'] || $permissions['editAdvancedBot'] || $permissions['editSimpleBot'])) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['botID','value'],['Podaj id bota!','Podaj id kanału bota!']);
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

      if(!is_numeric($req['value'])) {
        return $this->output->set_output(printJson(false,'Można tylko podać id kanału!'));
      }

      foreach(json_decode($this->ts3ab->botList(),true) as $index) {
        if($index['Status'] && $index['Name'] == $req['botID']) {
          $this->ts3ab->setBotID($index['Id']);
          $name = json_decode($this->ts3ab->setChannelID($req['value']),true);
          if(isset($name['ErrorMessage'])) {
            return $this->output->set_output(printJson(false,$name['ErrorMessage']));
          }
        }
      }

      $run = json_decode($this->ts3ab->command("settings/bot/set/{$req['botID']}/connect.channel/\"/{$req['value']}\""),true);
      if(isset($run['ErrorMessage'])) {
        return $this->output->set_output(printJson(false,$run['ErrorMessage']));
      }


      return $this->output->set_output(printJson(true,'Zapisano'));
    }
  }
 ?>
