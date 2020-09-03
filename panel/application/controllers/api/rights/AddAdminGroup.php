<?php
 /**
  * Information
  * @Author: xares
  * @Date:   27-05-2020 20:57
  * @Filename: AddAdminGroup.php
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

  class AddAdminGroup extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      if(!permission('editSettings')) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['group','rights'],['Podaj id grupy!','Podaj uprawnienia!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }

      if(!is_numeric($req['response']['group'])) {
        return $this->output->set_output(printJson(false,'ID grupy może zawierać tylko cyfry!'));
      }

      $config = getConfigDB();

      if(isset($config['adminGroups'][(int)$req['response']['group']])) {
        return $this->output->set_output(printJson(false,'Dana grupa już istnieje!'));
      }

      $config['adminGroups'][(int)$req['response']['group']] = $req['response']['rights'];

      $groups = array();
      foreach($config['adminGroups'] as $groupid => $index) {
        $groups[(int)$groupid] = $index;
      }

      $this->load->library('TomlEditor');
      $this->tomleditor->adminGroups = $groups;
      $save = $this->tomleditor->saveFile();
      if($save['success']) {
        $jsonGroup = json_encode($groups,JSON_PRETTY_PRINT);
        $this->db->query("UPDATE `xDashSettings` SET `value` = '{$jsonGroup}' WHERE `id` = 'adminGroups'");
        $this->ts3ab->rightsReload();
        return $this->output->set_output(printJson(true,'Dodano grupę',$groups));
      }
      return $this->output->set_output(printJson(false,$save['response']));
    }
  }
 ?>
