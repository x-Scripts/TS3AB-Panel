<?php
 /**
  * Information
  * @Author: xares
  * @Date:   25-04-2020 23:27
  * @Filename: TS3AudioBot.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 29-05-2020 11:10
  *
  * @Copyright(C) 2020 x-Scripts
  */


 class TS3AudioBot {
   private $runtime = array('token' => '', 'ip' => 'localhost', 'port' => 58913, 'timeout' => 5, 'botid' => 0,'config' => array());
   // Global parameters
   public function __construct() {
     $config = getConfigDB();
     $this->runtime['token'] = "Authorization: Basic " . base64_encode($config['apiToken']);
     $this->runtime['ip'] = $config['host'];
     $this->runtime['port'] = $config['port'];
     $this->runtime['timeout'] = $config['timeout'];
     $this->runtime['appApi'] = $config['appApi'];
     $this->runtime['apiKey'] = $config['apiKey'];
     $this->runtime['apiLocal'] = $config['apiLocal'];
   }

   public function setTimeout(int $timeout) {
     if($timeout >= 1) {
       $this->runtime['timeout'] = $timeout;
     }
   }

   private function request($path) {
     if($this->runtime['appApi']) {
       return $this->appRequestExternalServer('/api/bot/use/'.$this->runtime['botid']."/(/".$path);
     }
     $ch = curl_init();
     $requestpath = "http://".$this->runtime['ip'] . ":" . $this->runtime['port'] . "/api/bot/use/" . $this->runtime['botid'] . "/(/" . $path;
     curl_setopt($ch, CURLOPT_URL, $requestpath);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->runtime['timeout']);
     curl_setopt($ch, CURLOPT_TIMEOUT, $this->runtime['timeout']);
     curl_setopt($ch, CURLOPT_HTTPHEADER, array($this->runtime['token']));
     $output = curl_exec($ch);
     if($output === false) {
       $output = json_encode(array('ErrorCode' => 1,'ErrorMessage' => curl_error($ch)));
     }
     curl_close($ch);
     return ($output);
   }

   private function rawRequest($path) {
     if($this->runtime['appApi']) {
       return $this->appRequestExternalServer('/api/'.$path);
     }
     $ch = curl_init();
     $requestpath = "http://".$this->runtime['ip'] . ":" . $this->runtime['port'] . '/api/' . $path;
     curl_setopt($ch, CURLOPT_URL, $requestpath);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->runtime['timeout']);
     curl_setopt($ch, CURLOPT_TIMEOUT, $this->runtime['timeout']);
     curl_setopt($ch, CURLOPT_HTTPHEADER, array($this->runtime['token']));
     $output = curl_exec($ch);
     if($output === false) {
       $output = json_encode(array('ErrorCode' => curl_errno($ch),'ErrorMessage' => curl_error($ch)));
     }
     curl_close($ch);
     return ($output);
   }

   private function appRequestExternalServer($request) {
     $parameters = array(
       'request' => $request,
       'token' => $this->runtime['token'],
       'port' => $this->runtime['port'],
       'timeout' => $this->runtime['timeout']
     );
     $post = array('key' => $this->runtime['apiKey'], 'req' => 'appRequest', 'parameters' => $parameters);
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $this->runtime['apiLocal']);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
     $output = curl_exec($ch);
     if($output === false) {
       $output = json_encode(array('ErrorCode' => curl_errno($ch),'ErrorMessage' => curl_error($ch)));
     }
     curl_close($ch);
     return json_decode($output,true);
   }

   public function setBotID(int $id) {
     $this->runtime['botid'] = $id;
   }

   public function command($request = null) {
     return $this->rawRequest($request);
   }

   public function commandBot($request) {
     return $this->request($request);
   }

   //single bot

   public function play(string $value = null) {
     if(empty($value)) {
       return $this->request('play');
     }
     return $this->request('play/"'.rawurlencode($value).'"');
   }

   public function pause() {
     return $this->request('pause');
   }

   public function stop() {
     return $this->request('stop');
   }

   public function add(string $link) {
     return $this->request('add/'.rawurlencode($link));
   }

   public function volume(int $vol = null) {
     if($vol >= 0 && $vol <= 100) {
       return $this->request('volume/'.$vol);
     }
     return false;
   }

   public function next() {
     return $this->request('next');
   }

   public function prev() {
     return $this->request('previous');
   }

   public function random($enabled = null) {
     if(empty($enabled)) {
       return $this->request('random');
     }
     $enabled = filter_var($enabled,FILTER_VALIDATE_BOOLEAN);
     return $this->request('random/'.($enabled ? 'on' : 'off'));
   }

   public function repeat(string $repeat) {
     if(empty($repeat)) {
       return $this->request('repeat');
     }
     return $this->request('repeat/'.$repeat);
   }

   public function botList() {
     return $this->rawRequest('bot/list');
   }

   public function setName(string $name) {
     return $this->request('bot/name/"'.rawurlencode($name).'"');
   }

   public function setChannelID(int $id) {
     return $this->request('bot/move/'.$id);
   }

   public function botConnect($template) {
     return $this->rawRequest('bot/connect/template/'.rawurlencode($template));
   }

   public function botCreate($ip) {
     return $this->rawRequest('bot/connect/to/'.$ip);
   }

   public function botDisconnect() {
     return $this->request('bot/disconnect');
   }

   public function botSave($template) {
     return $this->request('bot/save/'.rawurlencode($template));
   }

   public function botInfo() {
     return $this->request('bot/info');
   }

   public function botCommander($commander = null) {
     if(empty($commander)) {
       return json_decode($this->request('bot/commander'),true)['Value'];
     }
     return $this->request('bot/commander/'.($commander ? 'on' : 'off'));
   }

   public function seek(int $time) {
     return $this->request('seek/'.$time);
   }

   //settings functions

   public function settingsCreate($template) {
     return $this->rawRequest('settings/create/'.rawurlencode($template));
   }

   public function settingsDelete($template) {
     return $this->rawRequest('settings/delete/'.rawurlencode($template));
   }

   public function getSettingsBot($template,$path) {
     return $this->rawRequest('settings/bot/get/'.rawurlencode($template).'/'.$path);
   }

   public function setSettingsBot($template,$path,$set) {
     return $this->rawRequest('settings/bot/set/'.rawurlencode($template).'/'.$path.'/"'.rawurlencode($set).'"');
   }

   public function getSettings($path) {
     return $this->request('settings/get/'.$path);
   }

   public function setSettings($path,$set) {
     return $this->request('settings/set/'.$path.'/"'.rawurlencode($set).'"');
   }

   public function rightsReload() {
     return $this->rawRequest("rights/reload");
   }
 }
 ?>
