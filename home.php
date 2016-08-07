<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<div>
<?php
echo "Hello, " . $_SESSION['username'] . "!<br/>";
?>
<a href="logout.php">logout</a>
</div>
<body>
</html>

