<?php
require_once "../config.php";
session_start();
// if ( isset( $_SESSION['student_number'] ) ) {
//     header("Location: ../login_page.php");
//     exit();
// }

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        foreach ($_POST['vote'] as $position_id => $candidate_id) {
            $sql_update = "UPDATE candidate SET number_of_votes = number_of_votes + 1 WHERE id = :candidate_id";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->execute(['candidate_id' => $candidate_id]);
        }
        echo "Votes submitted successfully!";
        exit();
    } catch (PDOException $e) {
        echo "Error submitting votes: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user_dashboard_style.css">
    <title>user_dashboard</title>
</head>
<body>
    <form method="POST">
        <?php
        $sql = "SELECT * FROM positions";
        $stmt = $conn->query($sql);
        $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($positions) > 0) {
            foreach ($positions as $position) {
                echo "<table>";
                echo "<tr><th>" . htmlspecialchars($position['position_name']) . "</th></tr>";
                try {
                    $sql_2 = "SELECT candidate.* FROM candidate
                             JOIN positions ON candidate.position_id = positions.id
                             WHERE candidate.position_id = :position_id";
                    $stmt_2 = $conn->prepare($sql_2);
                    $stmt_2->execute(['position_id' => $position['id']]);
                    $candidates = $stmt_2->fetchAll(PDO::FETCH_ASSOC);
                    if (count($candidates) > 0) {
                        foreach ($candidates as $candidate) {
                            echo "<tr><td>";
                            echo "<input type='radio' name='vote[".htmlspecialchars($position['id'])."]'value='".htmlspecialchars($candidate['id'])."' required>";
                            echo htmlspecialchars($candidate['candidate_name']);
                            echo "</td></tr>";
                        }
                    } else {
                        echo "<tr><td>No candidates for this position</td></tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td>Error loading candidates</td></tr>";
                }
                echo "</table>";
            }
            echo "<button type='submit' class='submit_btn'>Submit Votes</button>";
        } else {
            echo "<p>No positions available</p>";
        }
        ?>
    </form>
</body>
</html>