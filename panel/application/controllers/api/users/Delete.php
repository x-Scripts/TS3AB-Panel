<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 23:02
  * @Filename: Delete.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 01:08
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Delete extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permission = permission(['deleteAccount']);
      if(!$permission['deleteAccount']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }
      $req = request($this->input->post(),['login'],'Podaj login!');
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];
      if(!$this->db->query("SELECT * FROM `xDashAccounts` WHERE `username` = '{$req['login']}'")->num_rows()) {
        return $this->output->set_output(printJson(false,'Nie znaleziono użytkownika'));
      }
      if($this->session->userdata('login') == $req['login']) {
        return $this->output->set_output(printJson(false,'Nie możesz usunąć swojego konta!'));
      }
      $this->db->query("DELETE FROM `xDashAccounts` WHERE `username` = '{$req['login']}'");
      $this->db->query("DELETE FROM `xDashBotsUsers` WHERE `username` = '{$req['login']}'");
      $this->db->query("DELETE FROM `xDashPermissionsUsers` WHERE `username` = '{$req['login']}'");
      $this->db->query("DELETE FROM `xDashLoginHistory` WHERE `username` = '{$req['login']}'");
      return $this->output->set_output(printJson(true,'Pomyślnie usunięto użytkownika'));
    }
  }
 ?>
