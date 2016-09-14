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
<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">Coaster Rider</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-left">
          <li><a href="parks">Parks</a></li>
          <li><a href="coasters">Coasters</a></li>
        </ul>
        <a class="nav navbar-nav navbar-right" href="logout.php"><button class="btn btn-default navbar-btn">Logout</button></a>
        <ul class="nav navbar-nav navbar-right">
          <li><p class="navbar-text"><?php
echo 'Hello, <a class="navbar-link" href="home.php">' . $_SESSION['username'] . '</a>!';
?></p></li>
        </ul>
      </div>
    </div>
  </div>
</nav>
</div>

<script src="/js/bootstrap.min.js"></script>

<body>
</html>

