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
// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $city = $_POST['city'];
    $region = $_POST['region'];
    $country = $_POST['country'];
    $inputErr = "";

    if(
        $name == null |
        $city == null |
        $region == null |
        $country == null ) {
        $inputErr = "Missing criteria.";
    } else {
        $sql =
            "select * from parks where " .
                "name = '" . $name . "' and " .
                "city = '" . $city . "' and " .
                "region = '" . $region . "' and " .
                "country = '" . $country . "';";
        if($result = $conn->query($sql)->fetch_array()) {
            $inputErr = "That park has been added already.";
        } else {
            $sql =
                "insert into parks set " .
                    "name = '" . $name . "', " .
                    "city = '" . $city . "', " .
                    "region = '" . $region . "', " .
                    "country = '" . $country . "';";
            $result = $conn->query($sql);
        }
    }
    $_POST = array();
}
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
        <li class="active"><a href="/parks">Parks</a></li>
        <li><a href="/coasters">Coasters</a></li>
<?php
if ($_SESSION['username'] == null) {
?>
        <li class="visible-xs"><a href="/login.php?href=parks">Login</a></li>
      </ul>
      <a class="nav navbar-nav navbar-right hidden-xs" href="/login.php?href=parks"><button class="btn btn-default navbar-btn">Login</button></a>
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
// Display table data
$city = $_GET['city'];
$region = $_GET['region'];
$country = $_GET['country'];

if(!$city) $city = "%";
if(!$region) $region = "%";
if(!$country) $country = "%";

// Retrieve Data
$sql = "select name, city, region, country from parks " .
    "where parks.city like '" . $city . "' " .
    "and parks.region like '" . $region . "' " .
    "and parks.country like '" . $country . "';";
$result = $conn->query($sql);

// Display Data
echo "<table><tr>" .
     "<th>Park Name</th>" .
     "<th>City</th>" .
     "<th>Region</th>" .
     "<th>Country</th>" .
     "</td>";
while($row = $result->fetch_array())
    echo "<tr>" .
         "<td><a href='/coasters/?park=" .
            $row[0] . "'>" . $row[0] . "</a></td>" .
         "<td><a href='/parks/?city=" .
            $row[1] . "'>" . $row[1] . "</a></td>" .
         "<td><a href='/parks/?region=" .
            $row[2] . "'>" . $row[2] . "</a></td>" .
         "<td><a href='/parks/?country=" .
            $row[3] . "'>" . $row[3] . "</a></td>" .
         "</tr>";
echo "</table><p><span class='error'>" . $inputErr . "</span></td></p>";
?>
<table>
<tr><th>Add a park</th><th></th><th></th><th></th></tr>
<form action="
    <?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"
    method="post">
<tr>
<td><input type="text" name="name" placeholder="Name"></td>
<td><input type="text" name="city" placeholder="City"></td>
<td><input type="text" name="region" placeholder="Region"></td>
<td><input type="text" name="country" placeholder="Country"></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td><input type="submit" value="Submit"></td>
</tr>
</form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

</body>
</html>
