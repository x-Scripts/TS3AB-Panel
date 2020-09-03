<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 21:44
  * @Filename: Logs.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 26-05-2020 11:14
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Logs extends CI_Controller {
    public function index() {
      if(!$this->session->userdata('logged')) {
        redirect(base_url('login?redirect=logs'));
      }
      if(!permission('viewLogs')) {
        $this->session->set_userdata('alert',array('success' => false, 'message' => 'Nie posiadasz dostępu!'));
        redirect(base_url('dash'));
      }
      $logs = $this->api->logs();
      if($logs['success']) {
        $this->load->helper('language');
        $response = array();
        foreach($logs['response'] as $index) {
          $date = preg_split('/[-]/',$index);
          $date = "{$date[0]} ".lang('date')['months_num'][$date[1]]." {$date[2]}";
          $response[strtotime($index)] = "<option value=\"$index\">$date</option>";
        }
        krsort($response);
        $logs['response'] = implode(PHP_EOL,$response);
      }
      $this->load->view('header',array('title' => 'Logi'));
      $this->load->view('logs/home',array('logs' => $logs));
      $this->load->view('footer',array('loadjs' => 'logs'));
    }
  }
 ?>
