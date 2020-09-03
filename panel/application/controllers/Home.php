<?php
 /**
  * Information
  * @Author: xares
  * @Date:   25-04-2020 20:47
  * @Filename: Home.php
  * @Project: xDashTS3AudioBot
  *
  * Contact
  * @Email: xares.scripts@gmail.com
  * @TeamSpeak: x-scripts.pl, jutuby.net
  *
  * Modify
  * @Last Modified by:   xares
  * @Last Modified time: 27-05-2020 23:22
  *
  * @Copyright(C) 2020 x-Scripts
  */

 defined('BASEPATH') OR exit('No direct script access allowed');
 class Home extends CI_Controller {

   public function index() {
     if($this->session->userdata('logged')) {
       redirect(base_url('dash'));
     }
     redirect(base_url('login'));
   }

 }
 ?>
