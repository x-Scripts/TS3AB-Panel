<?php
 /**
  * Information
  * @Author: xares
  * @Date:   13-05-2020 00:13
  * @Filename: Bot.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 20:58
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Bot extends CI_Controller {

    public function start() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $req = request($this->input->post(),['botID'],['Podaj id bota!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];
      $ids = array();
      $permissions = permission(['viewAllBots']);
      if($permissions['viewAllBots']) {
        foreach($this->db->query("SELECT * FROM `xDashBotList`")->result_array() as $bot) {
          $ids[] = $bot['id'];
        }
      } else {
        foreach($this->db->query("SELECT * FROM `xDashBotsUsers` WHERE `username` = '".$this->session->userdata('login')."'")->result_array() as $bot) {
          $ids[] = $bot['botID'];
        }
      }
      if(!in_array($req['botID'],$ids)) {
        return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
      }
      $list = json_decode($this->ts3ab->botList(),true);
      if(isset($command['ErrorCode'])) {
        return $this->output->set_output(printJson(false,'Błąd autoryzacji'));
      }

      foreach($list as $bot) {
        if($bot['Name'] == $req['botID']) {
          if($bot['Status'] <= 1) {
            $this->ts3ab->botConnect($bot['Name']);
            return $this->output->set_output(printJson(true,'Bot został włączony',true));
          } else {
            return $this->output->set_output(printJson(false,'Bot jest już włączony',true));
          }
        }
      }

      return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
    }

    public function stop() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $req = request($this->input->post(),['botID'],['Podaj id bota!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];
      $ids = array();
      $permissions = permission(['viewAllBots']);
      if($permissions['viewAllBots']) {
        foreach($this->db->query("SELECT * FROM `xDashBotList`")->result_array() as $bot) {
          $ids[] = $bot['id'];
        }
      } else {
        foreach($this->db->query("SELECT * FROM `xDashBotsUsers` WHERE `username` = '".$this->session->userdata('login')."'")->result_array() as $bot) {
          $ids[] = $bot['botID'];
        }
      }
      if(!in_array($req['botID'],$ids)) {
        return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
      }
      $list = json_decode($this->ts3ab->botList(),true);
      if(isset($command['ErrorCode'])) {
        return $this->output->set_output(printJson(false,'Błąd autoryzacji'));
      }

      foreach($list as $bot) {
        if($bot['Name'] == $req['botID']) {
          if($bot['Status'] >= 1) {
            $this->ts3ab->setBotID($bot['Id']);
            $this->ts3ab->botDisconnect();
            return $this->output->set_output(printJson(true,'Bot został wyłączony',true));
          } else {
            return $this->output->set_output(printJson(false,'Bot jest już wyłączony',true));
          }
        }
      }

      return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
    }

    public function delete() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permission = permission(['deleteBots','viewAllBots']);
      if(!$permission['deleteBots']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['botID'],['Podaj id bota!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if(!$permission['viewAllBots']) {
        if(!$this->db->query("SELECT * FROM `xDashBotsUsers` WHERE `username` = '{$this->session->userdata('login')}' AND `botID` = '{$req['botID']}'")->num_rows()) {
          return $this->output->set_output(printJson(false,'Nie znaleziono danego bota!'));
        }
      } else {
        if(!$this->db->query("SELECT * FROM `xDashBotList` WHERE `id` = '{$req['botID']}'")->num_rows()) {
          return $this->output->set_output(printJson(false,'Nie znaleziono danego bota!'));
        }
      }


      foreach(json_decode($this->ts3ab->botList(),true) as $list) {
        if($list['Name'] == $req['botID']) {
          if($list['Status'] != 0) {
            $this->ts3ab->setBotID($list['Id']);
            $this->ts3ab->commandBot("json/merge/(/bot/disconnect)/(/settings/delete/{$list['Name']})");
          } else {
            $this->ts3ab->command('settings/delete/'.$list['Name']);
          }
          return $this->output->set_output(printJson(true,'Pomyślnie usunięto bota!'));
        }
      }
      return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
    }

    public function editUsers() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permission = permission(['deleteBots','viewAllBots']);
      if(!$permission['deleteBots']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['botID'],['Podaj id bota']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if(!$permission['viewAllBots']) {
        if(!$this->db->query("SELECT * FROM `xDashBotsUsers` WHERE `username` = '{$this->session->userdata('login')}' AND `botID` = '{$req['botID']}'")->num_rows()) {
          return $this->output->set_output(printJson(false,'Nie znaleziono danego bota!'));
        }
      } else {
        if(!$this->db->query("SELECT * FROM `xDashBotList` WHERE `id` = '{$req['botID']}'")->num_rows()) {
          return $this->output->set_output(printJson(false,'Nie znaleziono danego bota!'));
        }
      }

      if(empty($req['users'])) {
        $req['users'] = array();
      }

      $botUsers = $this->db->query("SELECT * FROM `xDashBotsUsers`")->result_array();
      $users = array();
      $bots = array();
      foreach($botUsers as $item) {
        if(isset($users[$item['username']])) {
          $users[$item['username']][] = $item['botID'];
        } else {
          $users[$item['username']] = [$item['botID']];
        }
        if(isset($bots[$item['botID']])) {
          $bots[$item['botID']][] = $item['username'];
        } else {
          $bots[$item['botID']] = [$item['username']];
        }
      }

      foreach($req['users'] as $index) {
        if(!isset($users[$index]) || !in_array($req['botID'],$users[$index])) {
          $this->db->query("INSERT INTO `xDashBotsUsers`(`username`,`botID`,`timeAdd`) VALUES ('{$index}','{$req['botID']}','".time()."')");
        }
      }
      if(isset($bots[$req['botID']])) {
        foreach($bots[$req['botID']] as $index) {
          if(!in_array($index,$req['users'])) {
            $this->db->query("DELETE FROM `xDashBotsUsers` WHERE `username` = '{$index}' AND `botID` = '{$req['botID']}'");
          }
        }
      }
      return $this->output->set_output(printJson(true,'Zapisano'));
    }

  }
 ?>
