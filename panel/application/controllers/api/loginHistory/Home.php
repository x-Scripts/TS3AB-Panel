<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 21:01
  * @Filename: Home.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 10-05-2020 21:09
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Home extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $permissions = permission(['viewAllHistory','viewFullIP']);

      $history = array();
      $query = "SELECT * FROM `xDashLoginHistory`";
      if(!$permissions['viewAllHistory']) {
        $query .= " WHERE `username` = '{$this->session->userdata('login')}'";
      }
      foreach($this->db->query($query)->result_array() as $index) {
        if(!isset($history[$index['username']])) {
          $history[$index['username']] = array();
        }
        $history[$index['username']][] = array(
          'date' => date('d.m.Y H:i',$index['time']),
          'browser' => $index['browser'],
          'ip' => ((int)$permissions['viewFullIP'] ? $index['ip'] : censorIPAddress($index['ip'])),
        );
      }
      return $this->output->set_output(printJson(true,$history,empty($history)));
    }
  }
 ?>
