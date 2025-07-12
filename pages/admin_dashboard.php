
<?php require_once "../config.php" ?>


<?php if (isset($_POST["AddParty"])) {
    $partyName = $_POST["PartyName"];
    if (!empty($partyName)) {
        $stmt = $conn->prepare("INSERT INTO party_list (party_name) VALUES (:PartyName)");
        $stmt->bindParam(':PartyName', $partyName);
        if ($stmt->execute()) {
            $stmt = $conn->prepare("SELECT * FROM party_list");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_POST['btn1'] = true;
        } else {
            echo "Error adding party.";
        }
    } else {
        echo "Party name cannot be empty.";
    }
} ?>

<?php
function button1Action()
{
    global $rows;
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
            <?php if (count($rows) > 0) {
                foreach ($rows as $row) { ?>
                    <tr>
                        <td name="PartyID">
                            <p><?php echo $row["party_id"] ?></p>
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
<?php }
function button4Action()
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
                    echo "<h1>Add Party</h1>";
                    button4Action();
                }
                ?>
            </div>
        </div>
    <?php } ?>
</body>

</html>

<?php main(); ?>