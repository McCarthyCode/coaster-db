<?php

$usernameErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "settings.php";
    require "hash.php";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

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
                ($_GET["href"] ? sanitize($_GET["href"]) : "index.php"));
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
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/signin.css" rel="stylesheet">
</head>
<body>

<?php
if( $usernameErr || $passwordErr ) { ?>
<div class="container-fluid">
<div class="alert alert-danger" role="alert">The username or password you have entered is invalid.</div>
</div>
<?php
}
?>

<div class="container">
<form class="form-signin" action="
    <?php echo htmlspecialchars($_SERVER['PHP_SELF']) .
    ($_GET["href"] ? "?href=" . $_GET["href"] : "");?>"
    method="post">
<h2 class="form-signin-heading">Login to Coaster Rider</h2>
<input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo $username;?>">
<input type="password" class="form-control" name="password" placeholder="Password">
<button class="btn btn-lg btn-primary btn-block" type="submit">Sign In</button>
<div id="copyright">
Copyright &copy; <?php echo date("Y"); ?> Coaster Rider. All rights reserved.
</div>
</form>
</div><!-- /.container -->

<script src="js/bootstrap.min.js"></script>

</body>
</html>

