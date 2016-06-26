<html>
<head>
<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
<style>
table {
        border-collapse: collapse;
            width: 100%;
}

th, td {
        text-align: left;
            padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
        background-color: #4CAF50;
            color: white;
}
</style>
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

//$park = "Holiday World";
// Retrieve Data
$sql = "select " .
    "coasters.coasters.name, coasters.coasters.track_type, " .
    "coasters.coasters.status, coasters.parks.name " .
    "from coasters.coasters " .
    "inner join coasters.parks " .
    "on coasters.parks.id=coasters.coasters.park_id " .
    "where coasters.parks.name like '" . $park . "';";
$result = $conn->query($sql);

// Display Data
$n = $result->num_rows;

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
