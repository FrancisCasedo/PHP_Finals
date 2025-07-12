
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

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_voter'])) {
    $student_no = $_POST['student_no'];
    $fname = $_POST['fname'];
    $mi = $_POST['mi'];
    $lastname = $_POST['lastname'];
    $course = $_POST['course'];
    $password = $_POST['voter_password'];

    $sql = "INSERT INTO voters (student_number, first_name, middle_initial, last_name, Course,VoterPassword)
            VALUES (:student_no, :fname, :mi, :lastname, :course, :VoterPassword)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':student_no' => $student_no,
        ':fname' => $fname,
        ':mi' => $mi,
        ':lastname' => $lastname,
        ':course' => $course,
        ':VoterPassword' => $password
    ]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_voter'])) {
    $id = $_POST['id'];
    $student_no = $_POST['student_no'];
    $fname = $_POST['fname'];
    $mi = $_POST['mi'];
    $lastname = $_POST['lastname'];
    $course = $_POST['course'];

    $sql = "UPDATE voters SET student_no=:student_no, fname=:fname, mi=:mi, lastname=:lastname, course=:course, VoterPassword =:VoterPassword WHERE ID=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':student_no' => $student_no,
        ':fname' => $fname,
        ':mi' => $mi,
        ':lastname' => $lastname,
        ':course' => $course,
        ':id' => $id,
        ':VoterPassword' => $VoterPassword
    ]);
    header("Location: voter.php");
    exit();
}

if (isset($_GET['delete_voter'])) {
    $id = $_GET['delete_voter'];
    $sql = "DELETE FROM voters WHERE ID=:id";
    $stmt_d_voter = $conn->prepare($sql);
    $stmt_d_voter->execute([':id' => $id]);
    header("Location: voter.php");
    exit();
}

$edit_mode_voter = false;
$edit_voter = ['ID'=>'','student_no'=>'', 'fname'=>'', 'mi'=>'', 'lastname'=>'', 'course'=>'', 'VoterPassword'=>''];

if (isset($_GET['edit'])) {
    $edit_mode_voter = true;
    $id = $_GET['edit'];
    $sql = "SELECT * FROM voters WHERE ID=:id";
    $stmt_e_voter = $conn->prepare($sql);
    $stmt_e_voter->execute([':id' => $id]);
    $result_edit = $stmt_e_voter->fetch(PDO::FETCH_ASSOC);
    if ($result_edit) {
        $edit_voter = $result_edit;
    }
}

$stmt = $conn->prepare("SELECT * FROM voters ORDER BY ID ASC");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voters</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h1><?php echo $edit_mode_voter ? "Edit voter" : "Add Voter"; ?></h1>
        <hr class="divider">
        <form method="Post" action="">
            <input type="hidden" name="id" value="<?php echo $edit_voter['id']; ?>">
            <label>Student Number:</label><br>
            <input type="text" name="student_no" value="<?php echo $edit_voter['student_no']; ?>" required><br>

            <label>First Name:</label><br>
            <input type="text" name="fname" value="<?php echo $edit_voter['fname']; ?>" required><br>
            <label>Middle Initial:</label><br>
            <input type="text" name="mi" maxlength="1" value="<?php echo $edit_voter['mi']; ?>" required><br>
            <label>Last Name:</label><br>
            <input type="text" name="lastname" value="<?php echo $edit_voter['lastname']; ?>" required><br>
            <label>Course:</label><br>
            <input type="text" name="course" value="<?php echo $edit_voter['course']; ?>" required><br>
            <label>Password:</label><br>
            <input type="text" name="voter_password" value="<?php echo $edit_voter['VoterPassword']; ?>" required><br>

            <?php if ($edit_mode_voter): ?>
            <input type="submit" name="update_voter" value="update_voter">
            <a href="voter.php">Cancel</a>
            <?php else: ?>
                <input type="submit" name="add_voter" value="Add_voter">
            <?php endif; ?>
        </form>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>Number</th>
                <th>Student ID</th>
                <th>First Name</th>
                <th>MI</th>
                <th>Last Name</th>
                <th>Course</th>
                <th>VoterPassword</th>
                <th>Action</th>
            </tr>
            <?php
            $i = 1;
            foreach ($result as $row) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['student_number']}</td>
                        <td>{$row['first_name']}</td>
                        <td>{$row['middle_initial']}</td>
                        <td>{$row['last_name']}</td>
                        <td>{$row['Course']}</td>
                        <td>{$row['VoterPassword']}</td>
                        <td>
                            <a href='?edit={$row['id']}'>Edit</a> |
                            <a href='?delete_voter={$row['id']}' onclick=\"return confirm('Are you sure?')\">Delete</a>
                        </td>
                    </tr>";
                $i++;
            }
            ?>
        </table>
    </div>
</body>
</html>
