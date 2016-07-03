<html>
<head>
<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
<?php
$servername = "localhost";
$username = "public";

// Create connection
$conn = new mysqli($servername, $username, null);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$city = $_GET['city'];
$region = $_GET['region'];
$country = $_GET['country'];

if(!$city) $city = "%";
if(!$region) $region = "%";
if(!$country) $country = "%";

// Retrieve Data
$sql = "select name, city, region, country from coasters.parks " .
    "where coasters.parks.city like '" . $city . "' " .
    "and coasters.parks.region like '" . $region . "' " .
    "and coasters.parks.country like '" . $country . "';";
$result = $conn->query($sql);
$conn->close();

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
</body>
</html>
