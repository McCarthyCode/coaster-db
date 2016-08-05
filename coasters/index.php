<?php
session_start();
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

$servername = "localhost";
$username = $_SESSION['username'] ? $_SESSION['username'] : "public";
$password = $_SESSION['password'] ? $_SESSION['password'] : null;

if ($_SESSION['connection'] != null) {
    echo "<p>Already connected.</p>";
    $conn = $_SESSION['connection'];
} else {
    echo "<p>Trying to connect again.</p>";
    $conn = new mysqli($servername, $username, $password, "coasters");
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);
}

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

// Display Data
echo "<table><tr>" .
     "<th>Coaster Name</th>" .
     "<th>Track Type</th>" .
     "<th>Status</th>" .
     "<th>Park</th>" .
     "</td>";
while($row = $result->fetch_array())
    echo "<tr><td>" . $row[0] .
         "</td><td>" . $row[1] .
         "</td><td>" . $row[2] .
         "</td><td><a href='/coasters/?park=" .
         $row[3] . "'>" . $row[3] . "</a></td></tr>";
echo "</table>";

?>
</body>
</html>
