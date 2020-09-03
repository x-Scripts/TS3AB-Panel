<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 21:40
  * @Filename: Login.php
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

  class Login extends CI_Controller {
    public function index() {
      if($this->session->userdata('logged')) {
        redirect(base_url('dash'));
      }

      $this->load->helper('cookie');
      $cookie = get_cookie('xDashRemember');
      $redirect = $this->input->get('redirect',true);
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
          $this->load->library('user_agent');
          return redirect(base_url(($redirect == null ? 'dash' : $redirect)));
        }
        delete_cookie('xDashRemember');
      }
      $url = parse_url(base_url());
      $data['redirect'] = $redirect;
      $this->load->view('login',$data);
    }
  }
 ?>
