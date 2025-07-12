<?php require_once "../config.php" ?>

<?php
if (!isset($rows_party_list) || !is_array($rows_party_list)) {
    $rows_party_list = [];
}

if (!isset($rows_candidate) || !is_array($rows_candidate)) {
    $rows_candidate = [];
}

if (!isset($rows_positions) || !is_array($rows_positions)) {
    $rows_positions = [];
}

if (!isset($rows_voters) || !is_array($rows_voters)) {
    $rows_voters = [];
}
?>

<!----------------------- PARTY MANAGEMENT ------------------------->

<?php if (isset($_POST["AddParty"])) {
    $partyName = $_POST["PartyName"];
    if (!empty($partyName)) {
        $stmt = $conn->prepare("INSERT INTO party_list (party_name) VALUES (:PartyName)");
        $stmt->bindParam(':PartyName', $partyName);
        if ($stmt->execute()) {
            $stmt = $conn->prepare("SELECT * FROM party_list");
            $stmt->execute();
            $rows_party_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_POST['btn1'] = true;
        } else {
            echo "Error adding party.";
        }
    } else {
        echo "Party name cannot be empty.";
    }
} ?>

<!----------------------- PARTY MANAGEMENT ------------------------->

<!----------------------- VOTER MANAGEMENT ------------------------->

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
    $_POST['btn4'] = true;
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
    $_POST['btn4'] = true;
    exit();
}

if (isset($_GET['delete_voter'])) {
    $id = $_GET['delete_voter'];
    $sql = "DELETE FROM voters WHERE ID=:id";
    $stmt_d_voter = $conn->prepare($sql);
    $stmt_d_voter->execute([':id' => $id]);
    $_POST['btn4'] = true;
}

$edit_mode_voter = false;
$edit_voter = ['ID' => '', 'student_no' => '', 'fname' => '', 'mi' => '', 'lastname' => '', 'course' => '', 'VoterPassword' => ''];

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

$stmt_voters = $conn->prepare("SELECT * FROM voters ORDER BY ID ASC");
$stmt_voters->execute();
$result_voters = $stmt_voters->fetchAll(PDO::FETCH_ASSOC);
?>

<!----------------------- VOTER MANAGEMENT ------------------------->












<?php
function button1Action()
{
    global $rows_party_list;
    ?>
    <div class="RightHeader">
        <h4>Add party</h4>
    </div>
    <div class="AddParty">
        <form action="" method="post">
            <label for="PartyName">Party name</label><br>
            <input type="text" name="PartyName">
            <button type="submit" name="AddParty">Add</button>
        </form>
    </div>
    <div class="PartyList">
        <table name="table">
            <tr>
                <th>Party ID</th>
                <th>Party Name</th>
                <th name="action">Action</th>
            </tr>
            <?php if (count($rows_party_list) > 0) {
                foreach ($rows_party_list as $row) { ?>
                    <tr>
                        <td name="PartyID">
                            <p><?php echo $row["id"] ?></p>
                        </td>
                        <td name="PartyName">
                            <p><?php echo $row["party_name"] ?></p>
                        </td>
                        <td name="action">
                            <form action="" method="post">
                                <button type="submit" name="delete">Delete</button>
                                <button type="submit" name="edit">Edit</button>
                            </form>
                        </td>
                    <?php } ?>
                <?php } ?>
            </tr>
    </div>
<?php } ?>

<?php
function button2Action()
{ ?>
    <form action="">
        <h1>Hello</h1>
        <h1>Form 1</h1>
        <input type="text">
        <button type="submit">Add</button>
    </form>
    <form action="">
        <h1>Form 2</h1>
        <input type="text">
        <button type="submit">Add</button>
        <h1>pooks</h1>
    </form>
<?php }
function button3Action()
{ ?>
    <form action="">
        <h1>Form 1</h1>
        <input type="text">
        <button type="submit">Add</button>
    </form>
    <form action="">
        <h1>Form 2</h1>
        <input type="text">
        <button type="submit">Add</button>
    </form>
<?php } ?>

<?php
function button4Action()
{
    global $edit_mode_voter, $edit_voter, $result_voters;
    ?>
    <h1><?php echo $edit_mode_voter ? "Edit voter" : "Add Voter"; ?></h1>
        <hr class="divider">
        <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo isset($edit_voter['id']) ? $edit_voter['id'] : ''; ?>">
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
        <table name = "table">
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
            if (count($result_voters) > 0) {
                $i = 1;
            foreach ($result_voters as $row) {
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
        }
            ?>
        </table>
<?php } ?>
<?php
function main()
{ ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./assets/admin_style.css">
        <title>Document</title>
    </head>

    <body>

        <div class="container">
            `<div class="left">
                <div class="left-header">
                    <p name="Welcome">Welcome to the admin dashboard.</p>
                    <!-- icon dapat nandito-->
                </div>
                <div class="selection">
                    <form method="post" action="">

                        <button type="submit" name="btn1">
                            <p>Add Party</p>
                        </button><br>
                        <button type="submit" name="btn2">
                            <p>Add Position</p>
                        </button><br>
                        <button type="submit" name="btn3">
                            <p>Add Candidate</p>
                        </button><br>
                        <button type="submit" name="btn4">
                            <p>Add Voters</p>
                        </button><br>
                        <button type="submit" name="btn5">
                            <p>Results</p>
                        </button><br>
                </div>
                <button type="submit" name="btn6">Delete Database</button>
                </form>`
            </div>
            <div class="right">
                <?php
                if (isset($_POST['btn1'])) {
                    button1Action();
                } elseif (isset($_POST['btn2'])) {
                    echo "<h1>Add Position</h1>";
                    button2Action();
                } elseif (isset($_POST['btn3'])) {
                    echo "<h1>Add Candidate</h1>";
                    button3Action();
                } elseif (isset($_POST['btn4'])) {
                    button4Action();
                }
                ?>
            </div>
        </div>
    <?php } ?>
</body>

</html>

<?php main(); ?>