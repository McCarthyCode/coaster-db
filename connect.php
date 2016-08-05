<?php
session_start();
require "settings.php";
require "hash.php";

// Create connection
$conn = new mysqli($servername, $username, $password, "coasters");

// Check connection
if ($conn->connect_error) {
    $_POST = array();
    die("Connection failed: " . $conn->connect_error);
} 

// Authenticate user
function sanitize($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

$username = sanitize($_POST['username']);
$password = sanitize($_POST['password']);

// look in database for registered user
$sql =
    "select * from users where username = '" .
    $username .
    "';";
$result = $conn->query($sql);
$arr->fetch_array($result);

// if not found
if(!$arr) {
    //user not found error
    //check spelling or register
} else {
    // compare client password with server hash
    $serverHash = $arr['password'];
    $clientHash = generateHash(
                        $_POST['password'],
                        substr($serverHash, 0, SALT_LENGTH)
                    );
}
?>
<style>
* {
    font-family: monospace;
    font-size: larger;
}
</style>
<?php

echo $serverHash;?><br><?php
echo $clientHash;?><br><?php

if( !strcmp($serverHash, $clientHash) ) {
    echo "Success!<br>";
} else {
    echo "Uh oh, better try again.<br>";
}

?>

