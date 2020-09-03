<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 23:12
  * @Filename: Edit.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 12-05-2020 01:00
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Edit extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $permission = permission(['editAccountLogin','editAccountPerms','editLimitBots','editAccountTwoAuth','editAccountBotRights']);

      if($permission['editAccountTwoAuth'] || $permission['editLimitBots'] || $permission['editAccountLogin'] || $permission['editAccountBotRights'] || $permission['editAccountPerms']) {

        $req = request($this->input->post(),['userID'],'Podaj id użytkownika!');
        if(!$req['success']) {
          return $this->output->set_output(printJson(false,$req['response']));
        }
        $req = $req['response'];

        $db = $this->db->query("SELECT * FROM `xDashAccounts` WHERE `username` = '{$req['userID']}'");

        if($db->num_rows()) {
          $result = array();
          $db = $db->result_array()[0];
          $userPermissions = $this->db->query("SELECT * FROM `xDashPermissionsUsers` WHERE `username` = '{$req['userID']}'");
          if($userPermissions->num_rows()) {
            $userPermissions = $userPermissions->result_array()[0];
            $limitBots = $userPermissions['limitBots'];
            $rights = $userPermissions['userRights'] == 'all' ? 'all' : json_decode($userPermissions['userRights'],true);
            unset($userPermissions['userRights'],$userPermissions['limitBots'],$userPermissions['username']);
          } else {
            $userPermissions = null;
            $limitBots = null;
            $rights = null;
          }
          if($db['tokenAuthentication'] != null) {
            $secret = $this->coder->decode($db['tokenAuthentication']);
            $db['tokenAuthentication'] = array(
              'img' => $this->googleauth->getQRCodeGoogleUrl($req['userID'],$secret,'xDashTS3AB - '.parse_url(base_url())['host']),
              'key' => $secret
            );
          }
          unset($db['password']);
          $result = array();
          if($permission['editAccountPerms']) {
            $result['permsDash'] = $userPermissions;
          }
          if($permission['editLimitBots']) {
            $result['limitBots'] = $limitBots;
          }
          if($permission['editAccountBotRights']) {
            $result['rights'] = $rights;
          }
          if($permission['editAccountLogin']) {
            $result['username'] = $db['username'];
          }
          if($permission['editAccountTwoAuth']) {
            $result['twoauth'] = $db['tokenAuthentication'];
          }

          return $this->output->set_output(printJson(true,null,$result));
        }
        return $this->output->set_output(printJson(false,'Nie znaleziono danego użytkownika',base_url('users')));
      }
      return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));


    }

    public function generateTwoAuth() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permissions = permission(['editAccount','editAccountTwoAuth']);

      if($permissions['editAccountTwoAuth']) {
        $req = request($this->input->post(),['userID'],'Podaj id użytkownika!');
        if(!$req['success']) {
          return $this->output->set_output(printJson(false,$req['response']));
        }
        $key = $this->googleauth->createSecret(20);
        $result = array(
          'img' => $this->googleauth->getQRCodeGoogleUrl($req['response']['userID'],$key,'xDashTS3AB - '.parse_url(base_url())['host']),
          'key' => $key
        );

        return $this->output->set_output(printJson(true,null,$result));
      }

      return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
    }

    public function verifyTwoAuth() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permissions = permission(['editAccountTwoAuth']);

      if(!$permissions['editAccountTwoAuth']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['userID','secretKey','tokenAuth'],['Podaj id użytkownika!','Podaj sekretny klucz!','Podaj token weryfikacyjny!']);

      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if(!$this->db->query("SELECT * FROM `xDashAccounts` WHERE `username` = '{$req['userID']}'")->num_rows()) {
        return $this->output->set_output(printJson(false,'Nie odnaleziono użytkownika!'));
      }

      if($this->googleauth->verifyCode($req['secretKey'],$req['tokenAuth'])) {
        $secret = $this->coder->encode($req['secretKey']);
        $this->db->query("UPDATE `xDashAccounts` SET `tokenAuthentication` = '$secret' WHERE `username` = '{$req['userID']}'");
        return $this->output->set_output(printJson(true,'Pomyślnie zweryfikowano autoryzację!'));
      }

      return $this->output->set_output(printJson(false,'Nie udało się zweryfikować autoryzacji<br>Sprawdź czy poprawnie dodałeś autoryzację do urządzenia!'));
    }

    public function deleteTwoAuth() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permissions = permission(['editAccountTwoAuth']);

      if(!$permissions['editAccountTwoAuth']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['userID'],'Podaj id użytkownika!');

      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      $this->db->query("UPDATE `xDashAccounts` SET `tokenAuthentication` = NULL WHERE `username` = '{$req['userID']}'");
      return $this->output->set_output(printJson(true,'Pomyślnie usunięto weryfikację!'));
    }

    public function login() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permissions = permission(['editAccountLogin']);

      if(!$permissions['editAccountLogin']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['userID','login'],'Podaj id użytkownika!','Podaj nowy login!');

      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if($req['userID'] == $req['login']) {
        return $this->output->set_output(printJson(false,'Nowy login jest taki sam jak poprzedni!'));
      }

      if(!$this->db->query("SELECT * FROM `xDashAccounts` WHERE `username` = '{$req['userID']}'")->num_rows()) {
        return $this->output->set_output(printJson(false,'Nie odnaleziono użytkownika!'));
      }

      foreach(['xDashAccounts','xDashPermissionsUsers','xDashBotsUsers','xDashLoginHistory'] as $table) {
        $this->db->query("UPDATE `$table` SET `username` = '{$req['login']}' WHERE `username` = '{$req['userID']}'");
      }

      foreach($this->db->query("SELECT * FROM `xDashLoginHistory` WHERE `username` = '{$req['login']}'")->result_array() as $ips) {
        $this->db->query("DELETE FROM `xDashSessions` WHERE `ip_address` = '{$ips['ip']}'");
      }

      return $this->output->set_output(printJson(true,'Pomyślnie zmieniono login!',base_url('users/edit/'.$req['login'])));
    }

    public function password() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permissions = permission(['editAccountPassword']);

      if(!$permissions['editAccountPassword']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['userID','password'],'Podaj id użytkownika!','Podaj nowe hasło!');

      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];
      $user = $this->db->query("SELECT * FROM `xDashAccounts` WHERE `username` = '{$req['userID']}'");
      if(!$user->num_rows()) {
        return $this->output->set_output(printJson(false,'Nie odnaleziono użytkownika!'));
      }

      if(password_verify($req['password'],$user->result_array()[0]['password'])) {
        return $this->output->set_output(printJson(false,'Nowe hasło jest takie same jak poprzednie!'));
      }

      $newPassword = password_hash($req['password'],PASSWORD_DEFAULT);
      $this->db->query("UPDATE `xDashAccounts` SET `password` = '$newPassword' WHERE `username` = '{$req['userID']}'");
      return $this->output->set_output(printJson(true,'Pomyślnie zmieniono hasło!'));
    }

    public function limitBots() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permissions = permission(['editLimitBots']);

      if(!$permissions['editLimitBots']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      if($this->input->post('limitBots') == 0) {
        $req = request($this->input->post(),['userID'],'Podaj id użytkownika!');
      } else {
        $req = request($this->input->post(),['userID','limitBots'],['Podaj id użytkownika!','Podaj limit botów!']);
      }

      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];
      $user = $this->db->query("SELECT * FROM `xDashAccounts` WHERE `username` = '{$req['userID']}'");
      if(!$user->num_rows()) {
        return $this->output->set_output(printJson(false,'Nie odnaleziono użytkownika!'));
      }

      $this->db->query("UPDATE `xDashPermissionsUsers` SET `limitBots` = '{$req['limitBots']}' WHERE `username` = '{$req['userID']}'");
      return $this->output->set_output(printJson(true,'Pomyślnie zmeniono limit!'));
    }

    public function rights() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permissions = permission(['editAccountBotRights']);

      if(!$permissions['editAccountBotRights']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['userID','rights'],['Podaj id użytkownika!','Wybierz uprawnienia!']);

      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];
      $user = $this->db->query("SELECT * FROM `xDashPermissionsUsers` WHERE `username` = '{$req['userID']}'");
      if(!$user->num_rows()) {
        return $this->output->set_output(printJson(false,'Nie odnaleziono użytkownika!'));
      }
      $this->db->query("UPDATE `xDashPermissionsUsers` SET `userRights` = '{$req['rights']}' WHERE `username` = '{$req['userID']}'");
      $this->output->set_output(printJson(true,'Pomyślnie zmieniono uprawnienia dla botów!'));
    }

    public function permsDash() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permissions = permission(['editAccountPerms']);

      if(!$permissions['editAccountPerms']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }
      //return $this->output->set_output(printJson(false,$this->input->post()));
      $req = request($this->input->post(),['userID','permsDash'],['Podaj id użytkownika!','Wybierz uprawnienia!']);

      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$this->input->post()));
      }
      $req = $req['response'];
      $user = $this->db->query("SELECT * FROM `xDashPermissionsUsers` WHERE `username` = '{$req['userID']}'");
      if(!$user->num_rows()) {
        return $this->output->set_output(printJson(false,'Nie odnaleziono użytkownika!'));
      }

      $result = array();
      foreach($req['permsDash'] as $id => $index) {
        $result[] = "`{$id}` = '{$index}'";
      }
      $query = "UPDATE `xDashPermissionsUsers` SET ".implode(',',$result)." WHERE `username` = '{$req['userID']}'";
      $this->db->query($query);
      return $this->output->set_output(printJson(true,'Pomyślnie zmieniono uprawnienia!'));
    }
  }
 ?>
