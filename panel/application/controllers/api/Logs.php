<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 22:14
  * @Filename: Logs.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 10:43
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Logs extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      if(!permission('viewLogs')) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['date'],['Podaj datę!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }

      $log = $this->api->showLogs($req['response']['date']);

      if(!$log['success']) {
        return $this->output->set_output(printJson(false,$log['response']));
      }

      $result = array();
      $items = ["INFO", "WARN", "DEBUG", "ERROR"];
      $itemsreplace = ["<b class='text-info'>INFO</b>", "<b class='text-warning'>WARN</b>", "<b class='text-muted'>DEBUG</b>", "<b class='text-danger'>ERROR</b>"];

      foreach(preg_split('/['.PHP_EOL.']/',$log['response']) as $log) {
        if(!empty($log)) {
          $result[] = '<i class="fa fa-chevron-right" aria-hidden="true"></i> '.str_replace($items, $itemsreplace, $log);
        }
      }

      return $this->output->set_output(printJson(true,implode(PHP_EOL,$result)));
    }

    public function delete() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      if(!permission('viewLogs')) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['date'],['Podaj datę!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }

      $remove = $this->api->removeLogs($req['response']['date']);

      if(!$remove['success']) {
        return $this->output->set_output(printJson(false,$remove['response']));
      }
      return $this->output->set_output(printJson(true,'Usunięto logi'));
    }
  }
 ?>
