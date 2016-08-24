<?php

$usernameErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $arr = $result->fetch_array();
    
    if($arr == null) {
        $usernameErr = "Username not found.";
    } else {
        // compare client password with server hash
        $serverHash = $arr['password'];
        $clientHash = generateHash(
                $_POST['password'],
                substr($serverHash, 0, SALT_LENGTH)
                );
        if( !strcmp($serverHash, $clientHash) ) {
            session_start();
            $_SESSION['username'] = $_POST['username'];
            header("Location: " .
                ($_GET["href"] ? sanitize($_GET["href"]) : "home.php"));
        } else {
            $passwordErr = "Password is invalid.";
        }
    }
} else {
    session_start();
    session_destroy();
}
?>
<!DOCTYPE html>
<html>
<head>
<style>
.error {
    color: #FF0000;
}
</style>
</head>
<body>
<form action="
    <?php echo htmlspecialchars($_SERVER['PHP_SELF']) .
    ($_GET["href"] ? "?href=" . $_GET["href"] : "");?>"
    method="post">
<table>
<tr>
<td>username:</td>
<td><input type="text" name="username" value="<?php echo $username;?>"><span class="error"> <?php echo $usernameErr;?></span></td>
</tr>
<tr>
<td>password:</td>
<td><input type="password" name="password"><span class="error"> <?php echo $passwordErr;?></td>
</tr>
<tr>
<td></td>
<td><input type="submit"></td>
</tr>
</table>
</form>
</body>
</html>

