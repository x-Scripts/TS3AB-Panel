<?php
 /**
  * Information
  * @Author: xares
  * @Date:   15-05-2020 17:07
  * @Filename: api.class.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 29-05-2020 12:51
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class api {

    /*****************************************/
    /*                                       */
    /*          Private Variables            */
    /*                                       */
    /*****************************************/

    private $runtime = array('config' => array(), 'lang' => array(), 'check' => false, 'checkErrorLog' => '');
    private $post;


    /*****************************************/
    /*                                       */
    /*          Global functions             */
    /*                                       */
    /*****************************************/

    /**
     * __construct class
     *
     * @param array $config
     * @param array $post
     */
    public function __construct($config,$post) {
      if(!empty($config['auth']['authKey'])) {
        if(is_dir($config['path'])) {
          if(is_dir($config['logs']['dir'])) {
            $this->runtime['config'] = $config;
            $this->post = $post;
            $this->runtime['check'] = true;
          } else {
            $this->runtime['checkErrorLog'] = ['dirNotFound',$config['logs']['dir']];
          }
        } else {
          $this->runtime['checkErrorLog'] = ['dirNotFound',$config['path']];
        }
      } else {
        $this->runtime['checkErrorLog'] = ['emptyKey'];
      }
    }

    /**
     * __call
     *
     * @param  string $a
     * @param  string $b
     * @return mixed
     */
    public function __call($a,$b) {
      return $this->getLangError('badFunction',$a);
    }

    /**
     * Check ip server in access
     *
     * @return mixed
     */
    public function checkIP() {
      if(!$this->runtime['check']) { return $this->error(); }

      $config = $this->runtime['config']['accessIP'];

      if(!$config['enabled']) {
        return $this->response(true,true);
      }

      if(in_array($this->getClientIP(),$config['IPs'])) {
        return $this->response(true,true);
      }
      return $this->getLangError('accessDenied');
    }

    /**
     * Check api key server
     *
     * @return mixed
     */
    public function checkKey() {
      if(!$this->runtime['check']) { return $this->error(); }

      $config = $this->runtime['config']['auth'];

      if(!$config['enabled'] || in_array($this->getClientIP(),$config['ignoredIPKey'])) {
        return $this->response(true,true);
      }

      if($this->input('key') == $config['authKey']) {
        return $this->response(true,true);
      }

      return $this->getLangError('invalidKey');
    }

    /**
     * Show file logs application
     *
     * @return mixed
     */
    public function logs() {
      if(!$this->runtime['check']) { return $this->error(); }

      $config = $this->runtime['config']['logs'];

      $files = $this->scanDir($config['dir'],false,$config['extension']);

      if(!$files['success']) {
        return $files;
      }

      $logs = array();

      foreach($files['response'] as $index) {
        $logs[strtotime($index)] = $index;
      }
      krsort($logs);
      return $this->response(true,$logs);
    }

    /**
     * Show logs application
     *
     * @param  string $filename
     * @return string
     */
    public function showLogs($filename) {
      if(!$this->runtime['check']) { return $this->error(); }

      $config = $this->runtime['config']['logs'];

      $logs = $this->openFile($config['dir'].'/'.$filename.$config['extension']);

      return $logs;
    }

    /**
     * Remove logs application
     *
     * @param  string $filename
     * @return mixed
     */
    public function removeLogs($filename) {
      if(!$this->runtime['check']) { return $this->error(); }

      $config = $this->runtime['config']['logs'];

      $pathFile = $config['dir'].'/'.$filename.$config['extension'];

      if(!file_exists($pathFile)) {
        return $this->getLangError('fileNotFound',$filename);
      }

      if(!unlink($pathFile)) {
        return $this->response(false,'Error api: delete '.strtolower(end(preg_split('/[: ]/',error_get_last()['message'],-1,PREG_SPLIT_NO_EMPTY))));
      }
      return $this->response(true,true);
    }

    /**
     * Show result usage cpu, memory, disk space
     *
     * @return mixed
     */
    public function serverInfo() {
      if(!$this->runtime['check']) { return $this->error(); }

      $cpu = json_decode(shell_exec('mpstat -u 1 2 -o JSON'),true);
      if($cpu == null) {
        $usage = round(shell_exec("grep 'cpu ' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'"),1);
        $cpu = array('idle' => 100-$usage,'usage' => $usage);
      } else {
        $usage = 0;
        foreach($cpu['sysstat']["hosts"] as $index) {
          if(round(100-$index["statistics"][0]["cpu-load"][0]["idle"],2) > $usage) {
            $cpu = $index;
            $usage = round(100-$index["statistics"][0]["cpu-load"][0]["idle"],2);
          }
        }
        $cpu = $cpu["statistics"][0]["cpu-load"][0];
        $cpu['usage'] = $usage;
      }

      $result = array(
        'memory' => $this->serverMemory(),
        'cpu' => $cpu,
        'disk' => array(
          'total' => disk_total_space('/'),
          'free' => disk_free_space('/'),
          'usage' => (disk_total_space('/')-disk_free_space('/'))
        )
      );
      return $this->response(true,$result);
    }


    /**
     * Open file rights application
     *
     * @return string
     */
    public function getRights() {
      if(!$this->runtime['check']) { return $this->error(); }

      $config = $this->runtime['config'];

      $file = $this->openFile($config['path'].'/rights.toml');

      return $file;
    }

    /**
     * Save file rights application
     *
     * @return mixed
     */
    public function saveRights() {
      if(!$this->runtime['check']) { return $this->error(); }

      $config = $this->runtime['config'];

      file_put_contents($config['path'].'/rights.toml',$this->input('parameters','value'));

      return $this->response(true,true);
    }

    /**
     * Start application
     *
     * @return mixed
     */
    public function startApp() {
      if(!$this->runtime['check']) { return $this->error(); }

      $config = $this->runtime['config'];

      if(preg_replace('/\D/', '', shell_exec("sudo screen -S TS3AudioBot -Q select . ; echo $?")) == 0) {
        return $this->getLangError('appIsEnabled');
      }

      shell_exec("cd {$config['path']} && sudo screen -dmS TS3AudioBot dotnet TS3AudioBot.dll");

      return $this->response(true,true);
    }

    /**
     * Stop application
     *
     * @return mixed
     */
    public function stopApp() {
      if(!$this->runtime['check']) { return $this->error(); }

      $config = $this->runtime['config'];

      if(preg_replace('/\D/', '', shell_exec("sudo screen -S TS3AudioBot -Q select . ; echo $?")) != 0) {
        return $this->getLangError('appIsDisabled');
      }

      shell_exec('sudo screen -S TS3AudioBot -p 0 -X stuff ^C');
      if(preg_replace('/\D/', '', shell_exec("sudo screen -S TS3AudioBot -Q select . ; echo $?")) == 0) {
        shell_exec('sudo screen -S TS3AudioBot -X quit');
      }

      return $this->response(true,true);
    }

    /**
     * Check app is enabled
     *
     * @return mixed
     */
    public function checkIsAppEnabled() {
      if(!$this->runtime['check']) { return $this->error(); }

      if(preg_replace('/\D/', '', shell_exec("sudo screen -S TS3AudioBot -Q select . ; echo $?")) == 0) {
        return $this->response(true,true);
      }
      return $this->response(true,false);
    }

    /**
     * Restart application
     *
     * @return mixed
     */
    public function restartApp() {
      if(!$this->runtime['check']) { return $this->error(); }

      $config = $this->runtime['config'];

      if(preg_replace('/\D/', '', shell_exec("sudo screen -S TS3AudioBot -Q select . ; echo $?")) == 0) {
        shell_exec('sudo screen -S TS3AudioBot -p 0 -X stuff ^C');
        if(preg_replace('/\D/', '', shell_exec("sudo screen -S TS3AudioBot -Q select . ; echo $?")) == 0) {
          shell_exec('sudo screen -S TS3AudioBot -X quit');
        }
      }

      shell_exec("cd {$config['path']} && sudo screen -dmS TS3AudioBot dotnet TS3AudioBot.dll");

      return $this->response(true,true);
    }

    public function appRequest($request) {
      $ch = curl_init();
      $requestpath = "http://127.0.0.1:" . $this->input('parameters','port') . $request;
      curl_setopt($ch, CURLOPT_URL, $requestpath);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->input('parameters','timeout'));
      curl_setopt($ch, CURLOPT_TIMEOUT, $this->input('parameters','timeout'));
      curl_setopt($ch, CURLOPT_HTTPHEADER, array($this->input('parameters','token')));
      $output = curl_exec($ch);
      if($output === false) {
        $output = json_encode(array('ErrorCode' => 1,'ErrorMessage' => curl_error($ch)));
      }
      curl_close($ch);
      return ($output);
    }

    /*****************************************/
    /*                                       */
    /*          Language Functions           */
    /*                                       */
    /*****************************************/

    /**
     * Loading file language
     *
     * @param  array $lang
     */
    public function loadLang($lang) {
      $this->runtime['lang'] = $lang;
    }

    /**
     * Show message language
     *
     * @param  string $get
     * @param  array $replace
     * @return string
     */
    public function getLangError($get,...$replace) {
      $lang = $this->runtime['lang']['error'];
      if(empty($lang['resError'][$get])) {
        return $this->response(false,$this->replace($lang['showErrorMessage'],array('{message}' => $lang['resError']['notFoundLang'],'{0}' => "\"$get\"")));
      }
      $result = array();
      $result['{message}'] = $lang['resError'][$get];
      foreach($replace as $id => $rep) {
        $result["{{$id}}"] = $rep;
      }
      return $this->response(false,$this->replace($lang['showErrorMessage'],$result));
    }


    /*****************************************/
    /*                                       */
    /*          Helpers Functions            */
    /*                                       */
    /*****************************************/

    /**
     * Show result input variables
     *
     * @param  array $input
     * @return string|int|null|array
     */
    public function input(...$input) {
      $post = $this->post;
      foreach($input as $in) {
        if(!isset($post[$in])) {
          return null;
        }
        $post = $post[$in];
      }
      return $post;
    }

    /**
     * Show result json
     *
     * @param  array $array
     * @return string
     */
    public function printJson($array) {
      return json_encode($array,JSON_PRETTY_PRINT);
    }

    /**
     * Scan dir to show files
     *
     * @param  string  $dir
     * @param  boolean $short
     * @param  string|null  $shortFile
     * @return mixed
     */
    public function scanDir($dir, $short = true, $shortFile = null) {
      if($short == false && empty($shortFile)) {
        return $this->getLangError('fileExtension');
      }

      if(!is_dir($dir)) {
        return $this->getLangError('dirNotFound',$dir);
      }

      $files = array();
      foreach(scandir($dir) as $file) {
        if(!in_array($file,['.','..'])) {
          if($short) {
            $files[] = $file;
          } else {
            $files[] = preg_split("/[{$shortFile}]/",$file)[0];
          }
        }
      }
      return $this->response(true,$files);
    }

    /**
     * Open file result
     *
     * @param  string $pathFile
     * @return mixed
     */
    public function openFile($pathFile) {
      if(!file_exists($pathFile)) {
        return $this->getLangError('fileNotFound',$pathFile);
      }

      return $this->response(true,file_get_contents($pathFile));
    }

    /**
     * Show message function true or false
     *
     * @param  boolean $success
     * @param  string $response
     * @return string
     */
    public function response($success, $response) {
      return array('success' => $success, 'response' => $response);
    }

    /**
     * Replace message variables
     *
     * @param  string $mess
     * @param  array $rep
     * @return string
     */
    private function replace($mess,$rep = array()) {
      foreach($rep as $a => $b) {
        $mess = str_replace($a,$b,$mess);
      }
      return $mess;
    }

    /**
     * Show error api
     *
     * @return mixed
     */
    private function error() {
      $checkError = $this->runtime['checkErrorLog'];
      if(!empty($checkError)) {
        if(empty($checkError[1])) {
          return $this->getLangError($checkError[0]);
        }
        return $this->getLangError($checkError[0],$checkError[1]);
      }
      return $this->getLangError('unknown');
    }

    /**
     * Show connect client ip
     *
     * @return string
     */
    private function getClientIP() {
      if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
      }
      $client  = @$_SERVER['HTTP_CLIENT_IP'];
      $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
      $remote  = @$_SERVER['REMOTE_ADDR'];

      if(filter_var($client, FILTER_VALIDATE_IP)) {
          return $client;
      }

      if(filter_var($forward, FILTER_VALIDATE_IP)) {
          return $forward;
      }

      return $remote;
    }

    /**
     * Show usage memory server
     *
     * @return mixed
     */
    private function serverMemory() {
      $memory = shell_exec('free -b');
      $memory = preg_split('/['.PHP_EOL.']/',$memory,-1,PREG_SPLIT_NO_EMPTY);
      $var = preg_split('/[ ]/',$memory[0],-1,PREG_SPLIT_NO_EMPTY);
      unset($memory[0]);
      $result = array();
      foreach($memory as $index) {
        preg_match('|(.*):(.*)|',$index,$match);
        $result[$match[1]] = array();
        foreach(preg_split('/[ ]/',$match[2],-1,PREG_SPLIT_NO_EMPTY) as $id => $item) {
          $result[$match[1]][$var[$id]] = (int)$item;
        }
      }
      return $result;
    }
  }
 ?>
