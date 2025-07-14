<?php require_once "./config.php";

session_start() ?>

<?php
$UserInput = "";
$PasswordInput = "";
$message = "";
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $UserInput = $_POST['Username'] ?? '';
    $PasswordInput = $_POST['Password'] ?? '';
    if ($UserInput === "admin" && $PasswordInput === "admin123") {
        header("Location: ./pages/admin_dashboard.php");
        exit();
    } elseif (!empty($UserInput) && !empty($PasswordInput)) {
        try {
            $query = "SELECT student_number, VoterPassword FROM voters WHERE student_number = ?";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(1, $UserInput);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            var_dump($row);
            var_dump($UserInput, $PasswordInput);
            if ($row) {
                if ($PasswordInput === $row['VoterPassword'] || $UserInput === "$row[student_number]") {
                    header("Location: ./pages/admin_dashboard.php");
                    exit();
                } else {
                    $message = "Your Password Was Incorrect";
                }
                $message = "User Not Found";
            } else {
                echo $message;
            }
        } catch (PDOException $e) {
            $message = "Database Error: " . $e->getMessage();
        }
    } else {
        $message = "Your ID Was Not Found";
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
            <form action="" method="POST">
                <label for="Username">Username</label><br>
                <input type="text" name="Username" value="<?php echo $UserInput; ?>" class="InputBox"><br>
                <label for="Password">Password</label><br>
                <input type="password" name="Password" value="<?php echo $PasswordInput; ?>" class="InputBox"><br>
                <button type="submit">login</button>
            </form>
        </div>
    </div>
</body>

</html>