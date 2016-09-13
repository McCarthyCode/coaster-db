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
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/signin.css" rel="stylesheet">
<style>
.error {
    color: #FF0000;
}
</style>
</head>
<body>

<?php
if( $usernameErr ) { ?>
<div class="container-fluid">
<div class="alert alert-danger" role="alert"><?php echo $usernameErr; ?></div>
</div><?php
} else if( $passwordErr ) { ?>
<div class="container-fluid">
<div class="alert alert-danger" role="alert"><?php echo $passwordErr; ?></div>
</div><?php
}
?>

<div class="container">
<form class="form-signin" action="
    <?php echo htmlspecialchars($_SERVER['PHP_SELF']) .
    ($_GET["href"] ? "?href=" . $_GET["href"] : "");?>"
    method="post">
<input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo $username;?>">
<input type="password" class="form-control" name="password" placeholder="Password">
<button class="btn btn-lg btn-primary btn-block" type="submit">Sign In</button>
</form>
</div><!-- /.container -->

<script src="js/bootstrap.min.js"></script>

</body>
</html>

