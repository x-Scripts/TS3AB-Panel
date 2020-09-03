<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class help extends CI_Controller {
	public function isApi() {
		$split = preg_split('/[\/]/',uri_string());
		if(isset($split[0])) {
			if(strtolower($split[0]) == 'api') {
				header('Content-Type: application/json');
				http_response_code(200);
				exit(printJson(false,'Error 404'));
			}
		}
		return;
	}

}

$help = new help();
$help->isApi();

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>TS3AudioBot - Error 404</title>
	<!-- Bootstrap CSS-->
	<link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css'); ?>">
	<!-- theme stylesheet-->
	<link rel="stylesheet" href="<?= base_url('assets/css/custom.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/style.red.css'); ?>" id="theme-stylesheet">
	<link rel="shortcut icon" href="<?= base_url('assets/img/favicon.ico'); ?>">
	<style>
		body {
			background: #22252a;
		}

		.error-bar {
			position: fixed;

			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			padding: 20px;
			text-align: center;
		}
	</style>
</head>

<body>
	<div class="col-sm-6 error-bar">
		<div class="block">
			<div class="title">
				<h1 class="d-block">Error 404</h1>
			</div>
			Nie odnaleziono żądanej strony!
			<div class="form-group" style="margin-top: 10px;">
				<a href="<?= base_url(); ?>"><button class="btn btn-dark">Strona główna</button></a>
			</div>
		</div>
	</div>
</body>
</html>
