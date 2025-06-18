<?php

function button1Action() { ?>
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
function button2Action() { ?>
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
function button3Action() { ?>
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
function button4Action() { ?>
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
                <button type="submit" name="btn1">Button 1</button>
                <button type="submit" name="btn2">Button 2</button>
                <button type="submit" name="btn3">Button 3</button>
                <button type="submit" name="btn4">Button 4</button>
                <button type="submit" name="btn5">Button 5</button>
            </div>
        </form>
        <div class="right">
            <?php
            if (isset($_POST['btn1'])) {
                button1Action();
            }elseif (isset($_POST['btn2'])) {
                button2Action();
            } elseif (isset($_POST['btn3'])) {
                button3Action();
            } elseif (isset($_POST['btn4'])) {
                button4Action();
            }
            ?>
        </div>
    </div>
<?php }?>

<?php main() ?>