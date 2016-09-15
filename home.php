<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link href="/css/bootstrap.min.css" rel="stylesheet">
<style>
/*
@media (max-width: 767px)
button#navbar{
display: none;
}*/
</style>
</head>
<body>
<div>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">Coaster Rider</a>
    </div>
    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-left">
        <li class="active"><a href="/home.php">Home</a></li>
        <li><a href="/parks">Parks</a></li>
        <li><a href="/coasters">Coasters</a></li>
        <li class="visible-xs"><a href="/logout.php">Logout</a></li>
      </ul>
      <a class="nav navbar-nav navbar-right hidden-xs" href="/logout.php"><button class="btn btn-default navbar-btn">Logout</button></a>
      <ul class="nav navbar-nav navbar-right hidden-xs">
        <li><p class="navbar-text"><?php
echo 'Hello, <a class="navbar-link" href="/home.php">' . $_SESSION['username'] . '</a>!';?></p></li>
      </ul>
    </div>
  </div>
</nav>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

<body>
</html>

