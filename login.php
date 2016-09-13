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
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/style.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>
.error {
    color: #FF0000;
}
</style>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
<div class="container">
<div class="collapse navbar-collapse">

<ul class="nav navbar-nav">
<li><a>Parks</a></li>
<li><a>Coasters</a></li>
</ul>

<div class="navbar-right">
<button class="btn btn-default navbar-btn">Sign In</button>
<button class="btn btn-default navbar-btn">Register</button>
</div>

</div>
</div>
</nav>

<div class="container">
<div class="page-header">
<form action="
    <?php echo htmlspecialchars($_SERVER['PHP_SELF']) .
    ($_GET["href"] ? "?href=" . $_GET["href"] : "");?>"
    method="post">
<input type="text" name="username" placeholder="username" value="<?php echo $username;?>"><span class="error"> <?php echo $usernameErr;?></span>
<input type="password" name="password" placeholder="password"><span class="error"> <?php echo $passwordErr;?>
<input type="submit">
</form>
</div>
</div>

<script src="js/bootstrap.min.js"></script>

</body>
</html>

