<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "election_database";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$stmt_party_list = $conn->prepare("SELECT * FROM partylist");
$stmt_party_list->execute();
$count_party_list = $stmt_party_list->rowCount();
$rows_party_list = $stmt_party_list->fetchAll(PDO::FETCH_ASSOC);

$stmt_candidate = $conn->prepare("SELECT * FROM candidate");
$stmt_candidate->execute();
$count_candidate = $stmt_candidate->rowCount();
$rows_candidate = $stmt_candidate->fetchAll(PDO::FETCH_ASSOC);

$stmt_positions = $conn->prepare("SELECT * FROM positions");
$stmt_positions->execute();
$count_positions = $stmt_positions->rowCount();
$rows_positions = $stmt_positions->fetchAll(PDO::FETCH_ASSOC);

$stmt_voters = $conn->prepare("SELECT * FROM voters");
$stmt_voters->execute();
$count_voters = $stmt_voters->rowCount();
$rows_voters = $stmt_voters->fetchAll(PDO::FETCH_ASSOC);

?>