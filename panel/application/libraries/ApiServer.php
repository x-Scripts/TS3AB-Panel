<?php
 /**
  * Information
  * @Author: xares
  * @Date:   27-04-2020 00:39
  * @Filename: ApiServer.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 28-05-2020 16:11
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class ApiServer {

    /*********************/
    /*                   */
    /*     Variables     */
    /*                   */
    /*********************/

    private $type;
    private $local;
    private $config;
    private $check = array('error' => '','status' => false);
    private $key;
    private $CI;


    /*********************/
    /*                   */
    /*       Public      */
    /*     Functions     */
    /*                   */
    /*********************/

    /**
     * function __construct
     *
     */
    public function __construct() {
      $config = getConfigDB();
      $this->CI =& get_instance();
      $this->type = $config['apiType'];
      $this->local = $config['apiLocal'];
      $this->key = $config['apiKey'];
      $config = $this->CI->config->item('ts3ab');

      if(@is_dir($config['path'])) {
        if(@is_dir($config['logs']['path'])) {
          $this->check['status'] = true;
          $this->config = $config;
        } else {
          $this->check['error'] = "Error: dir {$config['logs']['path']} not found";
        }
      } else {
        $this->check['error'] = "Error: dir {$config['path']} not found";
      }
    }

    /**
     * function startApp
     *
     * @return mixed
     */
    public function startApp() {
      if($this->type == 'localhost') {
        if(!$this->check['status']) { return $this->error(); }
        $config = $this->config;

        if(preg_replace('/\D/', '', shell_exec("sudo screen -S TS3AudioBot -Q select . ; echo $?")) == 0) {
          return $this->response(false,'Error: app is enabled');
        }

        shell_exec("cd {$config['path']} && sudo screen -dmS TS3AudioBot dotnet TS3AudioBot.dll");

        return $this->response(true,true);
      } elseif($this->type == 'externalhost') {
        return $this->request('startApp');
      }
      return $this->response(false,'Error: bad api method');
    }

    /**
     * function stopApp
     *
     * @return mixed
     */
    public function stopApp() {
      if($this->type == 'localhost') {
        if(!$this->check['status']) { return $this->error(); }
        $config = $this->config;

        if(preg_replace('/\D/', '', shell_exec("sudo screen -S TS3AudioBot -Q select . ; echo $?")) != 0) {
          return $this->response(false,'Error: app is disabled');
        }

        shell_exec('sudo screen -S TS3AudioBot -p 0 -X stuff ^C');
        if(preg_replace('/\D/', '', shell_exec("sudo screen -S TS3AudioBot -Q select . ; echo $?")) == 0) {
          shell_exec('sudo screen -S TS3AudioBot -X quit');
        }

        return $this->response(true,true);
      } elseif($this->type == 'externalhost') {
        return $this->request('stopApp');
      }
      return $this->response(false,'Error: bad api method');
    }

    /**
     * function restartApp
     *
     * @return mixed
     */
    public function restartApp() {
      if($this->type == 'localhost') {
        if(!$this->check['status']) { return $this->error(); }
        $config = $this->config;

        if(preg_replace('/\D/', '', shell_exec("sudo screen -S TS3AudioBot -Q select . ; echo $?")) == 0) {
          shell_exec('sudo screen -S TS3AudioBot -p 0 -X stuff ^C');
          if(preg_replace('/\D/', '', shell_exec("sudo screen -S TS3AudioBot -Q select . ; echo $?")) == 0) {
            shell_exec('sudo screen -S TS3AudioBot -X quit');
          }
        }

        shell_exec("cd {$config['path']} && sudo screen -dmS TS3AudioBot dotnet TS3AudioBot.dll");

        return $this->response(true,true);
      } elseif($this->type == 'externalhost') {
        return $this->request('restartApp');
      }
      return $this->response(false,'Error: bad api method');
    }

    /**
     * function checkIsAppEnabled
     *
     * @return mixed
     */
    public function checkIsAppEnabled() {
      if($this->type == 'localhost') {
        if(!$this->check['status']) { return $this->error(); }

        if(preg_replace('/\D/', '', shell_exec("sudo screen -S TS3AudioBot -Q select . ; echo $?")) == 0) {
          return $this->response(true,true);
        }
        return $this->response(true,false);
      } elseif($this->type == 'externalhost') {
        return $this->request('checkIsAppEnabled');
      }
      return $this->response(false,'Error: bad api method');
    }

    /**
     * function serverInfo
     *
     * @return mixed
     */
    public function serverInfo() {
      if($this->type == 'localhost') {
        if(!$this->check['status']) { return $this->error(); }

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
      } elseif($this->type == 'externalhost') {
        return $this->request('serverInfo');
      }
      return $this->response(false,'Error: bad api method');
    }

    /**
     * function getRights
     *
     * @return string
     */
    public function getRights() {
      if($this->type == 'localhost') {
        if(!$this->check['status']) { return $this->error(); }
        $config = $this->config;
        return $this->openFile($config['path'].'/rights.toml');
      } elseif($this->type == 'externalhost') {
        return $this->request('getRights');
      }
      return $this->response(false,'Error: bad api method');
    }

    /**
     * Save file rights application
     *
     * @param  string $value
     * @return mixed
     */
    public function saveRights($value) {
      if($this->type == 'localhost') {
        if(!$this->check['status']) { return $this->error(); }
        $config = $this->config;
        file_put_contents($config['path'].'/rights.toml',$value);
        return $this->response(true,true);
      } elseif($this->type == 'externalhost') {
        return $this->request('saveRights',array('value' => $value));
      }
      return $this->response(false,'Error: bad api method');
    }

    /**
     * function logs
     *
     * @return mixed
     */
    public function logs() {
      if($this->type == 'localhost') {
        if(!$this->check['status']) { return $this->error(); }
        $config = $this->config['logs'];
        return $this->scanDir($config['path'],false,$config['extension']);
      } elseif($this->type == 'externalhost') {
        return $this->request('logs');
      }
      return $this->response(false,'Error: bad api method');
    }

    /**
     * Show logs on day
     *
     * @param  string $date
     * @return mixed
     */
    public function showLogs($date = null) {
      if($this->type == 'localhost') {
        if(!$this->check['status']) { return $this->error(); }
        $config = $this->config['logs'];
        return $this->openFile($config['path'].'/'.$date.$config['extension']);
      } elseif($this->type == 'externalhost') {
        return $this->request('showLogs',array('filename' => $date));
      }
      return $this->response(false,'Error: bad api method');
    }

    /**
     * Remove logs
     *
     * @param  string|null $date
     * @return mixed
     */
    public function removeLogs($date = null) {
      if($this->type == 'localhost') {
        if(!$this->check['status']) { return $this->error(); }
        $config = $this->config['logs'];

        $pathFile = $config['path'].'/'.$date.$config['extension'];

        if(!file_exists($pathFile)) {
          return $this->response(false,"Error: file $pathFile not found");
        }

        if(!unlink($pathFile)) {
          return $this->response(false,'Error: delete '.strtolower(end(preg_split('/[: ]/',error_get_last()['message'],-1,PREG_SPLIT_NO_EMPTY))));
        }
        return $this->response(true,true);
      } elseif($this->type == 'externalhost') {
        return $this->request('removeLogs',array('filename' => $date));
      }
      return $this->response(false,'Error: bad api method');
    }


    /*********************/
    /*                   */
    /*      Private      */
    /*     Functions     */
    /*                   */
    /*********************/

    /**
     * function scanDir
     *
     * @param  string  $dir
     * @param  boolean $short
     * @param  string $shortFile
     * @return mixed
     */

    private function scanDir($dir, $short = true, $shortFile = null) {
      if($short == false && empty($shortFile)) {
        return $this->response(false,'Error: empty file extension');
      }

      if(!is_dir($dir)) {
        return $this->response(false,"Error: directory $dir not found");
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
     * function openFile
     *
     * @param  string $file
     * @return mixed
     */
    private function openFile($file) {

      if(file_exists($file)) {
        return $this->response(true,file_get_contents($file));
      }
      return $this->response(false,"Error: file $file not found");

    }

    /**
     * function request
     *
     * @param  string $req
     * @param  array $parameters
     * @return mixed
     */
    private function request($req,$parameters = null) {
      $post = array('key' => $this->key, 'req' => $req, 'parameters' => $parameters);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->local);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
      $result = curl_exec($ch);
      if($result === false) {
        return $this->response(false,curl_error($ch));
      }
      $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      if($code >= 200 && $code <= 206) {
        return json_decode($result,true);
      }
      return $this->response(false,'Error code: '.$code);

    }

    /**
     * function response
     *
     * @param  boolean $success
     * @param  string  $response
     * @return mixed
     */
    private function response($success, $response) {
      return array('success' => $success, 'response' => $response);
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

    private function error() {
      return $this->response(false,$this->check['error']);
    }

  }
 ?>
