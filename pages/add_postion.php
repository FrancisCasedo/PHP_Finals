<?php
if (isset($_POST["AddPosition"])) {
    $positionName = $_POST["PositionName"];
    if (!empty($positionName)) {
        $stmt = $conn->prepare("INSERT INTO position (Position Name) VALUES (:PositionName)");
        $stmt->bindParam(':PositionName', $positionName);
        if ($stmt->execute()) {
            $stmt = $conn->prepare("SELECT * FROM position");
            $stmt->execute();
            $positionRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_POST['btn2'] = true;
        } else {
            echo "Error adding position.";
        }
    } else {
        echo "Position name cannot be empty.";
    }
}
?>

<?php
function button2Action()
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM position");
    $stmt->execute();
    $positionRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="RightHeader">
        <h4>Add position</h4>
        <hr>
    </div>

    <div class="AddPosition">
        <form action="" method="post">
            <label for="PositionName">Position name</label><br>
            <input type="text" name="PositionName">
            <button type="submit" name="AddPosition">Add</button>
        </form>
    </div>

    <br><br>

    <div class="PositionList">
        <table style="border-collapse: collapse; width: 100%; text-align: center;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="padding: 10px; border: 1px solid #ccc;">ID</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Position</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($positionRows) > 0): ?>
                    <?php foreach ($positionRows as $row): ?>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $row["Position ID"]; ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $row["Position Name"]; ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <form action="" method="post" style="display: inline;">
                                    <input type="hidden" name="edit_position_id" value="<?php echo $row["Position ID"]; ?>">
                                    <button type="submit" name="editPosition">Edit</button>
                                </form>
                                <form action="" method="post" style="display: inline;">
                                    <input type="hidden" name="delete_position_id" value="<?php echo $row["Position ID"]; ?>">
                                    <button type="submit" name="deletePosition">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="padding: 10px;">No positions added yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php }

button2Action();
?>
