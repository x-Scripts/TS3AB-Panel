<?php
 /**
  * Information
  * @Author: xares
  * @Date:   01-06-2020 14:57
  * @Filename: Logged.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 01-06-2020 15:52
  *
  * @Copyright(C) 2020 x-Scripts
  */


  class Logged extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if($this->session->userdata('logged')) {
        return $this->output->set_output(printJson(true,true));
      }
      $this->load->helper('cookie');
      $cookie = get_cookie('xDashRemember');
      if($cookie != null) {
        $db = $this->db->query("SELECT * FROM xDashRemembers INNER JOIN xDashAccounts USING(username) WHERE id = '$cookie'");
        if($db->num_rows()) {
          $db = $db->result_array()[0];
          $this->session->set_userdata(array(
            'login' => $db['username'],
            'password' => $db['password'],
            'logged' => true,
            'avatar' => $db['clientAvatar'],
            'twoAuth' => $db['tokenAuthentication'] == null ? null : $this->coder->decode($db['tokenAuthentication'])
          ));
          return $this->output->set_output(printJson(true,true));
        }
        delete_cookie('xDashRemember');
      }
      return $this->output->set_output(printJson(false,base_url('login')));
    }
  }
 ?>
