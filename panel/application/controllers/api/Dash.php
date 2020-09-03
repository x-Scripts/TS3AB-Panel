<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 19:44
  * @Filename: Dash.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 29-05-2020 11:20
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Dash extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $merge = array('json/merge');
      $ids = array();
      $permissions = permission(['viewAllBots','editSimpleBot','editAdvancedBot','editExpertBot','editRightsBot','addUsersBot','deleteBots','playSong','manageMusic','addBotUsers']);
      $botUsers = $this->db->query("SELECT * FROM `xDashBotsUsers` ORDER BY `timeAdd` ASC")->result_array();
      if($permissions['viewAllBots']) {
        foreach($this->db->query("SELECT * FROM `xDashBotList` ORDER BY `timeCreate` ASC")->result_array() as $bot) {
          $merge[] = '(/settings/bot/get/'.$bot['id'].'/connect.name)';
          $ids[] = $bot['id'];
        }
      } else {
        foreach($botUsers as $bot) {
          if($this->session->userdata('login') == $bot['username']) {
            $merge[] = '(/settings/bot/get/'.$bot['botID'].'/connect.name)';
            $ids[] = $bot['botID'];
          }
        }
      }
      if(!isset($merge[1])) {
        return $this->output->set_output(printJson(true,'Brak botów','none'));
      }

      $users = array();
      if($permissions['addBotUsers']) {
        foreach($botUsers as $id => $index) {
          if(isset($users[$index['botID']])) {
            $users[$index['botID']][] = $index['username'];
          } else {
            $users[$index['botID']] = [$index['username']];
          }
        }
      }
      $accounts = array();
      foreach($this->db->query("SELECT * FROM `xDashAccounts`")->result_array() as $index) {
        if($this->session->userdata('login') != $index['username']) {
          $accounts[] = $index['username'];
        }
      }


      $command = json_decode($this->ts3ab->command(implode('/',$merge).'/(/bot/list)'),true);
      if(isset($command['ErrorCode'])) {
        return $this->output->set_output(printJson(false,$command['ErrorMessage']));
      }

      $column = array_column(end($command),'Status','Name');
      $server = array_column(end($command),'Server','Name');
      $return = array();
      foreach($ids as $id => $list) {
        if(array_key_exists($list,$column)) {
          $return[] = array(
            'name' => $command[$id],
            'id' => $list,
            'status' => $column[$list],
            'server' => $server[$list],
            'users' => isset($users[$list]) ? $users[$list] : []
          );
        }
      }
      $perms = array(
        'editBots' => ($permissions['editSimpleBot'] || $permissions['editAdvancedBot'] || $permissions['editExpertBot'] || $permissions['editRightsBot'] || $permissions['addUsersBot']),
        'deleteBots' => $permissions['deleteBots'],
        'player' => ($permissions['playSong'] || $permissions['manageMusic']),
        'addBotUsers' => $permissions['addBotUsers']
      );
      return $this->output->set_output(printJson(true,array('list' => $return, 'perms' => $perms, 'users' => $accounts),'list'));
    }
  }
 ?>
