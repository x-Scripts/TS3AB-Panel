<?php
 /**
  * Information
  * @Author: xares
  * @Date:   01-06-2020 15:57
  * @Filename: RemoveCacheDB.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 01-06-2020 17:46
  *
  * @Copyright(C) 2020 x-Scripts
  */
  define('BASEPATH',true);

  require_once('../config/database.php');
  require_once('../config/config.php');

  $debug = array();

  if(empty($db['default']['dsn'])) {
    $db['default']['dsn'] = "mysql:host={$db['default']['hostname']};dbname={$db['default']['database']}";
  }

  # Connected database
  try {
      $pdo = new PDO($db['default']['dsn'], $db['default']['username'], $db['default']['password']);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $debug[] = 'Connected!';
   } catch(PDOException $e) {
      exit('Connection error: ' . $e->getMessage());
   }

   # Time remove sessions
   $timeRem = $config['sess_expiration'];

   # Delete remember sessions
   foreach($pdo->query("SELECT * FROM `xDashSessions`")->fetchAll(PDO::FETCH_ASSOC) as $index) {
     if(($index['timestamp'] + $timeRem) < time()) {
       $pdo->query("DELETE FROM `xDashSessions` WHERE `id` = '{$index['id']}'");
       $debug[] = 'Delete session '.$index['id'];
     }
   }

   # Delete remembers account
   foreach($pdo->query("SELECT * FROM `xDashRemembers`")->fetchAll(PDO::FETCH_ASSOC) as $index) {
     if(($index['time'] + (3600*24*365)) < time()) {
       $pdo->query("DELETE FROM `xDashSessions` WHERE `id` = '{$index['id']}'");
       $debug[] = 'Delete remember '.$index['id'];
     }
   }

   # Counter usage xDash
   $curl = curl_init();
   curl_setopt_array($curl, [
     CURLOPT_RETURNTRANSFER => 1,
     CURLOPT_URL => 'https://x-scripts.pl/counter/index.php',
     CURLOPT_USERAGENT => 'xDashTS3AudioBot-panel',
     CURLOPT_POST => 1,
     CURLOPT_POSTFIELDS => http_build_query(array('domain' => $config['base_url'])),
     CURLOPT_TIMEOUT => 5,
   ]);
   $resp = curl_exec($curl);
   curl_close($curl);
 ?>
