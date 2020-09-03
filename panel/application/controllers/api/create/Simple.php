<?php
 /**
  * Information
  * @Author: xares
  * @Date:   18-05-2020 03:06
  * @Filename: Simple.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 18-05-2020 23:10
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Simple extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $merge = array('json/merge');
      $ids = array();
      $permissions = permission(['createSimple','viewAllBots','limitBots']);
      if(!$permissions['createSimple']) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      if($permissions['limitBots'] >= 1) {
        $db = $this->db->query("SELECT * FROM `xDashBotsUsers` WHERE `username` = '{$this->session->userdata('login')}'");
        if($db->num_rows() >= $permissions['limitBots']) {
          return $this->output->set_output(printJson(false,'Osiągnąłeś limit botów!'));
        }
      }

      $req = request($this->input->post(),[
        'serverIP',
        'botName',
        'langBot',
        'groupID',
        'runBot',
        'loadAvatar',
        'loadTitleSongToDesc'
      ],[
        'Podaj ip serwera!',
        'Podaj nazwę bota!',
        'Podaj język bota!',
        'Podaj id grup uprawnionych do korzystania z bota!',
        'Podaj czy bot ma się łączyć przy starcie aplikacji!',
        'Podaj czy ma być ładowany awatar piosenki!',
        'Podaj czy w opis klienta ma być tytuł!'
      ]);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }

      $req = $req['response'];

      if(mb_strlen($req['botName'],'utf8') > 32) {
        return $this->output->set_output(printJson(false,'Nazwa bota może mieć maksymalnie 32 znaki!'));
      }

      $bots = array();
      foreach($this->db->query("SELECT * FROM `xDashBotList`")->result_array() as $index) {
        $bots[] = $index['id'];
      }

      $random = RandomString();
      while(in_array($random,$bots)) {
        $random = RandomString();
      }
      $json = array('json/merge','(/settings/create/'.$random.')');
      $json[] = "(/settings/bot/set/{$random}/connect.name/".rawurlencode($req['botName']).")";
      $json[] = "(/settings/bot/set/{$random}/connect.address/{$req['serverIP']})";
      $json[] = "(/settings/bot/set/{$random}/language/{$req['langBot']})";
      $json[] = "(/settings/bot/set/{$random}/connect.channel/\"/{$req['channelID']}\")";
      $json[] = "(/settings/bot/set/{$random}/run/{$req['runBot']})";
      $json[] = "(/settings/bot/set/{$random}/generate_status_avatar/{$req['loadAvatar']})";
      $json[] = "(/settings/bot/set/{$random}/set_status_description/{$req['loadTitleSongToDesc']})";


      $this->db->query("INSERT INTO `xDashBotList`(`id`,`timeCreate`) VALUES ('{$random}','".time()."')");
      if(!$permissions['viewAllBots']) {
        $this->db->query("INSERT INTO `xDashBotsUsers`(`username`,`botID`,`timeAdd`) VALUES ('{$this->session->userdata('login')}','{$random}','".time()."')");
      }
      $this->ts3ab->command(implode('/',$json));
      $this->ts3ab->botConnect($random);

      return $this->output->set_output(printJson(true,'Pomyślnie stworzona bota!',$random));
    }
  }
 ?>
