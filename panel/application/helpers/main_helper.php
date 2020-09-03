<?php
 /**
  * Information
  * @Author: xares
  * @Date:   10-05-2020 21:15
  * @Filename: main_helper.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 01-06-2020 14:39
  *
  * @Copyright(C) 2020 x-Scripts
  */

 function censorIPAddress($ip) {
   if(filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)) {
     $split = preg_split('/[.]/',$ip);
     if(count($split) >= 2) {
       return "{$split[0]}.{$split[1]}.***.***";
     }
   } else if(filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)) {
     $split = preg_split('/[:]/',$ip);
     if(count($split) >= 2) {
       return "{$split[0]}:{$split[1]}:***:***";
     }
   }
   return $ip;

 }

 function request($request, $required = null, $mes = null, $mesAll = null) {
   if(empty($required)) {
     return array('success' => false, 'response' => 'Error: empty required parameter');
   }
   if(!is_array($required)) {
     $required = array($required);
   }
   if(empty($request)) {
     return array('success' => false, 'response' => 'Podaj parametry');
   }
   if(is_array($mes)) {
     foreach($required as $id => $index) {
       if(empty($request[$index])) {
         return array('success' => false, 'response' => isset($mes[$id]) ? $mes[$id] : $mesAll);
       }
     }
     return array('success' => true, 'response' => $request);
   }
   $empty = array();
   foreach($required as $index) {
     if(empty($request[$index])) {
       $empty[] = $index;
     }
   }
   if(empty($empty)) {
     return array('success' => true, 'response' => $request);
   }
   return array('success' => false, 'response' => ($mes != null ? $mes : 'Brakuje nasępujących parametrów: '.implode(',',$empty)));
 }

 function randomString($len = 5,$ignore = array()) {
   if(!is_array($ignore)) {
     $ignore = array($ignore);
   }

   $string = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
   $stringLen = strlen($string)-1;
   while(true) {
     $result = '';
     for ($i=1; $i <= $len; $i++) {
       $result .= $string[rand(0,$stringLen)];
     }
     if(!in_array($result,$ignore)) {
       return $result;
     }
   }
 }

 function value($value) {
   if(empty($value)) {
     return null;
   }
   return $value;
 }

 function printJson($success,$message,$value = false) {
   $array = array(
     'success' => $success,
     'message' => $message,
     'value' => $value
   );
   return json_encode($array,JSON_PRETTY_PRINT);
 }

 function permission($parameter) {
   //$this->db->query();
   $CI =& get_instance();
   $query = $CI->db->query("SELECT * FROM `xDashPermissionsUsers` WHERE `username` = '".$CI->session->userdata('login')."'");
   if($query->num_rows() >= 1) {
     $query = $query->result_array()[0];
     if(is_array($parameter)) {
       $result = array();
       foreach($parameter as $index) {
         if(array_key_exists($index,$query)) {
           $result[$index] = ($index == 'userRights' ? $query[$index] : (int)$query[$index]);
         } else {
           $result[$index] = null;
         }
       }
       return $result;
     }
     if(array_key_exists($parameter,$query)) {
       return ($parameter == 'userRights' ? $query[$parameter] : (int)$query[$parameter]);
     }
     return ($parameter == 'userRights') ? null : 0;
   }
   $describe = array_flip(array_column($CI->db->query('DESCRIBE `xDashPermissionsUsers`')->result_array(),'Field'));
   if(is_array($parameter)) {
     $result = array();
     foreach($parameter as $index) {
       if(array_key_exists($index,$describe)) {
         $result[$index] = ($index == 'userRights' ? null : (int)0);
       } else {
         $result[$index] = null;
       }
     }
     return $result;
   }
   if(array_key_exists($parameter,$describe)) {
     return ($parameter == 'userRights') ? null : 0;
   }
   return null;


 }

 function build_versions() {
   $versions = preg_split('/['.PHP_EOL.']/',file_get_contents('https://raw.githubusercontent.com/ReSpeak/tsdeclarations/master/Versions.csv'));
   unset($versions[0]);
   $result = array();
   $result[] = '<option data-build="" data-platform="" data-sign="" selected>Domyślna</option>';
   foreach($versions as $id => $version) {
       $v = preg_split('/[,]/',$version);
       $result[] = '<option data-build="'.$v[0].'" data-platform="'.$v[1].'" data-sign="'.$v[2].'">'.$v[0].' : '.$v[1].'</option>';
   }
   return implode(PHP_EOL,$result);
 }

 function generateAllRightsHtml($config, $cmd = '', $classes = '', $otherClass = '', $checked = true) {
   $html = '<ul tabindex="0" style="list-style-type:none; padding-left: 10px;">';
   foreach($config as $index => $item) {
     if(is_array($item)) {
       if(isset($item['*'])) {
         $html .= '<li><div class="i-checks">
           <input type="checkbox" class="checkbox-template check-sub'.$classes.'" data-name="'.$cmd.$index.'.*" data-sub="'.str_replace('.','-',$cmd.$index).'" '.($item['*'] && $checked ? 'checked ' : '').'>
           <label for="checkboxCustom1">'.$cmd.$index.'.*</label>
         </div></li>';
       }
       $html .= generateAllRightsHtml($item, $cmd.$index.'.',str_replace('.','-',$classes.' '.$cmd.$index), $otherClass, $checked);
     } else {
       if($index != '*') {
         $html .= '<li><div class="i-checks">
           <input type="checkbox" class="checkbox-template check'.$classes.' '.$otherClass.'" data-name="'.$cmd.$index.'" '.($item && $checked ? 'checked ' : '').'>
           <label for="checkboxCustom1">'.$cmd.$index.'</label>
         </div></li>';
       }
     }
   }
   $html .= '</ul>';
   return $html;
 }
 function generateUserRightsHtml($config,$checked = true) {
   $ci =& get_instance();
   $checkbox = '<li><div class="i-checks"><input type="checkbox" class="checkbox-template check" data-name="{1}"{2}><label for="checkboxCustom1">{1}</label></div></li>';
   $html = '<ul tabindex="0" style="list-style-type:none; padding-left: 10px;">';
   foreach($config as $index => $item) {
     $split = preg_split('/[.]/',$item);
     $cfg = $ci->config->item('allRights');
     for ($i=0; $i < count($split); $i++) {
       $cfg = $cfg[$split[$i]];
     }
     $html .= str_replace(array('{1}','{2}'),array($item,($cfg && $checked ? ' checked' : '')),$checkbox);
   }
   $html .= '</ul>';
   return $html;
 }

 function search_badges($badgesuid) {
   $versions = preg_split('/['.PHP_EOL.']/',str_replace(array(',',"| ",'"'),array('|',', ',''),file_get_contents('https://raw.githubusercontent.com/ReSpeak/tsdeclarations/master/Badges.csv')));
   $result = array();
   foreach($versions as $id => $version) {
     $v = preg_split('/[|]/',$version);
     if(isset($v[3])) {
       if(in_array($v[0],$badgesuid)) {
         $result[] = array(
           'name' => $v[0],
           'title' => $v[2],
           'filename' => $v[3]
         );
       }
     }
   }
   return $result;
 }

 function dateTimeToISO($datetime) {
   $hours = 0;
   $minutes = 0;
   $seconds = 0;
   $datetime = preg_split('/[:]/',$datetime);

   if($datetime[2] != '00') {
     if($datetime[2] == '60') {
       $minutes += 1;
     } else {
       $seconds += (int)$datetime[2];
     }
   }

   if($datetime[1] != '00') {
     if($datetime[1] == '60') {
       $hours += 1;
     } else {
       $minutes += (int)$datetime[1];
     }
   }

   if($datetime[0] != '00') {
     $hours += (int)$datetime[0];
   }

   $result = '';
   if($hours != 0) {
     $result .= "{$hours}h";
   }
   if($minutes != 0) {
     $result .= "{$minutes}m";
   }
   if($seconds != 0) {
     $result .= "{$seconds}s";
   }
   return $result;
 }

 function build_badges() {
   $versions = preg_split('/['.PHP_EOL.']/',str_replace(array(',',"| ",'"'),array('|',', ',''),file_get_contents('https://raw.githubusercontent.com/ReSpeak/tsdeclarations/master/Badges.csv')));
   unset($versions[0]);
   $result = array();
   $result[] = '<option data-filename="" value="" selected>Wybierz odznaki</option>';
   foreach($versions as $id => $version) {
      $v = preg_split('/[|]/',$version);
      if(isset($v[3])) {
        $result[] = '<option style="background: #2d3035;" class="'.$v[0].'" value="'.$v[0].'" data-name="'.$v[2].'" data-filename="'.$v[3].'">'.$v[1].'</option>';
      }
   }
   return implode(PHP_EOL,$result);
 }

 function ip_address()
 {
   if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
     return $_SERVER['HTTP_CF_CONNECTING_IP'];
   } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
     return $_SERVER['HTTP_CLIENT_IP'];
   } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
     return $_SERVER['HTTP_X_FORWARDED'];
   } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
     return $_SERVER['HTTP_FORWARDED_FOR'];
   } else if (isset($_SERVER['HTTP_FORWARDED'])) {
     return $_SERVER['HTTP_FORWARDED'];
   } else if (isset($_SERVER['REMOTE_ADDR'])) {
     return $_SERVER['REMOTE_ADDR'];
   } else {
     return 'UNKNOWN';
   }
 }

 function getConfigDB() {
   $result = array();
   $CI =& get_instance();
   foreach($CI->db->query("SELECT * FROM `xDashSettings`")->result_array() as $index) {
     if($index['type'] == "STRING") {
       $result[$index['id']] = (string)$index['value'];
     } elseif($index['type'] == "INT") {
       $result[$index['id']] = (int)$index['value'];
     } elseif($index['type'] == "JSON") {
       $result[$index['id']] = json_decode($index['value'],true);
     } else {
       $result[$index['id']] = $index['value'];
     }
   }
   return $result;

 }
 function readableBytes($bytes) {
    $i = floor(log($bytes) / log(1024));
    $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

    return sprintf('%.02F', $bytes / pow(1024, $i)) * 1 . ' ' . $sizes[$i];
  }

  function bytesToMB($bytes, $decimal_places = 2 ){
    return round($bytes / 1048576, $decimal_places);
  }

  function suma($array) {
    $suma = 0;
    foreach($array as $index) {
      $suma += $index;
    }
    return $suma;
  }
 ?>
