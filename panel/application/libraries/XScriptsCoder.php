<?php
 /**
  * Information
  * @Author: xares
  * @Date:   11-05-2020 03:31
  * @Filename: XScriptsCoder.class.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 11-05-2020 13:23
  *
  * @Copyright(C) 2020 x-Scripts
  */

  class XScriptsCoder {
    private $imp = '.,1234567890&$%';


    public function encode($string, $base64 = 1, $encoding = 'utf8') {
      $lenght = mb_strlen($string,$encoding);

      $en = array();
      $a = $this->EncodeChars();
      foreach($this->DecodeChars() as $id => $index) {
        $en[$index] = $a[$id];
      }

      $encode = '';
      for ($i=0; $i < $lenght; $i++) {
        if(isset($en[$string[$i]])) {
          $encode .= $this->randImp().$en[$string[$i]].$this->randImp();
        } else {
          $encode .= $string[$i];
        }
      }

      for ($base=0; $base < $base64; $base++) {
        $encode = base64_encode($encode);
      }
      $encode .= '~'.$base64;
      return $encode;
    }

    public function decode($encoding) {
      $encoding = preg_split('/[~]/',$encoding);

      for ($i=0; $i < (int)$encoding[1]; $i++) {
        $encoding[0] = base64_decode($encoding[0]);
      }
      $encoding = $this->toArray($encoding[0]);

      $de = array();
      $a = $this->DecodeChars();
      foreach($this->EncodeChars() as $id => $index) {
        $de[$index] = $a[$id];
      }

      $decode = '';
      foreach($encoding as $item) {
        $decode .= $de[$item];
      }

      return $decode;
    }

    private function randImp() {
      return $this->imp[rand(0,strlen($this->imp)-1)];
    }

    private function toArray($string) {
      return preg_split("/[{$this->imp}]/",$string,-1,PREG_SPLIT_NO_EMPTY);
    }


    private function DecodeChars() {
      return array(
        'q','w','e','r','t','y','u','i','o','p',
        'a','s','d','f','g','h','j','k','l','z',
        'x','c','v','b','n','m','Q','W','E','R',
        'T','Y','U','I','O','P','A','S','D','F',
        'G','H','J','K','L','Z','X','C','V','B',
        'N','M','ą','ś','ć','ó','ę','ż','ź','ń',
        'Ą','Ś','Ć','Ó','Ę','Ż','Ź','Ń','1','2',
        '3','4','5','6','7','8','9','0','[',']',
        ';',':',"'",'"','\\','|',',','<','.','>',
        '/','?','{','}','`','~','!','@','#','$',
        '%','^','&','*','(',')','-','_','+','=',
        ' '
      );
    }


    private function EncodeChars() {
      return array(
        'qwer','tyui','opas','dfgh','jklz','xcvb','nmmn','bvcx','zlkj','hgfd',
        'sapo','iuyt','rewq','qazw','sxed','crfv','tgby','hnuj','miko','lppl',
        'okim','junh','ybgt','vfrc','dexs','wzaq','QaER','TYas','OPAS','DFGH',
        'JKLZ','XadB','NMMN','BVCX','ZLKJ','HGFD','SaDo','IUYT','REWQ','QAZW',
        'SXED','CRFV','TGBY','HNUJ','MIKO','LPPL','OKIM','agNH','YBGT','BGTV',
        'csCD','EcfW','ZAQY','fruk','zopa','yaop','wboc','wfvn','qafb','wcfv',
        'UADL','kfgv','wsfo','sadf','EIOM','fwjk','woBs','oqwa','adSd','JMEs',
        'efHk','sekp','nCqa','erFq','SadJ','fehd','djrg','sdfh','shec','sfhh',
        'joed','sonl','dhrm','jhsj','Jons','Onja','hnja','nkLO','nskA','DAMa',
        'bndO','bKOP','KsdK','knUY','nPoB','fONs','aOMP','BDpa','DNad','MWas',
        'ASDa','MSad','NWAk','mowN','nspM','aPMA','amnP','wnoG','NSka','aNEP',
        'NSma'
      );
    }
  }
 ?>
