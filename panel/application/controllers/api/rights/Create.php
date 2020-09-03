<?php
 /**
  * Information
  * @Author: xares
  * @Date:   26-05-2020 11:50
  * @Filename: Create.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 00:57
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Create extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $permissions = permission(['createSimple','createAdvanced','createExpert']);
      if(!($permissions['createSimple'] || $permissions['createAdvanced'] || $permissions['createExpert'])) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['botID','groups'],['Podaj id bota!','Podaj id grup uprawnionych do korzystania z bota!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      $this->load->library('TomlEditor');

      if(empty($req['rules'])) {
        $req['rules'] = array();
      }
      if(empty($req['users'])) {
        $req['users'] = array();
      }
      $users = '[]';

      $groups = array();
      foreach(preg_split('/[,]/',$req['groups']) as $item) {
        $groups[] = (int)$item;
      }

      if($this->db->query("SELECT * FROM `xDashBotRights` WHERE `id` = '{$req['botID']}'")->num_rows()) {
        return $this->output->set_output(printJson(false,'Taka tablica już istnieje!'));
      }

      foreach($req['rules'] as $id => $rules) {
        if(empty($rules)) {
          unset($req['rules'][$id]);
        }
      }
      $jsonGroup = json_encode($groups,JSON_PRETTY_PRINT);
      $this->db->query("INSERT INTO `xDashBotRights`(`id`,`clientPanel`,`clientFiles`,`rightsCmd`,`groups`) VALUES ('{$req['botID']}','$users','".json_encode($req['users'],JSON_PRETTY_PRINT)."','".json_encode($req['rules'],JSON_PRETTY_PRINT)."','{$jsonGroup}')");
      $this->tomleditor->createTable($req['botID'],$groups,$req['rules'],$req['users']);
      $save = $this->tomleditor->saveFile();

      if($save['success']) {
        $this->ts3ab->rightsReload();
        return $this->output->set_output(printJson(true,'Pomyślnie zapisano uprawnienia!'));
      }
      return $this->output->set_output(printJson(false,$save['response']));

    }
  }
 ?>
