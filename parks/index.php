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

// Retrieve Data
$sql = "select name, city, region, country from coasters.parks;";
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
    echo "<tr><td><a href='/coasters/?park=" .
         $row[0] . "'>" . $row[0] . "</a></td>" .
         "</td><td>" . $row[1] .
         "</td><td>" . $row[2] .
         "</td><td>" . $row[3] . "</td></tr>";
echo "</table>";
?>
</body>
</html>
