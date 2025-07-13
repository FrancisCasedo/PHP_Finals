<?php
$UserInput = "";
$PasswordInput = "";
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $UserInput = $_POST['Username'] ?? '';
    $PasswordInput = $_POST['Password'] ?? '';
    if ($Input === "admin" && $PasswordInput === "admin123") {
        header("Location: admin_dashboard.php");
        exit();
    } elseif ($UserInput === "user" && $PasswordInput === "user123") { // should be connected to the database for real user validation
        session_start();
        header("Location: user_dashboard.php");
        exit();

    } else {
        echo "<script>alert('Invalid Username or Password');</script>";
    }

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <div>
            <h2>Election --------</h2>
            <form action="">
                <label for="Username">Username</label><br>
                <input type="text" name="Username" value="<?php echo $UserInput; ?>" class="InputBox"><br>
                <label for="Password">Password</label><br>
                <input type="password" name="Password" value=$PasswordInput class="InputBox"><br>
                <button type="submit">login</button>
            </form>
        </div>
    </div>
</body>

</html>