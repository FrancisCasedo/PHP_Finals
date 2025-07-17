<?php
require_once './config.php';
session_start();

$UserInput = "";
$PasswordInput = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $UserInput = $_POST['Username'] ?? '';
    $PasswordInput = $_POST['Password'] ?? '';

    if (empty($UserInput) || empty($PasswordInput)) {
        $message = "Please enter both username and password";
    } elseif ($UserInput === "admin" && $PasswordInput === "admin123") {
        $_SESSION['student_number'] = $UserInput;
        $_SESSION['is_admin'] = true;
        header("Location: ./pages/admin_dashboard.php");
        exit();
    } else {
        try {
            $query = "SELECT student_number, VoterPassword, is_voted FROM voters WHERE student_number = :student_number";
            $stmt = $conn->prepare($query);
            $stmt->execute(['student_number' => $UserInput]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                if ($row['is_voted'] == 1) {
                    $message = "You have already voted";
                }
                elseif ($PasswordInput === $row['VoterPassword']) {
                    $_SESSION['student_number'] = $row['student_number'];
                    $_SESSION['is_voted'] = $row['is_voted'] ? 1 : 0;
                    header("Location: ./pages/user_dashboard.php");
                    exit();
                } else {
                    $message = "Incorrect password";
                }
            } else {
                $message = "User not found";
            }
        } catch (PDOException $e) {
            error_log("Login database error: " . $e->getMessage());
            $message = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/login_page_style.css">
    <title>Election Login</title>
</head>
<body>
    <div class="Divider">
        <h2>Election Login</h2>
        <?php if (!empty($message)): ?>
            <p class="error"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="Username">Username</label><br>
            <input type="text" name="Username" value="<?php echo htmlspecialchars($UserInput); ?>" class="InputBox"><br>
            <label for="Password">Password</label><br>
            <input type="password" name="Password" value="" class="InputBox"><br>
            <button type="submit">login</button>
        </form>
    </div>
</body>
</html>