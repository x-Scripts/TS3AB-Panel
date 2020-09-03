<?php
 /**
  * Information
  * @Author: xares
  * @Date:   24-05-2020 22:28
  * @Filename: Account.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 21:20
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Account extends CI_Controller {
    public function generateTwoAuth() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $key = $this->googleauth->createSecret(20);
      $result = array(
        'img' => $this->googleauth->getQRCodeGoogleUrl($this->session->userdata('login'),$key,'xDashTS3AB - '.parse_url(base_url())['host']),
        'key' => $key
      );

      return $this->output->set_output(printJson(true,null,$result));
    }

    public function verifyTwoAuth() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $req = request($this->input->post(),['secretKey','tokenAuth'],['Podaj sekretny klucz!','Podaj token weryfikacyjny!']);

      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if($this->googleauth->verifyCode($req['secretKey'],$req['tokenAuth'])) {
        $secret = $this->coder->encode($req['secretKey']);
        $this->session->set_userdata('twoAuth',$req['secretKey']);
        $this->db->query("UPDATE `xDashAccounts` SET `tokenAuthentication` = '$secret' WHERE `username` = '{$this->session->userdata('login')}'");
        return $this->output->set_output(printJson(true,'Pomyślnie zweryfikowano autoryzację!'));
      }

      return $this->output->set_output(printJson(false,'Nie udało się zweryfikować autoryzacji<br>Sprawdź czy poprawnie dodałeś autoryzację do urządzenia!'));
    }

    public function deleteTwoAuth() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $this->session->set_userdata('twoAuth',null);

      $this->db->query("UPDATE `xDashAccounts` SET `tokenAuthentication` = NULL WHERE `username` = '{$this->session->userdata('login')}'");
      return $this->output->set_output(printJson(true,'Pomyślnie usunięto weryfikację!'));
    }

    public function editPassword() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $req = request($this->input->post(),['newPassword','password'],['Podaj nowe hasło!','Podaj aktualne hasło!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if(empty($req['password'])) {
        return $this->output->set_output(printJson(false,'Podaj aktualne hasło!'));
      }

      if(empty($req['newPassword'])) {
        return $this->output->set_output(printJson(false,'Podaj nowe hasło!'));
      }

      if(!password_verify($req['password'],$this->session->userdata('password'))) {
        return $this->output->set_output(printJson(false,'Aktalne hasło jest niepoprawne!'));
      }

      if(password_verify($req['newPassword'],$this->session->userdata('password'))) {
        return $this->output->set_output(printJson(false,'Nowe hasło jest takie samo jak poprzednie!'));
      }

      $password = password_hash($req['newPassword'],PASSWORD_DEFAULT);
      $this->session->set_userdata('password',$password);
      $this->db->query("UPDATE `xDashAccounts` SET `password` = '{$password}' WHERE `username` = '{$this->session->userdata('login')}'");
      return $this->output->set_output(printJson(true,'Pomyślnie zmieniono hasło'));
    }

    public function deleteRemember() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $req = request($this->input->post(),['id'],['Podaj które urządzenie usunąć!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];
      $this->load->helper('cookie');
      $cookie = get_cookie('xDashRemember');

      $this->db->query("DELETE FROM `xDashRemembers` WHERE `id` = '{$req['id']}'");

      $remember = array();
      foreach($this->db->query("SELECT * FROM `xDashRemembers` WHERE `username` = '{$this->session->userdata('login')}'")->result_array() as $index) {
        $remember[] = array(
          'id' => $index['id'],
          'platform' => $index['id'] == $cookie ? 'Ta sesja' : $index['platform'],
          'browser' => $index['id'] == $cookie ? 'Ta sesja' : $index['browser']
        );
      }
      return $this->output->set_output(printJson(true,'Pomyślnie usunięto urządzenie',$remember));
    }
  }
 ?>
