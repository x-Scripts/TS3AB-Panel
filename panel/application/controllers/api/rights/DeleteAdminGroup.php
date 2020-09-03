<?php
 /**
  * Information
  * @Author: xares
  * @Date:   27-05-2020 00:25
  * @Filename: DeleteAdminUser.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 27-05-2020 22:36
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class DeleteAdminGroup extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      if(!permission('editSettings')) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['group'],['Podaj id grupy!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }

      $config = getConfigDB();

      unset($config['adminGroups'][$req['response']['group']]);

      $this->load->library('TomlEditor');
      $this->tomleditor->adminUsers = $config['adminGroups'];
      $save = $this->tomleditor->saveFile();
      if($save['success']) {
        $jsonGroups = json_encode($config['adminGroups'],JSON_PRETTY_PRINT);
        $this->db->query("UPDATE `xDashSettings` SET `value` = '{$jsonGroups}' WHERE `id` = 'adminGroups'");
        $this->ts3ab->rightsReload();
        return $this->output->set_output(printJson(true,'Usunięto grupę',$config['adminGroups']));
      }
      return $this->output->set_output(printJson(false,$save['response']));
    }
  }
 ?>
