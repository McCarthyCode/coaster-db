<?php
session_start();
require "../settings.php";
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
<?php
$isLoggedIn = ($_SESSION['username'] == null) ? false : true;

$conn = new mysqli($servername, $username, $password, "coasters");
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

$park = $_GET['park'];
if(!$park) $park = "%";

// Retrieve Data
$sql =
    "select " .
        "coasters.name, " .
        "coasters.track_type, " .
        "coasters.status, " .
        "parks.name " .
    "from coasters " .
    "inner join parks " .
        "on parks.id=coasters.park_id " .
    "where parks.name like '" . $park . "' " .
    "order by " .
        "parks.name, " .
        "coasters.status asc, " .
        "coasters.name asc;";
$result = $conn->query($sql);
?>
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
        <li><a href="/">Home</a></li>
        <li><a href="/parks">Parks</a></li>
        <li class="active"><a href="/coasters">Coasters</a></li>
<?php
if (!$isLoggedIn) {
?>
        <li class="visible-xs"><a href="/login.php?href=coasters">Login</a></li>
      </ul>
      <a class="nav navbar-nav navbar-right hidden-xs" href="/login.php?href=coasters"><button class="btn btn-default navbar-btn">Login</button></a>
<?php
} else {
?>
        <li class="visible-xs"><a href="/logout.php">Logout</a></li>
      </ul>
      <a class="nav navbar-nav navbar-right hidden-xs" href="/logout.php"><button class="btn btn-default navbar-btn">Logout</button></a>
      <ul class="nav navbar-nav navbar-right hidden-xs">
        <li><p class="navbar-text"><?php
echo 'Hello, <a class="navbar-link" href="/">' . $_SESSION['username'] . '</a>!';?></p></li>
      </ul>
<?php
}
?>
    </div>
  </div>
</nav>
<div class="container">
<?php
// Display Data
echo
    "<table><tr>" .
    ($isLoggedIn ? "<th>Ridden?</th>" : "") .
    "<th>Coaster Name</th>" .
    "<th>Track Type</th>" .
    "<th>Status</th>" .
    "<th>Park</th>" .
    "</td>";
while($row = $result->fetch_array())
    echo 
        ($isLoggedIn ? "<tr><td><input type='checkbox'></td>" : "<tr>") .
        "<td>" . $row[0] .
        "</td><td>" . $row[1] .
        "</td><td>" . $row[2] .
        "</td><td><a href='/coasters/?park=" .
        $row[3] . "'>" . $row[3] . "</a></td></tr>";
echo "</table>";
?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

</body>
</html>

