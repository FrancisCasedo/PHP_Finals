<?php
function rightHeader()
{
    ?>
    <div class="right-header">
        //icon para sa dashboard
        <h1>Admin Dashboard</h1>
    </div>
    <?php
}
function button1Action()
{ ?>
<div>
    <h4>Add party</h4>
</div>
<div class = "AddParty">
    <form action="">
        <h1>Party name</h1>
        <input type="text">
        <button type="submit">Add</button>
        <h1></h1>
    </form>
</div>
<div class = "PartyList">
    <h4 id = "PartyId">Party ID</h4>
    <h4 id = "PartyName">Party Name</h4>
    <h4 id = "Action">Action</h4>
</div>
<?php 
//Placeholder for what shows up when partylist is added or exists already
// if partylist exists
/*<div class = "PartyList">
     <h4 id = "PartyId">1</h4>
     <h4 id = "PartyName">Party 1</h4>
     <h4 id = "Action"><button>Delete</button></h4>
*/?>
<?php }
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
<?php }
function main()
{ ?>
    <div class="container">
        <form method="post">
            <div class="left">
                <div class="left-header">
                    //icon para sa dashboard
                    <h1>Tools</h1>
                </div>
                <div>
                    <p>Welcome to the admin dashboard. Please select an action below:</p>
                    <button type="submit" name="btn1">Add Party</button>
                    <button type="submit" name="btn2">Add Position</button>
                    <button type="submit" name="btn3">Add Candidate</button>
                    <button type="submit" name="btn4">Add Voters</button>
                    <button type="submit" name="btn5">Results</button>
                </div>
                <button type="submit" name="btn4">Delete Database</button>
            </div>
        </form>
        <div class="right">
            <?php
            rightHeader();
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

<?php main() ?>