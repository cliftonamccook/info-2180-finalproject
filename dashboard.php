<?php 
session_start();

if (!isset($_SESSION["username"], $_SESSION["password"])) {
    header("Location: index.html");
    die();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title >Dashboard</title>
    <link rel="stylesheet" href="ui.css">
    <script src="ui.js"></script>
</head>
<body>
    <div id="container">
    <header>
        <img id="bug-icon" src="icons/bug.png" alt="bug icon">
        <p>BugMe Issue Tracker</p>
    </header>
    <nav>
        <div>
            <a href="">
                <div id="home">
                    <img src="icons/home-7-32.png">
                    <p>Home</p>
                </div>
            </a>
        </div>
        <div <?php if($_SESSION["username"] !== "admin@project2.com"){echo'style="display:none;"';}?>>
            <a href="control.php">
                <div id="add-user">
                    <img src="icons/add-user-2-32.png">
                    <p>Add User</p>
                </div>
            </a>
        </div>
        <div <?php if($_SESSION["username"] !== "admin@project2.com"){echo'style="display:none;"';}?>>
            <a href="control.php">
                <div id="issue">
                    <img src="icons/plus-4-32.png">
                    <p>New Issue</p>
                </div>
            </a>
        </div>
        <div>
            <a href="logout.php">
                <div id="logout">
                    <img src="icons/power-32.png">
                    <p>Logout</p>
                </div>
            </a>
        </div>
    </nav>
    <main id="content">
        <?php include('home.php');?>    
    </main> 
    </div>   
</body>
</html>
