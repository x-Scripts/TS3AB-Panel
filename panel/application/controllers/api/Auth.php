<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 22:04
  * @Filename: Auth.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 25-05-2020 13:30
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Auth extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if($this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Jesteś już zalogowany!'));
      }
      $post = $this->input->post();
      $req = request($post,['login','password','remember']);
      if(!$req['success']) {
        if(empty($post['login']) && empty($post['password'])) {
          return $this->output->set_output(printJson(false,'Podaj login i hasło','all'));
        } elseif(empty($post['login'])) {
          return $this->output->set_output(printJson(false,'Podaj login','login'));
        } else {
          return $this->output->set_output(printJson(false,'Podaj hasło','password'));
        }
      }
      $req = $req['response'];
      $access = $this->db->query("SELECT * FROM `xDashAccounts`")->result_array();
      foreach($access as $index) {
        if($req['login'] == $index['username'] && password_verify($req['password'],$index['password'])) {
          if($index['tokenAuthentication'] != null) {
            $this->session->set_userdata('login',$index['username']);
            return $this->output->set_output(printJson(true,'Wpisz token z połączonej aplikacji',true));
          }
          $session = array(
            'password' => $index['password'],
            'login' => $post['login'],
            'logged' => true,
            'avatar' => $index['clientAvatar'],
            'twoAuth' => $index['tokenAuthentication'] == null ? null : $this->coder->decode($index['tokenAuthentication'])
          );
          $this->load->library('user_agent');
          $this->db->query("INSERT INTO `xDashLoginHistory`(`username`,`time`,`browser`,`ip`) VALUES ('{$index['username']}','".time()."','".html_escape($this->agent->browser().'/'.$this->agent->version(), true)."','".ip_address()."')");
          $this->session->set_userdata($session);
          if($req['remember'] == "true") {
            $this->load->helper('cookie');
            $token = randomString(20,array_column($this->db->query("SELECT * FROM `xDashRemembers`")->result_array(),'id'));
            $this->db->query("INSERT INTO `xDashRemembers`(`id`,`username`,`time`,`platform`,`browser`) VALUES ('$token','".$req['login']."',".time().",'{$this->agent->platform()}','{$this->agent->browser()}')");
            set_cookie('xDashRemember',$token,3600*24*365);
          }
          return $this->output->set_output(printJson(true,'Pomyślnie zweryfikowano',base_url($post['redirect'] == null ? 'dash' : $post['redirect'])));
        }
      }
      return $this->output->set_output(printJson(false,'Podano zły login lub hasło!','all'));
    }

    public function verifyCode() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if($this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Jesteś już zalogowany!'));
      }

      if($this->session->userdata('login') == false) {
        return $this->output->set_output(printJson(false,'Najpierw wybierz konto!'));
      }

      $account = $this->db->query("SELECT * FROM `xDashAccounts` WHERE `username` = '{$this->session->userdata('login')}'");
      if(!$account->num_rows()) {
        return $this->output->set_output(printJson(false,'Nie znaleziono konta!'));
      }

      $account = $account->result_array()[0];
      $post = $this->input->post();
      $req = request($this->input->post(),['token','remember'],['Podaj token weryfikacyjny!','Podaj czy zapamiętać logowanie']);

      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if($this->googleauth->verifyCode($this->coder->decode($account['tokenAuthentication']),$req['token'])) {
        $session = array(
          'password' => $account['password'],
          'login' => $this->session->userdata('login'),
          'logged' => true,
          'avatar' => $account['clientAvatar'],
          'twoAuth' => $this->coder->decode($account['tokenAuthentication'])
        );
        $this->load->library('user_agent');
        $this->db->query("INSERT INTO `xDashLoginHistory`(`username`,`time`,`browser`,`ip`) VALUES ('{$account['username']}','".time()."','".html_escape($this->agent->browser().'/'.$this->agent->version(), true)."','".ip_address()."')");
        $this->session->set_userdata($session);
        if($req['remember'] == "true") {
          $this->load->helper('cookie');
          $token = randomString(20,array_column($this->db->query("SELECT * FROM `xDashRemembers`")->result_array(),'id'));
          $this->db->query("INSERT INTO `xDashRemembers`(`id`,`username`,`time`,`platform`,`browser`) VALUES ('$token','".$account['username']."',".time().",'{$this->agent->platform()}','{$this->agent->browser()}')");
          set_cookie('xDashRemember',$token,3600*24*365);
        }
        return $this->output->set_output(printJson(true,'Pomyślnie zweryfikowano',base_url($post['redirect'] == null ? 'dash' : $post['redirect'])));
      }

      return $this->output->set_output(printJson(false,'Podano zły token!'));
    }
  }
 ?>
