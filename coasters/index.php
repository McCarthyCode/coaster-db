<html>
<head>
<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
<?php
$servername = "localhost";
$username = "public";
$password = null;

$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$park = $_GET['park'];
if(!$park) $park = "%";

// Retrieve Data
$sql =
    "select " .
        "coasters.coasters.name, coasters.coasters.track_type, " .
        "coasters.coasters.status, coasters.parks.name " .
    "from coasters.coasters " .
    "inner join coasters.parks " .
        "on coasters.parks.id=coasters.coasters.park_id " .
    "where coasters.parks.name like '" . $park . "' " .
    "order by " .
        "coasters.parks.name, " .
        "coasters.coasters.status asc, " .
        "coasters.coasters.name asc;";
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

$conn->close();
?>
</body>
</html>
