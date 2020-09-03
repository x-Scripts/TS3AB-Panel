<?php
defined('BASEPATH') OR exit('No direct script access allowed');


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>TS3AudioBot - Error</title>
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
				<h1 class="d-block"><?= $heading; ?></h1>
			</div>
			<?= $message; ?>
			<div class="form-group" style="margin-top: 10px;">
			</div>
		</div>
	</div>
</body>
</html>
