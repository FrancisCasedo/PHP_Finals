<?php
$Input = "";
$Input2 = "";
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Input = $_POST['Username'] ?? '';
    $Input2 = $_POST['Password'] ?? '';
    if ($Input === "admin" && $Input2 === "admin123") {
        header("Location: admin_dashboard.php");
        exit();
    } elseif ($Input === "user" && $Input2 === "user123") { // should be connected to the database for real user validation
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
                <input type="text" name="Username" value="<?php echo $Input; ?>" class="InputBox"><br>
                <label for="Password">Password</label><br>
                <input type="password" name="Password" value=$Input2 class="InputBox"><br>
                <button type="submit">login</button>
            </form>
        </div>
    </div>
</body>

</html>