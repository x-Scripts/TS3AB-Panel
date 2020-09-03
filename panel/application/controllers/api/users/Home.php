<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 22:16
  * @Filename: Home.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 12-05-2020 11:28
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Home extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $users = $this->db->query("SELECT * FROM `xDashAccounts`")->result_array();
      $result = array();
      $permission = permission(['editAccountLogin','editAccountPerms','editAccountTwoAuth','editAccountPassword','editAccountBotRights','editLimitBots','deleteAccount','viewAccountsList']);
      if(!($permission['editAccountLogin'] || $permission['editAccountPerms'] || $permission['editAccountTwoAuth'] || $permission['editAccountPassword'] || $permission['editAccountBotRights'] || $permission['editLimitBots'] || $permission['viewAccountsList'] || $permission['deleteAccount'])) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!',$permission['addAccount']));
      }
      $perms = array(
        'edit' => ($permission['editAccountLogin'] || $permission['editAccountPerms'] || $permission['editAccountTwoAuth'] || $permission['editAccountPassword'] || $permission['editAccountBotRights'] || $permission['editLimitBots']),
        'delete' => $permission['deleteAccount']
      );
      foreach($users as $id => $user) {
        if($this->session->userdata('login') != $user['username']) {
          $permissions = $this->db->query("SELECT * FROM `xDashPermissionsUsers` WHERE `username` = '{$user['username']}'");
          if($permissions->num_rows()) {
            $permissions = $permissions->result_array()[0];
            $limitBots = ($l = $permissions['limitBots']) ? $l : (($l == null) ? 'Brak danych' : 'Bez limitu');
          } else {
            $limitBots = 'Brak danych';
          }
          $result[] = array(
            'username' => $user['username'],
            'limitBots' => $limitBots,
            'twoauth' => $user['tokenAuthentication'] == null ? 'Nie' : 'Tak',
          );
        }
      }
      return $this->output->set_output(printJson(true,null,array('list' => $result,'perms' => $perms)));
    }
  }
 ?>
