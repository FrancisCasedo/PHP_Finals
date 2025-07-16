<?php
require_once "../config.php";
session_start();
// if ( isset( $_SESSION['student_number'] ) ) {
//     header("Location: ../login_page.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user_dashboard</title>
</head>
<body>
    <?php
    $sql = "SELECT * FROM positions";
    $stmt = $conn->query($sql);
    $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($positions) > 0) {
        foreach($positions as $position) {
            echo "<tr><td>". htmlspecialchars($position['position_name']). "</td></tr><br>";
            try{
                $sql_2 = "SELECT candidate.* FROM candidate
                         JOIN positions ON candidate.position_id = positions.id
                         WHERE candidate.position_id = :position_id";
                $stmt_2 = $conn->prepare($sql_2);
                $stmt_2->execute(['position_id' => $position['id']]);
                $candidates = $stmt_2->fetchAll(PDO::FETCH_ASSOC);
                if (count($candidates) > 0) {
                    foreach($candidates as $candidate) {
                        echo htmlspecialchars($candidate['candidate_name']);
                    }
                }
                else {
                    echo "No candidates for this position";
                }

            }
            catch (PDOException $e) {
                echo "Error loading candidates";
            }
        }
    }
    else {
        echo "No positions available";
    }
    
    ?>
</body>
</html>