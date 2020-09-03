<?php
 /**
  * Information
  * @Author: xares
  * @Date:   18-05-2020 03:57
  * @Filename: Toml.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 27-05-2020 22:01
  *
  * @Copyright(C) 2020 x-Scripts
  */

  require_once __DIR__ . '/toml-builder/vendor/autoload.php';
  use Yosymfony\Toml\Toml;
  use Yosymfony\Toml\TomlBuilder;

  class TomlEditor extends CI_Controller {
    public $useruid = array();
    private $CI;
    private $rights;
    public $ownerGroup = array();
    public $adminGroups;
    public $adminUsers;

    public function __construct() {
      $this->CI =& get_instance();
      $db = $this->CI->db->query("SELECT * FROM `xDashSettings`")->result_array();
      foreach($db as $id => $index) {
        if($index['id'] == 'apiToken') {
          $this->useruid[] = preg_split('/[:]/',$index['value'])[0];
        }
        if($index['id'] == 'ownerGroup') {
          $this->ownerGroup = json_decode($index['value'],true);
        }
        if($index['id'] == 'adminGroups') {
          $this->adminGroups = json_decode($index['value'],true);
        }
        if($index['id'] == 'ownerUserUID') {
          foreach(json_decode($index['value'],true) as $item) {
            $this->useruid[] = $item;
          }
        }
        if($index['id'] == 'adminUsers') {
          $this->adminUsers = json_decode($index['value'],true);
        }
      }
      $this->rights = Toml::Parse($this->CI->api->getRights()['response'])['rule'];
    }

    public function showRights() {
      return $this->rights;
    }

    public function searchBot($botID) {
      foreach($this->rights as $index) {
        if(isset($index['bot'])) {
          if(in_array($botID,$index['bot'])) {
            return $index;
          }
        }
      }
      return null;
    }

    public function editRightsBot($botID,$rules) {
      foreach($this->rights as $id => $index) {
        if(isset($index['bot'])) {
          if(in_array($botID,$index['bot'])) {
            $this->rights[$id]['rule'][0]['+'] = $rules;
          }
        }
      }
    }

    public function editGroupsBot($botID,$groups) {
      foreach($this->rights as $id => $index) {
        if(isset($index['bot'])) {
          if(in_array($botID,$index['bot'])) {
            $this->rights[$id]['rule'][0]['groupid'] = $groups;
          }
        }
      }
    }

    public function editUsersBot($botID,$users) {
      foreach($this->rights as $id => $index) {
        if(isset($index['bot'])) {
          if(in_array($botID,$index['bot'])) {
            $this->rights[$id]['rule'][0]['useruid'] = $users;
          }
        }
      }
    }

    public function createTable($botID = null,$groups = array(),$rules = array(),$users = array()) {
      if(empty($botID)) {
        return;
      }
      $this->rights[] = array(
        'bot' => array($botID),
        'rule' => array(array(
          'groupid' => $groups,
          'useruid' => array(),
          '+' => $rules
        ))
      );
    }

    public function deleteTable($botID) {
      $rules = $this->rights;
      foreach($rules as $id => $index) {
        if(isset($index['bot'])) {
          if(in_array($botID,$index['bot'])) {
            unset($rules[$id]);
          }
        }
      }
      $this->rights = $rules;
    }

    public function saveFile() {
      $i = 1;
      $tb = new TomlBuilder();
      $tb->addComment('Ustawienia właściciela')
        ->addArrayOfTable('rule')
        ->addValue('groupid',$this->ownerGroup)
        ->addValue('useruid',$this->useruid)
        ->addValue('+',array('*'));
      foreach($this->adminGroups as $id => $item) {
        $tb->addComment('Ustawienia grupy administracji #'.$i." ($id)")
        ->addArrayOfTable('rule')
        ->addValue('groupid',array((int)$id))
        ->addValue('+',$item);
        $i++;
      }
      $i = 1;
      foreach($this->adminUsers as $id => $item) {
        $tb->addComment('Ustawienia użytkownika administracji #'.$i." ($id)")
        ->addArrayOfTable('rule')
        ->addValue('useruid',array($id))
        ->addValue('+',$item);
        $i++;
      }
      foreach($this->rights as $id => $index) {
        if(isset($index['bot'])) {
          $tb->addComment('Ustawienia bota '.$index['bot'][0])
            ->addArrayOfTable('rule')
            ->addValue('bot',$index['bot'])
            ->addArrayOfTable('rule.rule')
              ->addValue('groupid',$index['rule'][0]['groupid'])
              ->addValue('useruid',$index['rule'][0]['useruid'])
              ->addValue('+',$index['rule'][0]['+']);
        }
      }

      return $this->CI->api->saveRights($tb->getTomlString());
      
		}
  }
 ?>
