<?php
 /**
  * Information
  * @Author: xares
  * @Date:   18-05-2020 23:17
  * @Filename: Home.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 21-05-2020 17:11
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class Home extends CI_Controller {
    public function index() {
      $this->output->set_content_type('application/json')->set_status_header(200);
      if(!$this->session->userdata('logged')) {
        return $this->output->set_output(printJson(false,'Najpierw się zaloguj!'));
      }

      $permissions = permission(['editExpertBot','editAdvancedBot','editSimpleBot','editRightsBot','addUsersBot','viewAllBots']);
      if(!($permissions['editExpertBot'] || $permissions['editAdvancedBot'] || $permissions['editSimpleBot'] || $permissions['editRightsBot'] || $permissions['addUsersBot'])) {
        return $this->output->set_output(printJson(false,'Nie posiadasz dostępu!'));
      }

      $req = request($this->input->post(),['botID'],['Podaj id bota!']);
      if(!$req['success']) {
        return $this->output->set_output(printJson(false,$req['response']));
      }
      $req = $req['response'];

      if(!$permissions['viewAllBots']) {
        if(!$this->db->query("SELECT * FROM `xDashBotsUsers` WHERE `username` = '{$this->session->userdata('login')}' AND `botID` = '{$req['botID']}'")->num_rows()) {
          return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
        }
      }

      if(!$this->db->query("SELECT * FROM `xDashBotList` WHERE `id` = '{$req['botID']}'")->num_rows()) {
        return $this->output->set_output(printJson(false,'Nie znaleziono bota!'));
      }


      //return var_dump(json_decode($this->ts3ab->command("settings/bot/get/{$req['botID']}"),true));
      $configBot = json_decode($this->ts3ab->command("settings/bot/get/{$req['botID']}"),true);
      if(isset($configBot['ErrorMessage'])) {
        return $this->output->set_output(printJson(false,$configBot['ErrorMessage']));
      }

      //return var_dump($configBot);

      $rights = $this->db->query("SELECT * FROM `xDashBotRights` WHERE `id` = '{$req['botID']}'");

      if($rights->num_rows()) {
        $rights = $rights->result_array()[0];
      } else {
        $rights = '[]';
      }

      $rights['groups'] = json_decode($rights['groups'],true);

      $result = array(
        'checkbox' => array(
          'run-bot' => $configBot['run'],
          'load-avatar' => $configBot['generate_status_avatar'],
          'load-title' => $configBot['set_status_description'],
        ),
        'text' => array(
          'bot-name' => $configBot['connect']['name'],
          'group-id' => @isset($rights['groups'][0]) ? implode(',',$rights['groups']) : '',
          'channel-id' => @end(preg_split('/[\/]/',$configBot['connect']['channel'])),
          'server-ip' => $configBot['connect']['address']
        ),
        'select' => array(
          $configBot['language']
        ),
        'rights' => @json_decode($rights['rightsCmd'],true),
        'clients' => @json_decode($rights['clientPanel'],true)
      );
      if($permissions['editAdvancedBot'] || $permissions['editExpertBot']) {
        $badgesRes = preg_split('/[:]/',$configBot['connect']['badges'],-1,PREG_SPLIT_NO_EMPTY);
        $result['checkbox']['server-password-hash'] = $configBot['connect']['server_password']['hashed'];
        $result['checkbox']['server-password-autohash'] = $configBot['connect']['server_password']['autohash'];
        $result['checkbox']['channel-password-hash'] = $configBot['connect']['channel_password']['hashed'];
        $result['checkbox']['channel-password-autohash'] = $configBot['connect']['channel_password']['autohash'];
        $result['checkbox']['overwolf'] = isset($badgesRes[0]) ? (boolean)str_replace('overwolf=','',strtolower($badgesRes[0])) : false;
        $result['text']['server-password'] = $configBot['connect']['server_password']['pw'];
        $result['text']['channel-password'] = $configBot['connect']['channel_password']['pw'];
        $result['text']['change-volume'] = $configBot['audio']['volume']['default'];
        $result['text']['change-volume-max'] = $configBot['audio']['max_user_volume'];
        $result['bitrate'] = $configBot['audio']['bitrate'];
        $result['textView'] = array(
          'bitrate' => $configBot['audio']['bitrate'],
          'volume' => $configBot['audio']['volume']['default'],
          'volume-max' => $configBot['audio']['max_user_volume']
        );
        $result['badges'] = isset($badgesRes[1]) ? search_badges(preg_split('/[,]/',str_replace('badges=','',strtolower($badgesRes[1])),-1,PREG_SPLIT_NO_EMPTY)) : [];
        $result['version'] = $configBot['connect']['client_version']['sign'] == '' ? 'default-version' : $configBot['connect']['client_version']['sign'];
      }
      if($permissions['editExpertBot']) {
        $alias = array();
        foreach($configBot['commands']['alias'] as $id => $index) {
          $alias[$id] = str_replace(array('[URL]','[/URL]','[url]','[/url]'),'',$index);
        }
        $result['colored-chat'] = $configBot['commands']['color'];
        $result['select'][] = $configBot['commands']['long_message'] == 0 ? 'split' : 'drop';
        $result['select'][] = $configBot['commands']['matcher'];
        $result['text']['long-message-split-limit'] = $configBot['commands']['long_message_split_limit'];
        $result['text']['command-complexity'] = $configBot['commands']['command_complexity'];
        $result['text']['onconnect'] = str_replace(array('[URL]','[/URL]','[url]','[/url]'),'',$configBot['events']['onconnect']);
        $result['text']['ondisconnect'] = str_replace(array('[URL]','[/URL]','[url]','[/url]'),'',$configBot['events']['ondisconnect']);
        $result['text']['onidle'] = str_replace(array('[URL]','[/URL]','[url]','[/url]'),'',$configBot['events']['onidle']);
        $result['text']['idle-time'] = dateTimeToISO($configBot['events']['idletime']);
        $result['text']['onalone'] = str_replace(array('[URL]','[/URL]','[url]','[/url]'),'',$configBot['events']['onalone']);
        $result['text']['alone-delay'] = dateTimeToISO($configBot['events']['alone_delay']);
        $result['text']['onparty'] = str_replace(array('[URL]','[/URL]','[url]','[/url]'),'',$configBot['events']['onparty']);
        $result['text']['party-delay'] = dateTimeToISO($configBot['events']['party_delay']);
        $result['textView']['min-max-vol'] = "{$configBot['audio']['volume']['min']} - {$configBot['audio']['volume']['max']}";
        $result['minMaxVol'] = array($configBot['audio']['volume']['min'],$configBot['audio']['volume']['max']);
        $result['aliases'] = $alias;
      }
      return $this->output->set_output(printJson(true,null,$result));
    }
  }
 ?>
