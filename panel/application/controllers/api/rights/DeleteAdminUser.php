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
  * @Last Modified time: 27-05-2020 22:35
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class DeleteAdminUser extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      if(!permission('editSettings')) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['user'],['Podaj użytkownika!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }

      $config = getConfigDB();

      unset($config['adminUsers'][$req['response']['user']]);

      $this->load->library('TomlEditor');
      $this->tomleditor->adminUsers = $config['adminUsers'];
      $save = $this->tomleditor->saveFile();
      if($save['success']) {
        $jsonUsers = json_encode($config['adminUsers'],JSON_PRETTY_PRINT);
        $this->db->query("UPDATE `xDashSettings` SET `value` = '{$jsonUsers}' WHERE `id` = 'adminUsers'");
        $this->ts3ab->rightsReload();
        return $this->output->set_output(printJson(true,'Usunięto użytkownika',$config['adminUsers']));
      }
      return $this->output->set_output(printJson(false,$save['response']));
    }
  }
 ?>
