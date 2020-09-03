<?php
 /**
  * Information
  * @Author: xares
  * @Date:   25-04-2020 23:20
  * @Filename: dashboard.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 29-05-2020 13:04
  *
  * @Copyright(C) 2020 x-Scripts
  */

  $config = array(
    'ts3ab' => array(
      'path' => '/home/ts3ab',
      'logs' => array(
        'path' => '/home/ts3ab/logs',
        'extension' => '.log'
      )
    ),
    'allRights' => array(
      'cmd' => array(
        '*' => false,
        'whisper' => array(
          '*' => false,
          'all' => false,
          'group' => false,
          'list' => false,
          'off' => false,
          'subscription' => false
        ),
        'xecute' => true,
        'list' => array(
          '*' => false,
          'import' => true,
          'insert' => false,
          'item' => array(
            '*' => false,
            'get' => false,
            'move' => false,
            'delete' => false,
            'name' => false
          ),
          'list' => true,
          'merge' => false,
          'name' => true,
          'play' => true,
          'queue' => false,
          'show' => true,
          'add' => true,
          'create' => true,
          'delete' => true,
          'from' => false
        ),
        'next' => true,
        'param' => true,
        'pm' => array(
          '*' => false,
          'channel' => false,
          'server' => false,
          'user' => false
        ),
        'pause' => true,
        'play' => true,
        'plugin' => array(
          '*' => false,
          'list' => false,
          'unload' => false,
          'load' => false
        ),
        'previous' => true,
        'print' => false,
        'quiz' => array(
          '*' => false,
          'on' => false,
          'off' => false
        ),
        'random' => array(
          '*' => true,
          'on' => false,
          'off' => false,
          'seed' => false
        ),
        'repeat' => array(
          '*' => true,
          'off' => false,
          'one' => false,
          'all' => false
        ),
        'rights' => array(
          '*' => false,
          'reload' => false,
          'can' => false
        ),
        'rng' => false,
        'seek' => true,
        'search' => array(
          '*' => false,
          'add' => false,
          'from' => true,
          'get' => false,
          'play' => true,
        ),
        'server' => array(
          '*' => false,
          'tree' => false,
        ),
        'settings' => array(
          '*' => false,
          'copy' => false,
          'create' => false,
          'delete' => false,
          'get' => false,
          'set' => false,
          'bot' => array(
            '*' => false,
            'get' => false,
            'set' => false,
            'reload' => false
          ),
          'global' => array(
            '*' => false,
            'get' => false,
            'set' => false
          ),
          'help' => false
        ),
        'song' => true,
        'stop' => true,
        'subscribe' => array(
          '*' => false,
          'tempchannel' => false,
          'channel' => false
        ),
        'system' => array(
          '*' => false,
          'info' => false,
          'quit' => false
        ),
        'take' => false,
        'unsubscribe' => array(
          '*' => false,
          'channel' => false,
          'temporary' => false
        ),
        'version' => false,
        'volume' => true,
        'add' => true,
        'alias' => array(
          '*' => false,
          'add' => false,
          'remove' => false,
          'list' => false,
          'show' => false
        ),
        'api' => array(
          '*' => false,
          'token' => false
        ),
        'bot' => array(
          '*' => false,
          'avatar' => array(
            '*' => false,
            'set' => false,
            'clear' => false,
          ),
          'badges' => false,
          'description.set' => false,
          'diagnose' => false,
          'disconnect' => false,
          'commander' => array(
            '*' => false,
            'on' => false,
            'off' => false,
          ),
          'come' => false,
          'connect' => array(
            '*' => false,
            'template' => false,
            'to' => false
          ),
          'info' => array(
            'client' => false,
          ),
          'list' => false,
          'move' => false,
          'name' => false,
          'save' => false,
          'setup' => false,
          'template' => false,
          'use' => false
        ),
        'clear' => true,
        'command' => array(
          '*' => false,
          'parse' => false,
          'tree' => false
        ),
        'data.song.cover.get' => false,
        'eval' => false,
        'from' => false,
        'get' => false,
        'getmy' => array(
          '*' => false,
          'id' => false,
          'uid' => false,
          'name' => false,
          'dbid' => false,
          'channel' => false,
          'all' => false
        ),
        'getuser' => array(
          '*' => false,
          'uid' => false,
          'name' => false,
          'dbid' => false,
          'channel' => false,
          'all' => false,
          'id' => false
        ),
        'help' => array(
          '*' => false,
          'all' => false,
          'command' => false,
          'play' => false
        ),
        'history' => array(
          '*' => false,
          'add' => false,
          'clean' => array(
            '*' => false,
            'removedefective' => false,
            'upgrade' => false
          ),
          'delete' => false,
          'from' => false,
          'id' => false,
          'last' => false,
          'play' => false,
          'rename' => false,
          'till' => false,
          'title' => false
        ),
        'if' => false,
        'json' => array(
          '*' => false,
          'api' => false,
          'merge' => false
        ),
        'kickme' => array(
          'far' => false
        )
      )
    ),
  );



 ?>
