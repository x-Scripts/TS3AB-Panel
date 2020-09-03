<?php
 /**
  * Information
  * @Author: xares
  * @Date:   25-05-2020 10:32
  * @Filename: App.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 25-05-2020 12:20
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class App extends CI_Controller {
    public function start() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      if(!permission('startStopApp')) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $app = $this->api->startApp();
      if(!$app['success']) {
        return $this->output->set_output(printJson(false,$app['response']));
      }

      return $this->output->set_output(printJson(true,'Aplikacja została uruchomiona!'));
    }

    public function stop() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      if(!permission('startStopApp')) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $check = $this->api->checkIsAppEnabled();
      if(!$check['success']) {
        return $this->output->set_output(printJson(false,$check['response']));
      }
      if($check['response']) {
        $list = json_decode($this->ts3ab->botList(),true);
        if(!isset($list['ErrorCode'])) {
          foreach($list as $index) {
            if($index['Status']) {
              $this->ts3ab->setBotID($index['Id']);
              $this->ts3ab->botDisconnect();
            }
          }
        }
      }

      $app = $this->api->stopApp();
      if(!$app['success']) {
        return $this->output->set_output(printJson(false,$app['response']));
      }

      return $this->output->set_output(printJson(true,'Aplikacja została wyłączona!'));
    }

    public function restart() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      if(!permission('startStopApp')) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $check = $this->api->checkIsAppEnabled();
      if(!$check['success']) {
        return $this->output->set_output(printJson(false,$check['response']));
      }

      if($check['response']) {
        $list = json_decode($this->ts3ab->botList(),true);
        if(!isset($list['ErrorCode'])) {
          foreach($list as $index) {
            if($index['Status']) {
              $this->ts3ab->setBotID($index['Id']);
              $this->ts3ab->botDisconnect();
            }
          }
        }
      }
      
      $app = $this->api->restartApp();
      if(!$app['success']) {
        return $this->output->set_output(printJson(false,$app['response']));
      }

      return $this->output->set_output(printJson(true,'Aplikacja została uruchomiona!'));
    }
  }
 ?>
