<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "voter";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $student_no = $_POST['student_no'];
    $fname = $_POST['fname'];
    $mi = $_POST['mi'];
    $lastname = $_POST['lastname'];
    $course = $_POST['course'];

    $sql = "INSERT INTO voters (student_no, fname, mi, lastname, course)
            VALUES (:student_no, :fname, :mi, :lastname, :course)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':student_no' => $student_no,
        ':fname' => $fname,
        ':mi' => $mi,
        ':lastname' => $lastname,
        ':course' => $course
    ]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $student_no = $_POST['student_no'];
    $fname = $_POST['fname'];
    $mi = $_POST['mi'];
    $lastname = $_POST['lastname'];
    $course = $_POST['course'];

    $sql = "UPDATE voters SET student_no=:student_no, fname=:fname, mi=:mi, lastname=:lastname, course=:course WHERE ID=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':student_no' => $student_no,
        ':fname' => $fname,
        ':mi' => $mi,
        ':lastname' => $lastname,
        ':course' => $course,
        ':id' => $id
    ]);
    header("Location: voter.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM voters WHERE ID=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    header("Location: voter.php");
    exit();
}

$edit_mode = false;
$edit_data = ['ID'=>'','student_no'=>'', 'fname'=>'', 'mi'=>'', 'lastname'=>'', 'course'=>''];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $sql = "SELECT * FROM voters WHERE ID=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    $result_edit = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result_edit) {
        $edit_data = $result_edit;
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
        <h1><?php echo $edit_mode ? "Edit voter" : "Add Voter"; ?></h1>
        <hr class="divider">
        <form method="Post" action="">
            <input type="hidden" name="id" value="<?php echo $edit_data['ID']; ?>">
            <label>Student Number:</label><br>
            <input type="text" name="student_no" value="<?php echo $edit_data['student_no']; ?>" required><br>

            <label>First Name:</label><br>
            <input type="text" name="fname" value="<?php echo $edit_data['fname']; ?>" required><br>
            <label>Middle Initial:</label><br>
            <input type="text" name="mi" maxlength="1" value="<?php echo $edit_data['mi']; ?>" required><br>
            <label>Last Name:</label><br>
            <input type="text" name="lastname" value="<?php echo $edit_data['lastname']; ?>" required><br>
            <label>Course:</label><br>
            <input type="text" name="course" value="<?php echo $edit_data['course']; ?>" required><br>

            <?php if ($edit_mode): ?>
            <input type="submit" name="update" value="update">
            <a href="voter.php">Cancel</a>
            <?php else: ?>
                <input type="submit" name="add" value="Add">
            <?php endif; ?>
        </form>
    </div>

    <div class="table-container">
        <h2>+++++OUTPUT+++++</h2>
        <table>
            <tr>
                <th>Number</th>
                <th>Student ID</th>
                <th>First Name</th>
                <th>MI</th>
                <th>Last Name</th>
                <th>Course</th>
                <th>Action</th>
            </tr>
            <?php
            $i = 1;
            foreach ($result as $row) {
                echo "<tr>
                        <td>{$i}</td>
                        <td>{$row['student_no']}</td>
                        <td>{$row['fname']}</td>
                        <td>{$row['mi']}</td>
                        <td>{$row['lastname']}</td>
                        <td>{$row['course']}</td>
                        <td>
                            <a href='?edit={$row['ID']}'>Edit</a> |
                            <a href='?delete={$row['ID']}' onclick=\"return confirm('Are you sure?')\">Delete</a>
                        </td>
                    </tr>";
                $i++;
            }
            ?>
        </table>
    </div>
</body>
</html>
