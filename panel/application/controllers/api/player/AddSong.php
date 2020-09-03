<?php
 /**
  * Information
  * @Author: xares
  * @Date:   24-05-2020 19:38
  * @Filename: AddSong.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 24-05-2020 19:40
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class AddSong extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $permissions = permission(['viewAllBots','playSong']);
      if(!$permissions['playSong']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['botID','value'],['Podaj id bota!','Podaj link do utworu!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if($permissions['viewAllBots']) {
        if(!$this->db->query("SELECT * FROM `xDashBotList` WHERE `id` = '{$req['botID']}'")->num_rows()) {
          return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
        }
      } else {
        if(!$this->db->query("SELECT * FROM `xDashBotsUsers` WHERE `username` = '{$this->session->userdata('login')}' AND `botID` = '{$req['botID']}'")->num_rows()) {
          return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
        }
      }

      $list = json_decode($this->ts3ab->botList(),true);
      if(isset($list['ErrorCode'])) {
        return $this->output->set_output(printJson(false,$list['ErrorMessage']));
      }

      foreach($list as $index) {
        if($index['Name'] == $req['botID']) {
          if($index['Status'] == 2) {
            $this->ts3ab->setBotID($index['Id']);
            $player = json_decode($this->ts3ab->add($req['value']),true);
            if(isset($player['ErrorCode'])) {
              return $this->output->set_output(printJson(false,$player['ErrorMessage']));
            }
            return $this->output->set_output(printJson(true,'Dodano utwór do kolejki!'));
          }
        }
      }

      return $this->output->set_output(printJson(false,'Bot jest wyłączony'));
    }
  }
 ?>
