<?php
 /**
  * Information
  * @Author: xares
  * @Date:   25-05-2020 13:46
  * @Filename: Settings.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 29-05-2020 13:47
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Settings extends CI_Controller {
    public function index() {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login?redirect=settings'));
      }
      if(!permission('editSettings')) {
        $this->session->set_userdata('alert',array('success' => false,'message' => 'Nie posiadasz dostępu!'));
        redirect(base_url('dash'));
        return;
      }

      $data['settings'] = getConfigDB();

      $this->load->view('header',array('title' => 'Ustawienia panelu'));
      $this->load->view('settings',$data);
      $this->load->view('footer',array('loadjs' => 'settings','const' => array('defRights' => json_encode($this->rights($this->config->item('allRights'))))));
    }

    private function rights($config, $cmd = '') {
      foreach($config as $index => $item) {
        if(is_array($item)) {
          if(isset($item['*'])) {
            if($item['*']) {
              $this->defRights[] = $cmd.$index.'.*';
            }
          }
          $this->rights($item, $cmd.$index.'.');
        } else {
          if($index != '*') {
            if($item) {
              $this->defRights[] = $cmd.$index;
            }
          }
        }
      }
      return $this->defRights;
    }

    private $defRights = array();
  }
 ?>
