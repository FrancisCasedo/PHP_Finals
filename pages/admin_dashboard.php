
<?php require_once "../config.php";
if (isset($_SESSION['student_number'])) {
    header("Location: ../login_page.php");
} else {
    session_start();
} ?>


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

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['AddParty'])) {
    $party_name = strip_tags($_POST['PartyName']);
    $sql = "INSERT INTO partylist (party_name) VALUES (:party_name)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':party_name' => $party_name]);
    header("Location: admin_dashboard.php?section=party");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_party'])) {
    $id = $_POST['id'];
    $party_name = strip_tags($_POST['PartyName']);
    $sql = "UPDATE partylist SET party_name=:party_name WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':party_name' => $party_name,
        ':id' => $id
    ]);
    header("Location: admin_dashboard.php?section=party");
    exit();
}

if (isset($_GET['delete_party'])) {
        $id = $_GET['delete_party'];

    try {
        $sql = "SELECT * FROM candidate WHERE partylist_id = :id";
        $stmt_check = $conn->prepare($sql);
        $stmt_check->execute([':id' => $id]);

        if ($stmt_check->rowCount() == 0) {
            $sql = "DELETE FROM partylist WHERE id = :id";
            $stmt_d_position = $conn->prepare($sql);
            $stmt_d_position->execute([':id' => $id]);
            header("Location: admin_dashboard.php?section=party");
            exit();
        } else {
            $_SESSION['partylist_exception'] = "Cannot delete this party list because it has associated candidates.";
            header("Location: admin_dashboard.php?section=party");
            exit();
        }

    } catch (Exception $e) {
        header("Location: admin_dashboard.php?section=party");
        exit();
    }
}

$edit_mode_party = false;
$edit_party = ['id' => '', 'party_name' => ''];

if (isset($_GET['edit_party'])) {
    $edit_mode_party = true;
    $id = $_GET['edit_party'];
    $sql = "SELECT * FROM partylist WHERE id=:id";
    $stmt_e_party = $conn->prepare($sql);
    $stmt_e_party->execute([':id' => $id]);
    $result_edit = $stmt_e_party->fetch(PDO::FETCH_ASSOC);
    if ($result_edit) {
        $edit_party = $result_edit;
    }
}

$stmt_party_list = $conn->prepare("SELECT * FROM partylist ORDER BY id ASC");
$stmt_party_list->execute();
$rows_party_list = $stmt_party_list->fetchAll(PDO::FETCH_ASSOC);
?>
<!----------------------- PARTY MANAGEMENT ------------------------->

<!----------------------- POSITIONS MANAGEMENT ------------------------->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_position'])) {
    $position_name = strip_tags($_POST['position_name']);
    $sql = "INSERT INTO positions (position_name) VALUES (:position_name)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':position_name' => $position_name]);
    header("Location: admin_dashboard.php?section=position");
}




if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_position'])) {
    $id = $_POST['id'];
    $position_name = strip_tags($_POST['position_name']);
    $sql = "UPDATE positions SET position_name=:position_name WHERE id=:id";
    var_dump($sql);
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':position_name' => $position_name,
        ':id' => $id
    ]);
    header("Location: admin_dashboard.php?section=position");
    exit();
}


if (isset($_GET['delete_position'])) {
    $id = $_GET['delete_position'];

    try {
        $sql = "SELECT * FROM candidate WHERE position_id = :id";
        $stmt_check = $conn->prepare($sql);
        $stmt_check->execute([':id' => $id]);

        if ($stmt_check->rowCount() == 0) {
            $sql = "DELETE FROM positions WHERE id = :id";
            $stmt_d_position = $conn->prepare($sql);
            $stmt_d_position->execute([':id' => $id]);
            header("Location: admin_dashboard.php?section=position");
            exit();
        } else {
            $_SESSION['position_exception'] = "Cannot delete this position because it has associated candidates.";
            header("Location: admin_dashboard.php?section=position");
            exit();
        }

    } catch (Exception $e) {
        header("Location: admin_dashboard.php?section=position");
        exit();
    }
}


if(isset($_POST['position_exception'])) {
    $delete_partylist_exception = false;
    header("Location: admin_dashboard.php?section=position");
    exit();
}

$edit_mode_position = false;
$edit_position = ['id' => '', 'position_name' => ''];

if (isset($_GET['edit_position'])) {
    $edit_mode_position = true;
    $id = $_GET['edit_position'];
    $sql = "SELECT * FROM positions WHERE id=:id";
    $stmt_e_position = $conn->prepare($sql);
    $stmt_e_position->execute([':id' => $id]);
    $result_edit = $stmt_e_position->fetch(PDO::FETCH_ASSOC);
    if ($result_edit) {
        $edit_position = $result_edit;
    }
}

$stmt_positions = $conn->prepare("SELECT * FROM positions ORDER BY id ASC");
$stmt_positions->execute();
$rows_positions = $stmt_positions->fetchAll(PDO::FETCH_ASSOC);
?>
<!----------------------- POSITIONS MANAGEMENT ------------------------->

<!----------------------- CANDIDATE MANAGEMENT ------------------------->
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_candidate'])) {
    $candidate_name = strip_tags($_POST['candidate_name']);
    $partylist_id = strip_tags($_POST['candidate_partylist']);
    $position_id = strip_tags($_POST['candidate_position']);

    $sql = "INSERT INTO candidate (candidate_name, partylist_id, position_id) VALUES (:candidate_name, :partylist_id, :position_id)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':candidate_name' => $candidate_name,
        ':partylist_id' => $partylist_id,
        ':position_id' => $position_id
    ]);
    header("Location: admin_dashboard.php?section=candidate");
    exit();
}

$edit_mode_candidate = false;
$edit_candidate = ['id' => '', 'candidate_name' => '', 'partylist_id' => '', 'position_id' => ''];

if (isset($_GET['edit_candidate'])) {
    $edit_mode_candidate = true;
    $id = $_GET['edit_candidate'];
    $sql = "SELECT * FROM candidate WHERE id=:id";
    $stmt_e_candidate = $conn->prepare($sql);
    $stmt_e_candidate->execute([':id' => $id]);
    $result_edit = $stmt_e_candidate->fetch(PDO::FETCH_ASSOC);
    if ($result_edit) {
        $edit_candidate = $result_edit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_candidate'])) {
    $id = strip_tags($_POST['id']);
    $candidate_name = strip_tags($_POST['candidate_name']);
    $partylist_id = strip_tags($_POST['candidate_partylist']);
    $position_id = strip_tags($_POST['candidate_position']);


    $sql = "UPDATE candidate SET candidate_name=:candidate_name, partylist_id=:partylist_id, position_id=:position_id WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':candidate_name' => $candidate_name,
        ':partylist_id' => $partylist_id,
        ':position_id' => $position_id,
        ':id' => $id
    ]);
    header("Location: admin_dashboard.php?section=candidate");
    exit();
}

if (isset($_GET['delete_candidate'])) {

    $id = $_GET['delete_candidate'];
    $sql = "DELETE FROM candidate WHERE id=:id";
    $stmt_d_candidate = $conn->prepare($sql);
    $stmt_d_candidate->execute([':id' => $id]);
    header("Location: admin_dashboard.php?section=candidate");
    exit();
}

$stmt_candidate = $conn->prepare("SELECT * FROM candidate ORDER BY id ASC");
$stmt_candidate->execute();
$rows_candidate = $stmt_candidate->fetchAll(PDO::FETCH_ASSOC);
?>

<!----------------------- CANDIDATE MANAGEMENT ------------------------->

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
    header("Location: admin_dashboard.php?section=voters");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_voter'])) {
    $id = $_POST['id'];
    $student_no = $_POST['student_no'];
    $fname = $_POST['fname'];
    $mi = $_POST['mi'];
    $lastname = $_POST['lastname'];
    $course = $_POST['course'];
    $VoterPassword = $_POST['voter_password'];

    $sql = "UPDATE voters SET student_number=:student_no, first_name=:fname, middle_initial=:mi, last_name=:lastname, Course=:course, VoterPassword =:VoterPassword WHERE id=:id";
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
    header("Location: admin_dashboard.php?section=voters");
    exit();
}



if (isset($_GET['delete_voter'])) {
    $id = $_GET['delete_voter'];
    $sql = "DELETE FROM voters WHERE ID=:id";
    $stmt_d_voter = $conn->prepare($sql);
    $stmt_d_voter->execute([':id' => $id]);
    header("Location: admin_dashboard.php?section=voters");
}


$edit_mode_voter = false;
$edit_voter = ['ID' => '', 'student_no' => '', 'fname' => '', 'mi' => '', 'lastname' => '', 'course' => '', 'VoterPassword' => ''];

if (isset($_GET['edit_voter'])) {
    $edit_mode_voter = true;
    $id = $_GET['edit_voter'];
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
function AddParty() {
    global $rows_party_list, $edit_mode_party, $edit_party;
    ?>
<div class="RightHeader">
    <h4><?php echo $edit_mode_party ? 'Edit Party' : 'Add Party'; ?></h4>
</div>
<hr class="divider">
<?php if (isset($_SESSION['partylist_exception'])) { ?>
    <div class="error-message">
        <h1><?php echo htmlspecialchars($_SESSION['partylist_exception']); ?></h1>
        <form action="" method="POST">
            <button type="submit" name="partylist_exception">Back</button>
        </form>
    </div>
    <?php 
    unset($_SESSION['partylist_exception']);
    ?>
<?php }else {?>
    <form action="" method="post">
        <input type="hidden" name="id" value="<?php echo isset($edit_party['id']) ? $edit_party['id'] : ''; ?>">

        <label for="PartyName">Party name</label><br>
        <input type="text" name="PartyName" value="<?php echo isset($edit_party['party_name']) ? $edit_party['party_name'] : ''; ?>" required><br>

        <?php if ($edit_mode_party): ?>
            <button type="submit" name="update_party">Update</button>
            <a href="admin_dashboard.php?section=party">Cancel</a>
        <?php else: ?>
            <button type="submit" name="AddParty">Add</button>
        <?php endif; ?>
    </form>
        <?php }?>
<div class="partytable-container">
    <table name="partytable">
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
                <a href="?edit_party=<?php echo $row['id']; ?>">Edit</a> |
                <a href="?delete_party=<?php echo $row['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
            <td colspan="3">No parties added yet.</td>
        </tr>
        <?php } ?>
    </table>
</div>
<?php } ?>

<?php
function AddPosition()
{
    global $conn, $rows_positions, $edit_mode_position, $edit_position;
    ?>
<div class="positionRightHeader">
    <h4><?php echo 'Add Position'; ?></h4>
</div>

<hr class="divider">
<?php if (isset($_SESSION['position_exception'])) { ?>
    <div class="error-message">
        <h1><?php echo htmlspecialchars($_SESSION['position_exception']); ?></h1>
        <form action="" method="POST">
            <button type="submit" name="position_exception">Back</button>
        </form>
    </div>
    <?php
    unset($_SESSION['position_exception']);
    ?>
<?php }else {?>
<form method="post" action="">
    <input type="hidden" name="id" value="<?php echo isset($edit_position['id']) ? $edit_position['id'] : ''; ?>">
    <label>Position Name:</label><br>
    <input type="text" name="position_name" value = "<?php $edit_position['position_name'] ?>"required><br>

        <?php if ($edit_mode_position): ?>
        <input type="submit" name="update_position" value="update_position">
        <a href="admin_dashboard.php?section=position">Cancel</a>
        <?php else: ?>
        <input type="submit" name="add_position" value="Add_position">
        <?php endif; ?>
</form>
<?php } ?>
<div class="positiontable-container">
    <table name="positiontable">
        <tr>
            <th>ID</th>
            <th>Position Name</th>
            <th>Action</th>
        </tr>
        <?php
        if (count($rows_positions) > 0) {
            foreach ($rows_positions as $row) {
                echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['position_name']}</td>
                            <td>
                                <a href='?edit_position={$row['id']}'>Edit</a> |
                                <a href='?delete_position={$row['id']}' >Delete</a>
                            </td>
                        </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No positions added yet.</td></tr>";
        }
        ?>
    </table>
</div>
<?php } ?>

<?php
function AddCandidate()
{
    global $conn, $rows_candidate, $rows_positions, $rows_party_list, $edit_candidate, $edit_mode_candidate;
    ?>
<div class="candidateRightHeader">
    <h4><?php echo 'Add Candidate'; ?></h4>
</div>
<div class="add-voter-form-wrapper">
<form method="post" action="">
    <input type="hidden" name="id" value="<?php echo isset($edit_candidate['id']) ? $edit_candidate['id'] : ''; ?>">
    <label>Candidate Name:</label><br>
    <input type="text" name="candidate_name" value="<?php echo $edit_candidate['candidate_name'] ?? ''; ?>" required><br>
    <label>Partylist:</label><br>
    <select name="candidate_partylist" id="candidate_partylist" required>
        <option value="" disabled selected>Select a Partylist</option>
        <?php foreach ($rows_party_list as $partylist) { ?>
            <option value="<?php echo $partylist['id']; ?>" <?php if ($edit_candidate['partylist_id'] == $partylist['id']) echo 'selected'; ?>>
                <?php echo $partylist['party_name']; ?>
            </option>
        <?php } ?>
    </select><br>
    <label>Position:</label><br>
    <select name="candidate_position" id="candidate_position" required>
        <option value="" disabled selected>Select a Position</option>
        <?php foreach ($rows_positions as $position) { ?>
            <option value="<?php echo $position['id']; ?>" <?php if ($edit_candidate['position_id'] == $position['id']) echo 'selected'; ?>>
                <?php echo $position['position_name']; ?>
            </option>
        <?php } ?>
    </select><br>
    <?php if ($edit_mode_candidate): ?>
        <input type="submit" name="update_candidate" value="Update Candidate">
        <a href="admin_dashboard.php?section=candidate">Cancel</a>
    <?php else: ?>
        <input type="submit" name="add_candidate" value="Add Candidate">
    <?php endif; ?>
</form>
</div>

<?php
        $partylist_lookup = [];

        $stmt_partylist = $conn->prepare("SELECT id, party_name FROM partylist");
        $stmt_partylist->execute();
        foreach ($stmt_partylist->fetchAll(PDO::FETCH_ASSOC) as $party) {
            $partylist_lookup[$party['id']] = $party['party_name'];
        }

        $position_lookup = [];
        $stmt_positions = $conn->prepare("SELECT id, position_name FROM positions");
        $stmt_positions->execute();

        foreach ($stmt_positions->fetchAll(PDO::FETCH_ASSOC) as $pos) {
            $position_lookup[$pos['id']] = $pos['position_name'];
        }
        ?>
<div class="candidatetable-container">

    <table name="candidatetable">

        <tr>
            <th>ID</th>
            <th>Candidate Name</th>
            <th>Party list</th>
            <th>Position</th>
            <th>Action</th>

        </tr>

        <?php
        if (count($rows_candidate) > 0) {

            foreach ($rows_candidate as $row) {
                
                $party_name = isset($partylist_lookup[$row['partylist_id']]) ? $partylist_lookup[$row['partylist_id']] : 'Unknown';

                $position_name = isset($position_lookup[$row['position_id']]) ? $position_lookup[$row['position_id']] : 'Unknown';

                echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['candidate_name']}</td>
                <td>{$party_name}</td>
                <td>{$position_name}</td>
                <td>
                    <a href='?edit_candidate={$row['id']}'>Edit</a> |
                    <a href='?delete_candidate={$row['id']}' >Delete</a>
                </td>
            </tr>";
            }
        }

        ?>
    </table>
    <?php } ?>

    <?php
    function AddVoters()
    {
        global $edit_mode_voter, $edit_voter, $rows_voters, $delete_voter_exception_1, $delete_voter_exception_2;
        ?>
        <div class="positionRightHeader">
    <h4><?php echo 'Add Voters'; ?></h4>
</div>
    <h1>
        <?php echo $edit_mode_voter ? "Edit voter" : ""; ?>
    </h1>

    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo isset($edit_voter['id']) ? $edit_voter['id'] : ''; ?>">

        <label>Student Number:</label><br>
        <input type="text" name="student_no" value="<?php echo isset($edit_voter['student_no']) ? $edit_voter['student_no'] : '' ?>" required><br>

        <label>First Name:</label><br>
        <input type="text" name="fname" value="<?php echo  isset($edit_voter['fname']) ? $edit_voter['fname'] : '' ?>" required><br>

        <label>Middle Initial:</label><br>
        <input type="text" name="mi" maxlength="1" value="<?php echo isset($edit_voter['mi']) ? $edit_voter['mi'] : '' ?>" required><br>

        <label>Last Name:</label><br>
        <input type="text" name="lastname" value="<?php echo isset($edit_voter['lastname']) ?$edit_voter['lastname']: '' ?>" required><br>

        <label>Course:</label><br>
        <input type="text" name="course" value="<?php echo isset($edit_voter['course']) ? $edit_voter['course'] : '' ?>" required><br>

        <label>Password:</label><br>
        <input type="text" name="voter_password" value="<?php isset($edit_voter['VoterPassword']) ? $edit_voter['VoterPassword']: '' ?>" required><br>

        <?php if ($edit_mode_voter): ?>
        <input type="submit" name="update_voter" value="update_voter">
        <a href="admin_dashboard.php?section=voters">Cancel</a>

        <?php else: ?>
        <input type="submit" name="add_voter" value="Add_voter">
        <?php endif; ?>
    </form>
    </

    <div class="add-voter-form-wrapper">
    <div class="votertable-container">
        <table name="votertable">
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
            if (count($rows_voters) > 0) {
                $i = 1;
                foreach ($rows_voters as $row) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['student_number']}</td>
                            <td>{$row['first_name']}</td>
                            <td>{$row['middle_initial']}</td>
                            <td>{$row['last_name']}</td>
                            <td>{$row['Course']}</td>
                            <td>{$row['VoterPassword']}</td>
                            <td>
                                <a href='?edit_voter={$row['id']}'>Edit</a> |
            <a href='?delete_voter={$row['id']}' >Delete</a>
                            </td>
                        </tr>";
                    $i++;
                }
            }
            ?>
        </table>
</div>
</div>
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
            <div class="left">
                <div class="left-header">
                    <p name="Welcome">Welcome to the admin dashboard.</p>
                </div>
                <div class="selection">
                    <form method="get" action="">

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
                </form>
            </div>
            <div class="right">
                <?php
                $section = $_GET['section'] ?? '';

                if (isset($_GET['edit_candidate'])) {
                    $section = 'candidate';
                }
                if (isset($_GET['edit_voter'])) {
                    $section = 'voters';
                }
                if (isset($_GET['edit_position'])) {
                    $section = 'position';
                }
                if (isset($_GET['edit_party'])) {
                    $section = 'party';
                }

                if ($section === 'party' || isset($_GET['btn1']) ) {
                    AddParty();
                } elseif ($section === 'position' || isset($_GET['btn2'])) {
                    AddPosition();
                } elseif ($section === 'candidate' || isset($_GET['btn3'])) {
                    AddCandidate();
                } elseif ($section === 'voters' || isset($_GET['btn4']) ) {
                    AddVoters();
                }
                ?>
            </div>
        </div>
        <?php } ?>
    </body>

    </html>

    <?php main(); ?>