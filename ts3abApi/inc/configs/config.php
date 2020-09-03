<?php
 /**
  * Information
  * @Author: xares
  * @Date:   15-05-2020 17:10
  * @Filename: config.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 16:22
  *
  * @Copyright(C) 2020 x-Scripts
  */

  $config = array(
    'lang' => 'en', # language api only en
    'path' => '/home/ts3ab', # location dir app
    'logs' => array(
      'dir' => '/home/ts3ab/logs', # location dir logs app
      'extension' => '.log', # extension file logs
    ),
    'accessIP' => array(
      'enabled' => true, # enabled/disabled access ip
      # access ip
      'IPs' => array(
        '127.0.0.1'
      )
    ),
    'auth' => array(
      'enabled' => true, # enabled/disabled auth key
      # ignored ip key
      'ignoredIPKey' => array(
        '127.0.0.1'
      ),
      'authKey' => '' #key generated in panel
    )
  );
 ?>
