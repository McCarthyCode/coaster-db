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
// Connect to database
$conn = new mysqli($servername, $username, $password, "coasters");
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

// Login/logout
if ($_SESSION['username'] != null) {
    echo "<p>Hello, " . $_SESSION['username'] . "!</p>" .
         "<p><a href='../logout.php'>Logout</a></p>";
} else {
    echo "<p>Hello, guest!</p>" .
         "<p><a href='../login.php?href=parks'>Login</a></p>";
}

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
</body>
</html>
