<?php
 /**
  * Information
  * @Author: xares
  * @Date:   21-05-2020 13:09
  * @Filename: MinMaxVol.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 21-05-2020 13:14
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class MinMaxVol extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $permissions = permission(['editExpertBot','viewAllBots']);
      if(!$permissions['editExpertBot']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['botID','value'],['Podaj id bota!','Podaj minimalną i maksymalną głośność!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if(!is_array($req['value'])) {
        return $this->output->set_output(printJson(false,'Podaj minimalną i maksymalną głośność!'));
      }

      if(!$permissions['viewAllBots']) {
        if(!$this->db->query("SELECT * FROM `xDashBotsUsers` WHERE `username` = '{$this->session->userdata('login')}' AND `botID` = '{$req['botID']}'")->num_rows()) {
          return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
        }
      }

      if(!$this->db->query("SELECT * FROM `xDashBotList` WHERE `id` = '{$req['botID']}'")->num_rows()) {
        return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
      }

      if(!is_numeric($req['value'][0]) && !is_numeric($req['value'][1])) {
        return $this->output->set_output(printJson(false,'Głośność można podać tylko w liczbach!'));
      }

      $json = array('json/merge');

      $json[] = "(/settings/bot/set/{$req['botID']}/audio.volume.min/{$req['value'][0]})";
      $json[] = "(/settings/bot/set/{$req['botID']}/audio.volume.max/{$req['value'][1]})";

      $run = json_decode($this->ts3ab->command(implode('/',$json)),true);
      if(isset($run[0]['ErrorMessage'])) {
        return $this->output->set_output(printJson(false,$run[0]['ErrorMessage']));
      }


      return $this->output->set_output(printJson(true,'Zapisano'));
    }
  }
 ?>
