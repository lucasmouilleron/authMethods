<?php require_once __DIR__."/../libs/tools.php" ?>
<!DOCTYPE html>
<head>

  <title>authMethods</title>
  <meta name="description" content="">

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width initial-scale=1.0">
  <link rel="shortcut icon" href="assets/img/favicon.png">
  <link rel="stylesheet" href="assets/css/main.css">

</head>
<body>

  <!-- /////////////////////////////////////////////////////////////// -->
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index">authMethods</a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="<?php if(isPage("php-sessions")) echo "active"?>"><a href="php-sessions">PHP sessions</a></li>
          <li class="<?php if(isPage("jwt")) echo "active"?>"><a href="jwt">JWT</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- /////////////////////////////////////////////////////////////// -->
  <div class="container">