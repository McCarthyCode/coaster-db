<?php
session_start();
require "../settings.php";
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
<?php

echo "<p>Hello, " .
    ($_SESSION['username'] ? $_SESSION['username'] : "guest") .
    "!</p>";

$conn = new mysqli($servername, $username, $password, "coasters");
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

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
//$n = $result->num_rows;

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
echo "</table>";
?>
<br/>
<table>
<tr><th>Add a park</th><th></th><th></th><th></th></tr>
<form action="" method="post">
<tr>
<td><input type="text" placeholder="Name"></td>
<td><input type="text" placeholder="City"></td>
<td><input type="text" placeholder="Region"></td>
<td><input type="text" placeholder="Country"></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td><input type="submit" value="Submit"></td>
</tr>
</form>

</body>
</html>
