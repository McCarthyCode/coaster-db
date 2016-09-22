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
<style>
.error {
    color: #FF0000;
}
</style>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<table>
  <tr>
    <td>username: </td>
    <td><input type="text" name="username" value="<?php echo $username;?>"><span class="error"> <?php echo $usernameErr;?></span></td>
  </tr>
  <tr>
    <td>password: </td>
    <td><input type="password" name="password"><span class="error"> <?php echo $passwordErr;?></td>
  </tr>
  <tr>
    <td>confirm password: </td>
    <td><input type="password" name="confirmation"></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit"></td>
  </tr>
</table>
</form>
</p>
</body>
</html>

