<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 22:50
  * @Filename: Create.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 25-05-2020 01:39
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Create extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permission = permission(['addAccount']);
      if(!$permission['addAccount']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }
      $req = request($this->input->post(),['login','password','limitBots','rightsUserBots','permsDash'],['Podaj login!','Podaj hasło!','Ustaw limit botów!','Wybierz uprawnienia przy tworzeniu bota!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];
      if($this->db->query("SELECT * FROM `xDashAccounts` WHERE `username` = '{$req['login']}'")->num_rows()) {
        return $this->output->set_output(printJson(false,'Takie konto już istnieje!'));
      }

      if(!is_array($req['rightsUserBots'])) {
        if(!isset($req['rightsUserBots']) || $req['rightsUserBots'] != 'all') {
          return $this->output->set_output(false,'Wybierz typ uprawnień przy tworzeniu bota!');
        }
      } else {
        $req['rightsUserBots'] = json_encode($req['rightsUserBots']);
      }

      $perms1 = array('`username`','`limitBots`','`userRights`');
      $perms2 = array("'{$req['login']}'","'{$req['limitBots']}'","'{$req['rightsUserBots']}'");
      foreach($req['permsDash'][0] as $id => $perms) {
        $perms1[] = "`{$req['permsDash'][1][$id]}`";
        $perms2[] = "{$perms}";
      }
      $query = "INSERT INTO `xDashPermissionsUsers` (".implode(',',$perms1).") VALUES (".implode(',',$perms2).")";
      $this->db->query($query);
      $password = password_hash($req['password'],PASSWORD_DEFAULT);
      $this->db->query("INSERT INTO `xDashAccounts`(`username`,`password`) VALUES ('{$req['login']}','{$password}')");
      return $this->output->set_output(printJson(true,'Pomyślnie stworzono użytkownika'));
    }
  }
 ?>
