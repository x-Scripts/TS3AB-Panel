<?php
 /**
  * Information
  * @Author: xares
  * @Date:   26-05-2020 11:52
  * @Filename: AddUser.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 01:02
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class AddUser extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permissions = permission(['addUsersBot','viewAllBots']);
      if(!$permissions['addUsersBot']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['botID','user','uid'],['Podaj id bota!','Podaj nazwę użytkownika!','Podaj unikalne id użytkownika!']);
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

      $this->load->library('TomlEditor');

      $users = $this->db->query("SELECT * FROM `xDashBotRights` WHERE `id` = '{$req['botID']}'");

      if($users->num_rows()) {
        $users = $users->result_array()[0];
        $clientsPanel = json_decode($users['clientPanel'],true);
        $clientsFile = json_decode($users['clientFiles'],true);

        if(isset($clientsPanel[$req['user']]) || in_array($req['uid'],$clientsFile)) {
          return $this->output->set_output(printJson(false,'Taki użytkownik już istnieje!'));
        }

        $clientsFile[] = $req['uid'];
        $clientsPanel[$req['user']] = $req['uid'];
        $this->tomleditor->editUsersBot($req['botID'],$clientsFile);
        $save = $this->tomleditor->saveFile();
        if($save['success']) {
          $this->ts3ab->rightsReload();
          $this->db->query("UPDATE `xDashBotRights` SET `clientPanel` = '".json_encode($clientsPanel,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."', `clientFiles` = '".json_encode($clientsFile,JSON_PRETTY_PRINT)."' WHERE `id` = '{$req['botID']}'");
          return $this->output->set_output(printJson(true,'Zapisano',$clientsPanel));
        }
      } else {
        $clientsPanel = array();
        $clientsFile = array();
        $clientsPanel[$req['user']] = $req['uid'];
        $clientsFile[] = $req['uid'];
        $this->tomleditor->editUsersBot($req['botID'],$clientsFile);
        $save = $this->tomleditor->saveFile();
        if($save['success']) {
          $this->ts3ab->rightsReload();
          $this->db->query("INSERT INTO `xDashBotRights` (`id`,`clientFiles`,`clientPanel`,`groups`,`rightsCmd`) VALUES ('{$req['botID']}','".json_encode($clientsFile,JSON_PRETTY_PRINT)."','".json_encode($clientsPanel,JSON_PRETTY_PRINT)."','','[]')");
          return $this->output->set_output(printJson(true,'Zapisano',$clientsPanel));
        }
      }
      return $this->output->set_output(printJson(false,$save['response']));
    }
  }
 ?>
