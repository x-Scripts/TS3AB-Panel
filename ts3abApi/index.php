<?php

  require_once('inc/configs/config.php');
  require_once('inc/classes/api.class.php');

  $api = new api($config,$_POST);

  if(!file_exists("inc/lang/{$config['lang']}.php")) {
    exit($api->printJson($api->response(false,"Error api: file lang {$config['lang']}.php not found")));
  }

  require_once("inc/lang/{$config['lang']}.php");
  $api->loadLang($lang);

  $result = $api->checkIP();
  if($result['success']) {
    $result = $api->checkKey();
    if($result['success']) {
      switch($req = $api->input('req')) {
        case 'logs':
          $result = $api->logs();
          break;
        case 'showLogs':
          $result = $api->showLogs($api->input('parameters','filename'));
          break;
        case 'removeLogs':
          $result = $api->removeLogs($api->input('parameters','filename'));
          break;
        case 'serverInfo':
          $result = $api->serverInfo();
          break;
        case 'getRights':
          $result = $api->getRights();
          break;
        case 'saveRights':
          $result = $api->saveRights();
          break;
        case 'startApp':
          $result = $api->startApp();
          break;
        case 'stopApp':
          $result = $api->stopApp();
          break;
        case 'restartApp':
          $result = $api->restartApp();
          break;
        case 'checkIsAppEnabled':
          $result = $api->checkIsAppEnabled();
          break;
        case 'appRequest':
          $result = $api->appRequest($api->input('parameters','request'));
          break;
        default:
          $result = $api->getLangError('badRequest',"\"$req\"");
          break;
      }
    }
  }
  header('Content-Type: application/json');
  echo $api->printJson($result);


 ?>
