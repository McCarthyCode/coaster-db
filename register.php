<?php

function sanitize($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

$usernameErr = $passwordErr = $username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form
    $valid = true;
    if (empty($_POST['username'])) {
        $usernameErr = "Username cannot be blank.";
        $valid = false;
    } else {
        $username = sanitize($_POST['username']);
        if(!ctype_alnum($username)) {
            $usernameErr = "Username must be alphanumeric.";
            $valid = false;
        }
    }
    if (empty($_POST['password'])) {
        $passwordErr = "Password is required.";
        $valid = false;
    } else {
        $password = sanitize($_POST['password']);
        if (empty($_POST['confirmation'])) {
            $passwordErr = "Password confirmation is required.";
            $valid = false;
        } else {
            $confirmation = sanitize($_POST['confirmation']);
            if(strcmp($password, $confirmation)) {
                $passwordErr = "Password and confirmation must match.";
                $valid = false;
            }
        }
    }

    // Register the user
    if($valid) {
        require "settings.php";
        require "hash.php";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            $_POST = array();
            die("Connection failed: " . $conn->connect_error);
        } 

        // Check for existing user
        $sql =
            "select * from users where username = '" .
            $_POST['username'] .
            "';";
        $result = $conn->query($sql);
        $arr = $result->fetch_array();

        // Store credentials if username is available
        if($arr) {
            $username = sanitize($_POST['username']);
            $usernameErr = "That username is unavailable.";
        } else {
            // Insert user data
            $sql =
                "insert into users (username, password) values ('" .
                $_POST['username'] .
                "', '" .
                generateHash($_POST['password']) .
                "');";
            if($conn->query($sql)) {
                session_start();
                $_SESSION['username'] = $_POST['username'];
                header("Location: index.php");
            } else {
                http_response_code(500);
            }
        }
    }
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
<div class="alert alert-danger" role="alert">
<?php
echo ($usernameErr) ? $usernameErr : $passwordErr;
?>
</div>
</div>
<?php
}
?>

<div class="container">
<form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<h2 class="form-signin-heading">Create an Account</h2>
<input id="username" type="text" class="form-control" name="username" placeholder="Username" value="<?php echo $username;?>">
<input id="password" type="password" class="form-control" name="password" placeholder="Password">
<input id="confirm" type="password" class="form-control" name="confirmation" placeholder="Confirm Password">
<button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
<ul>
<li><a href="/">Home</a></li>
<li><a href="/login.php">Login</a></li>
<li><a href="/register.php">Register</a></li>
</ul>
<div id="copyright">
Copyright &copy; <?php echo date("Y"); ?> Coaster Rider. All rights reserved.
</div>
</form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>

