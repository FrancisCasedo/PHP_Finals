<?php
require_once('config/config.php');
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
     <h1>Welcome <?php echo $_SESSION['fname'] . "  " . $_SESSION['lname'];?></h1>
    <?php
       if($_SERVER['REQUEST_METHOD'] == 'POST')
       {
            extract($_POST);//extracting post;
            //mysqli_query();
            //Create a query for login using Select statement
            $query=$db->query("SELECT `id`, `uname`, `upass`, `fname`, `lname`, `emailaddress`, `status`, `dateCreated`, `userlevel` FROM `tbluserfile` WHERE  uname='$txtUname' AND upass=sha1('$txtUpass')");
            //Execute query
            $executeQuery = $query->fetch_assoc();

           /// $fruits= array[1=>"Apple",2=>"Banana"];
           // %fruits[2]; = Banana

            if($query->num_rows > 0) //if the result of the query is  greater 0// count records
            {
                $_SESSION['fname'] = $executeQuery['fname'];// To make fname available in all pages;
                $_SESSION['lname']=$executeQuery['lname'];
                header('location: dashboard.php');
            }
            else{
                echo "Invalid Credentials";
            }


       }

     ?>

  <div class="login-container">
    <h2>Login</h2>
    <form action="" method="post">
      <input type="text" placeholder="Username" name="txtUname" required>
      <input type="password" placeholder="Password" name="txtUpass" required>
      <button type="submit" name="btnsubmit">Login</button>
      <div class="extra">
        <p><a href="#">Forgot password?</a></p>
        <p><a href="#">Create an account</a></p>
      </div>
    </form>
  </div>

</body>
</html>


EDIT

<?php
require_once('config/config.php');
session_start();


$getIDFromURL= $_GET['updateID'];
$query=$db->query("SELECT * FROM tbluserfile WHERE id='$getIDFromURL'");
$executeQuery=$query->fetch_assoc();


//UPDATE
if(isset($_POST['btnUpdate'])){
    extract($_POST);
    $queryUpdate=$db->query("UPDATE `tbluserfile` SET `uname`='$txtUname',`upass`=sha1('$txtUpass'),`fname`='$txtFname',`lname`='$txtLname',`emailaddress`='$txtEmailAddress',`userlevel`='$userlevel' WHERE id = $getIDFromURL");
    header('location: dashboard.php');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDIT</title>
</head>
<body>
     <h1>Welcome <?php echo $_SESSION['fname'] . "  " . $_SESSION['lname'];?></h1>
    <h1>Edit Record</h1>
     <form method="post">
        <label for="username">Username:
            <input type="text" name="txtUname" value="<?php echo $executeQuery['uname'] ?>" required placeholder="Username">
        </label>
        <br>
         <label for="password">Password:
            <input type="password" name="txtUpass" value="<?php echo $executeQuery['upass'] ?>" required placeholder="Password">
        </label>
        <br>
        <label for="Fname">Firstname:
            <input type="text" name="txtFname" value="<?php echo $executeQuery['fname'] ?>" required placeholder="First Name">
        </label>
        <br>
          <label for="Lname">Lastname:
            <input type="text" name="txtLname" value="<?php echo $executeQuery['lname'] ?>" required placeholder="Last Name">
           <br>
          <label for="Lname">Email address:
            <input type="text" name="txtEmailAddress" value="<?php echo $executeQuery['emailaddress'] ?>" required placeholder="Email Address">
        </label>
          <br>Userlevel:
          <select name="userlevel">
                <option value="<?php echo $executeQuery['userlevel'] ?>"><?php echo $executeQuery['userlevel'] ?></option>
            <option value="Administrator">Administrator</option>
             <option value="Student">Student</option>
          </select>
          <br>
          <input type="submit" value="Update Record" name="btnUpdate">
  </form>
</body>
</html>

DELETE


<?php
require_once('config/config.php');
session_start();


$getIDFromURL= $_GET['deleteID'];
$query=$db->query("SELECT * FROM tbluserfile WHERE id='$getIDFromURL'");
$executeQuery=$query->fetch_assoc();


    $db->query("DELETE FROM tbluserfile WHERE id = $getIDFromURL");
    header('location: dashboard.php');



?>


DASHBAORD

<?php
require_once('config/config.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
     <h1>Welcome <?php echo $_SESSION['fname'] . "  " . $_SESSION['lname'];?></h1>
<table border="1">
    <tr>
        <th>User ID</th>
        <th>User Name</th>
        <th>Password</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email Address</th>
        <th>Status</th>
        <th>Date Created</th>
        <th>User Level</th>
        <th>Action</th>
    </tr>

    <?php
        $query=$db->query("SELECT `id`, `uname`, `upass`, `fname`, `lname`, `emailaddress`, `status`, `dateCreated`, `userlevel` FROM `tbluserfile`");

        while($executeQuery=$query->fetch_assoc())
        {
       ?>

       <tr>
            <td><?php echo $executeQuery['id']; ?></td>
            <td><?php echo $executeQuery['uname']; ?></td>
            <td><?php echo $executeQuery['upass']; ?></td>
           <td><?php echo $executeQuery['fname']; ?></td>
           <td><?php echo $executeQuery['lname']; ?></td>
           <td><?php echo $executeQuery['emailaddress']; ?></td>
           <td><?php echo $executeQuery['status']; ?></td>
           <td><?php echo $executeQuery['dateCreated']; ?></td>
           <td><?php echo $executeQuery['userlevel']; ?></td>
           <td><a href="edit.php?updateID=<?php echo $executeQuery['id']; ?>">EDIT</a> | <a href="delete.php?deleteID=<?php echo $executeQuery['id']; ?>">DELETE</a> </td>


       </tr>

       <?php } ?>
</table>

<br>
<br>

<?php

 if(isset($_POST['btnSave'])){

    extract($_POST);//= $txt = $_POST['txtUname];
    $queryInsert =$db->query("INSERT INTO `tbluserfile`(`uname`, `upass`, `fname`, `lname`, `emailaddress`, `userlevel`) VALUES ('$txtUname',sha1('$txtUpass'),'$txtFname','$txtLname','$txtEmailAddress','$userlevel')");
    echo "Record Saved";
}

?>
<h1>Add New Record</h1>
  <form method="post">
        <label for="username">Username:
            <input type="text" name="txtUname" value="" required placeholder="Username">
        </label>
        <br>
         <label for="password">Password:
            <input type="password" name="txtUpass" value="" required placeholder="Password">
        </label>
        <br>
        <label for="Fname">Firstname:
            <input type="text" name="txtFname" value="" required placeholder="First Name">
        </label>
        <br>
          <label for="Lname">Lastname:
            <input type="text" name="txtLname" value="" required placeholder="Last Name">
           <br>
          <label for="Lname">Email address:
            <input type="text" name="txtEmailAddress" value="" required placeholder="Email Address">
        </label>
          <br>Userlevel:
          <select name="userlevel">
            <option value="Administrator">Administrator</option>
             <option value="Student">Student</option>
          </select>
          <br>
          <input type="submit" value="Save Record" name="btnSave">
  </form>
</body>
</html>

config


<?php

    //Constant Variable

    define("MYSQL_HOSTNAME","localhost");
    define("MYSQL_USERNAME","root");
    define("MYSQL_PASSWORD","");
    define("MYSQL_DATABASE","demo_database");

   $db = new mysqli(MYSQL_HOSTNAME,MYSQL_USERNAME,MYSQL_PASSWORD,MYSQL_DATABASE);

//check the connection
    if($db->connect_error){
        die("Connection Failed" .$db->connect_error);
    }

?>

DATABASE


-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2025 at 12:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demo_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbluserfile`
--

CREATE TABLE `tbluserfile` (
  `id` tinyint(4) NOT NULL COMMENT 'This a password',
  `uname` varchar(25) NOT NULL,
  `upass` varchar(255) NOT NULL,
  `fname` varchar(25) NOT NULL,
  `lname` varchar(25) NOT NULL,
  `emailaddress` varchar(80) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT 1,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `userlevel` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluserfile`
--

INSERT INTO `tbluserfile` (`id`, `uname`, `upass`, `fname`, `lname`, `emailaddress`, `status`, `dateCreated`, `userlevel`) VALUES
(5, 'admin', '86f7e437faa5a7fce15d1ddcb9eaeaea377667b8', 'a', 'a', 'FEU@FEU.com', 1, '2025-06-17 10:05:21', 'Administrator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbluserfile`
--
ALTER TABLE `tbluserfile`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbluserfile`
--
ALTER TABLE `tbluserfile`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT 'This a password', AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

