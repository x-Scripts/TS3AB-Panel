<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 23:07
  * @Filename: Usage.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 27-05-2020 23:27
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Usage extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      if(!permission('viewUsage')) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $systeminfo = json_decode($this->ts3ab->command('system/info'),true);

      if(isset($systeminfo['ErrorCode'])) {
        return $this->output->set_output(printJson(false,$systeminfo['ErrorMessage']));
      }

      $serverinfo = $this->api->serverInfo();
      if(!$serverinfo['success']) {
        return $this->output->set_output(printJson(false,$serverinfo['response']));
      }

      $result = array(
        'cpu' => array(
          'usage' => array(
            round(end($systeminfo['cpu'])*100,1),
            round(100-(end($systeminfo['cpu'])*100),1)
          ),
          'color' => array(
            '#6f39c1',
            '#864dd9e0'
          )
        ),
        'ram' => array(
          'usage' => array(
            bytesToMB(end($systeminfo['memory']),2),
            bytesToMB($serverinfo['response']['memory']['Mem']['total']-end($systeminfo['memory']),2)
          ),
          'color' => array(
            '#1e7e34',
            '#1f9039'
          )
        ),
        'cpuServer' => array(
          'usage' => array(
            round($serverinfo['response']['cpu']['usage'],1),
            round($serverinfo['response']['cpu']['idle'],1)
          ),
          'color' => array(
            '#6f39c1',
            '#864dd9e0'
          )
        ),
        'ramServer' => array(
          'usage' => array(
            bytesToMB(($serverinfo['response']['memory']['Mem']['total']-$serverinfo['response']['memory']['Mem']['free']),2),
            bytesToMB($serverinfo['response']['memory']['Mem']['free'],2)
          ),
          'color' => array(
            '#1e7e34',
            '#1f9039'
          )
        ),
        'diskUsage' => array(
          'usage' => array(
            bytesToMB($serverinfo['response']['disk']['usage'],2),
            bytesToMB($serverinfo['response']['disk']['free'],2)
          ),
          'color' => array(
            '#007bff',
            '#2e90f9'
          )
        )
      );

      return $this->output->set_output(printJson(true,null,$result));
    }
  }
 ?>
