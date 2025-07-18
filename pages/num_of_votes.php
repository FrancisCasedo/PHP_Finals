<?php
require_once "../config.php";
session_start();

function getPositions($conn) {
    $sql = "SELECT * FROM positions";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPartyList($conn) {
    $sql = "SELECT * FROM partylist";
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

function getMostVotesCandidate($conn, $position_id) {
    $sql = "SELECT candidate.candidate_name, candidate.number_of_votes, 
                    positions.position_name, partylist.party_name
            FROM candidate
            JOIN positions ON candidate.position_id = positions.id
            JOIN partylist ON candidate.partylist_id = partylist.id
            WHERE candidate.number_of_votes = (
                SELECT MAX(number_of_votes)
                FROM candidate
                WHERE position_id = :position_id
            ) AND candidate.position_id = :position_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['position_id' => $position_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMostVotesParty($conn) {
    $sql = "SELECT party_name, number_of_votes 
            FROM partylist
            WHERE number_of_votes = (SELECT MAX(number_of_votes) FROM partylist)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results</title>
</head>
<body>
    <h2>Election Results</h2>
    <?php
    $positions = getPositions($conn);
    if (count($positions) > 0) {
        foreach ($positions as $position) {
            echo "<h3>Candidate with Most Votes for " . htmlspecialchars($position['position_name']) . ":</h3>";
            $most_votes = getMostVotesCandidate($conn, $position['id']);
            foreach ($most_votes as $candidate) {
                echo htmlspecialchars($candidate['candidate_name']) . " (" . 
                    htmlspecialchars($candidate['party_name']) . ") - " . 
                    htmlspecialchars($candidate['number_of_votes']) . " votes</p>";
            }
        }
    }

    echo "<h3>Party with Most Votes: </h3>";
    $most_votes_party = getMostVotesParty($conn);
    if (count($most_votes_party) > 0) {
        foreach ($most_votes_party as $party) {
            echo htmlspecialchars($party['party_name']) . " - " . 
                 htmlspecialchars($party['number_of_votes']) . " votes";
        }
    }
    ?>
</body>
</html>