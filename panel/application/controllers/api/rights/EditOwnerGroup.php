<?php
 /**
  * Information
  * @Author: xares
  * @Date:   26-05-2020 12:00
  * @Filename: editOwnerGroup.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 27-05-2020 22:35
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class EditOwnerGroup extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      if(!permission('editSettings')) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['groups'],['Podaj grupę/y!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }

      $groups = array();
      foreach(preg_split('/[,]/',$req['response']['groups'],-1,PREG_SPLIT_NO_EMPTY) as $id => $group) {
        $groups[] = (int)$group;
      }

      $this->load->library('TomlEditor');
      $this->tomleditor->ownerGroup = $groups;
      $save = $this->tomleditor->saveFile();
      if($save['success']) {
        $jsonGroup = json_encode($groups,JSON_PRETTY_PRINT);
        $this->db->query("UPDATE `xDashSettings` SET `value` = '{$jsonGroup}' WHERE `id` = 'ownerGroup'");
        $this->ts3ab->rightsReload();
        return $this->output->set_output(printJson(true,'Zapisano'));
      }
      return $this->output->set_output(printJson(false,$save['response']));
    }
  }
 ?>
