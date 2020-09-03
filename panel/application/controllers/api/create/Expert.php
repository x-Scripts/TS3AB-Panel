<?php
 /**
  * Information
  * @Author: xares
  * @Date:   18-05-2020 21:11
  * @Filename: Expert.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 21-05-2020 12:31
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Expert extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }
      $merge = array('json/merge');
      $ids = array();
      $permissions = permission(['createExpert','viewAllBots','limitBots']);
      if(!$permissions['createExpert']) {
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
        'loadTitleSongToDesc',
        'defaultVolumeSong',
        'maxVolumeUser',
        'setBitrate',
        'coloredChat',
        'minMaxVol',
        'commandMatcher',
        'longMessage',
        'longMessageSplitLimit',
        'commandComplexity'
      ],[
        'Podaj ip serwera!',
        'Podaj nazwę bota!',
        'Podaj język bota!',
        'Podaj id grup uprawnionych do korzystania z bota!',
        'Podaj czy bot ma się łączyć przy starcie aplikacji!',
        'Podaj czy ma być ładowany awatar piosenki!',
        'Podaj czy w opis klienta ma być tytuł!',
        'Podaj domyślną głośność piosenek',
        'Podaj maksymalną głośność dla użytkownika',
        'Podaj jakość przesyłanego dźwięku',
        'Podaj czy ma być kolorowy czat',
        'Podaj minimalną i maksymalną głośność',
        'Podaj dopasowanie komend',
        'Podaj długość wiadomości',
        'Podaj limit podziału wiadomości',
        'Podaj złożność poleceń'
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

      $json[] = "(/settings/bot/set/{$random}/audio.volume.default/{$req['defaultVolumeSong']})";
      $json[] = "(/settings/bot/set/{$random}/audio.max_user_volume/{$req['maxVolumeUser']})";
      $json[] = "(/settings/bot/set/{$random}/audio.bitrate/{$req['setBitrate']})";
      $json[] = "(/settings/bot/set/{$random}/audio.volume.min/{$req['minMaxVol'][0]})";
      $json[] = "(/settings/bot/set/{$random}/audio.volume.max/{$req['minMaxVol'][1]})";

      $json[] = "(/settings/bot/set/{$random}/commands.color/{$req['coloredChat']})";
      $json[] = "(/settings/bot/set/{$random}/commands.long_message/{$req['longMessage']})";
      $json[] = "(/settings/bot/set/{$random}/commands.long_message_split_limit/{$req['longMessageSplitLimit']})";
      $json[] = "(/settings/bot/set/{$random}/commands.matcher/{$req['commandMatcher']})";
      $json[] = "(/settings/bot/set/{$random}/commands.command_complexity/{$req['commandComplexity']})";

      if(!empty($req['badges'])) {
        $badgesSplit1 = preg_split('|:badges=|',$req['badges']);
        if(count($badgesSplit1) >= 2) {
          $badgesSplit2 = preg_split('/[,]/',$badgesSplit1[1]);
          if(count($badgesSplit2) > 3) {
            return $this->output->set_output(printJson(false,'Przydzielono za dużo odznak!'));
          }
        }
        $json[] = "(/settings/bot/set/{$random}/connect.badges/\"".rawurlencode($req['badges'])."\")";
      }

      if(!empty($req['channelPassword']) && !empty($req['channelPasswordHash']) && !empty($req['channelPasswordAutoHash'])) {
        $json[] = "(/settings/bot/set/{$random}/connect.channel_password.pw/\"".rawurlencode($req['channelPassword'])."\")";
        $json[] = "(/settings/bot/set/{$random}/connect.channel_password.hashed/{$req['channelPasswordHash']})";
        $json[] = "(/settings/bot/set/{$random}/connect.channel_password.autohash/{$req['channelPasswordAutoHash']})";
      }

      if(!empty($req['serverPassword']) && !empty($req['serverPasswordHash']) && !empty($req['serverPasswordAutoHash'])) {
        $json[] = "(/settings/bot/set/{$random}/connect.server_password.pw/\"".rawurlencode($req['serverPassword'])."\")";
        $json[] = "(/settings/bot/set/{$random}/connect.server_password.hashed/{$req['serverPasswordHash']})";
        $json[] = "(/settings/bot/set/{$random}/connect.server_password.autohash/{$req['serverPasswordAutoHash']})";
      }

      if(!empty($req['clientVersion']['platform']) && !empty($req['clientVersion']['sign']) && !empty($req['clientVersion']['build'])) {
        $json[] = "(/settings/bot/set/{$random}/connect.client_version.build/\"".rawurlencode($req['clientVersion']['build'])."\")";
        $json[] = "(/settings/bot/set/{$random}/connect.client_version.sign/\"".rawurlencode($req['clientVersion']['sign'])."\")";
        $json[] = "(/settings/bot/set/{$random}/connect.client_version.platform/\"".rawurlencode($req['clientVersion']['platform'])."\")";
      }

      if(!empty($req['onConnect'])) {
        $json[] = "(/settings/bot/set/{$random}/events.onconnect/'".rawurlencode($req['onConnect'])."')";
      }

      if(!empty($req['onDisconnect'])) {
        $json[] = "(/settings/bot/set/{$random}/events.ondisconnect/'".rawurlencode($req['onDisconnect'])."')";
      }

      if(!empty($req['onIdle'])) {
        $json[] = "(/settings/bot/set/{$random}/events.onidle/'".rawurlencode($req['onIdle'])."')";
      }

      if(!empty($req['idleTime'])) {
        $json[] = "(/settings/bot/set/{$random}/events.idletime/'".rawurlencode($req['idleTime'])."')";
      }

      if(!empty($req['onAlone'])) {
        $json[] = "(/settings/bot/set/{$random}/events.onalone/'".rawurlencode($req['onAlone'])."')";
      }

      if(!empty($req['aloneDelay'])) {
        $json[] = "(/settings/bot/set/{$random}/events.alone_delay/'".rawurlencode($req['aloneDelay'])."')";
      }

      if(!empty($req['onParty'])) {
        $json[] = "(/settings/bot/set/{$random}/events.onparty/'".rawurlencode($req['onParty'])."')";
      }

      if(!empty($req['partyDelay'])) {
        $json[] = "(/settings/bot/set/{$random}/events.party_delay/'".rawurlencode($req['partyDelay'])."')";
      }

      $this->ts3ab->command(implode('/',$json));
      $this->ts3ab->botConnect($random);

      if(!empty($req['aliases'])) {
        $added = true;
        while($added) {
          usleep(500000);
          foreach(json_decode($this->ts3ab->botList(),true) as $index) {
            if($index['Name'] == $random) {
              if($index['Status']) {
                $this->ts3ab->setBotID($index['Id']);
                foreach($req['aliases'] as $id => $item) {
                  $this->ts3ab->commandBot("alias/add/\"".rawurlencode($id)."\"/\"".rawurlencode($item)."\"");
                }
                $added = false;
              }
            }
          }
        }
      }

      $this->db->query("INSERT INTO `xDashBotList`(`id`,`timeCreate`) VALUES ('{$random}','".time()."')");
      if(!$permissions['viewAllBots']) {
        $this->db->query("INSERT INTO `xDashBotsUsers`(`username`,`botID`,`timeAdd`) VALUES ('{$this->session->userdata('login')}','{$random}','".time()."')");
      }

      return $this->output->set_output(printJson(true,'Pomyślnie stworzono bota!',$random));
    }
  }
 ?>
