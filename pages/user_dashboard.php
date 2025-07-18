<?php
require_once "../config.php";
session_start();

if (!isset($_SESSION['student_number']) || (isset($_SESSION['is_voted']) && $_SESSION['is_voted'] == 1)) {
    header("Location: ../login_page.php");
    exit();
}
function getPositions($conn) {
    $sql = "SELECT * FROM positions";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getCandidatesByPosition($conn, $position_id) {
    $sql = "SELECT candidate.* FROM candidate
            JOIN positions ON candidate.position_id = positions.id
            WHERE candidate.position_id = :position_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['position_id' => $position_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPartyList($conn) {
    try {
        $sql = "SELECT * FROM partylist";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching party lists: " . $e->getMessage());
        echo "<p style='color: red;'>Error loading party lists</p>";
        return [];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql_check = "SELECT is_voted FROM voters WHERE student_number = :student_number";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute(['student_number' => $_SESSION['student_number']]);
        $voter = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if (!$voter) {
            error_log("Voter not found for student_number: " . $_SESSION['student_number']);
            echo "Error: Voter not found";
            exit();
        }
        if ($voter['is_voted'] == 1) {
            echo "You have already voted!";
            exit();
        }

        if (isset($_POST['vote'])) {
            foreach ($_POST['vote'] as $position_id => $candidate_id) {
                if ($position_id !== 'party') {
                    $sql_update_candidate_votes = "UPDATE candidate SET number_of_votes = number_of_votes + 1 WHERE id = :candidate_id";
                    $stmt_update_candidate_votes = $conn->prepare($sql_update_candidate_votes);
                    $stmt_update_candidate_votes->execute(['candidate_id' => $candidate_id]);
                }
            }
        }

        if (isset($_POST['vote']['party'])) {
            $sql_update_partylist_votes = "UPDATE partylist SET number_of_votes = number_of_votes + 1 WHERE id = :party_id";
            $stmt_update_partylist_votes = $conn->prepare($sql_update_partylist_votes);
            $stmt_update_partylist_votes->execute(['party_id' => $_POST['vote']['party']]);
        }

        $sql_update_voter_status = "UPDATE voters SET is_voted = 1 WHERE student_number = :student_number";
        $stmt_update_voter_status = $conn->prepare($sql_update_voter_status);
        $stmt_update_voter_status->execute(['student_number' => $_SESSION['student_number']]);

        $sql_verify = "SELECT is_voted FROM voters WHERE student_number = :student_number";
        $stmt_verify = $conn->prepare($sql_verify);
        $stmt_verify->execute(['student_number' => $_SESSION['student_number']]);
        $updated_voter = $stmt_verify->fetch(PDO::FETCH_ASSOC);

        $_SESSION['is_voted'] = 1;

        echo "Votes submitted successfully!";
        header("Location: ../login_page.php");
        exit();
    } catch (PDOException $e) {
        error_log("Error submitting votes: " . $e->getMessage());
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
        $positions = getPositions($conn);
        if (count($positions) > 0) {
            foreach ($positions as $position) {
                echo "<table>";
                echo "<tr><th>" . htmlspecialchars($position['position_name']) . "</th></tr>";
                $candidates = getCandidatesByPosition($conn, $position['id']);
                if (count($candidates) > 0) {
                    foreach ($candidates as $candidate) {
                        echo "<tr><td>";
                        echo "<input type='radio' name='vote[" . htmlspecialchars($position['id']) . "]' value='" . htmlspecialchars($candidate['id']) . "' required>";
                        echo htmlspecialchars($candidate['candidate_name']);
                        echo "</td></tr>";
                    }
                } else {
                    echo "<tr><td>No candidates for this position</td></tr>";
                }
                echo "</table>";
            }
        } else {
            echo "<p>No positions available</p>";
        }

        $party_list = getPartyList($conn);
        if (count($party_list) > 0) {
            echo "<table>";
            echo "<tr><th>Party Lists</th></tr>";
            foreach ($party_list as $party) {
                echo "<tr><td>";
                echo "<input type='radio' name='vote[party]' value='" . htmlspecialchars($party['id']) . "' required>";
                echo htmlspecialchars($party['party_name']);
                echo "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No party lists available</p>";
        }
        echo '<button type="submit">Submit Votes</button>';
        ?>
    </form>
</body>
</html>