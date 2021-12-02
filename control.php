<?php

session_start();

require("dbconnect.php");

if(isset($_SESSION["username"], $_SESSION["password"])) {
    // user is already logged in
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST["formname"])) {
            switch($_POST["formname"]) {
                case "new-user":
                    // sanitize!
                    $fname = strip_tags($_POST["firstname"]);
                    $lname = strip_tags($_POST["lastname"]);
                    $pword = password_hash(strip_tags($_POST["password"]), PASSWORD_DEFAULT);
                    $email = strip_tags($_POST["email"]);
                    try{
                        $query = "INSERT INTO `users` VALUES(NULL, :fname, :lname, :pword, :email, NOW())";
                        $sql = $conn->prepare($query);
                        $sql->bindValue(':fname', $fname, PDO::PARAM_STR);
                        $sql->bindValue(':lname', $lname, PDO::PARAM_STR);
                        $sql->bindValue(':pword', $pword, PDO::PARAM_STR);
                        $sql->bindValue(':email', $email, PDO::PARAM_STR);
                        $sql->execute();
                        $conn = null;
                        header("Location: home.php"); 
                        exit();
                    }catch(PDOException $error){
                        echo "Database connection failed<br><br>".$pe->getMessage();
                    }
                    break;
                case "new-issue":
                    // sanitize!
                    $title = strip_tags($_POST["title"]);
                    $description = strip_tags($_POST["description"]);
                    $type = strip_tags($_POST["type"]);
                    $priority = strip_tags($_POST["priority"]);
                    $assigned_to = strip_tags($_POST["assigned-to"]);
                    try{
                        
                        // find user with first name and lastname in database and return their id to assigned_to
                        $pieces = explode(" ",$assigned_to);
                        $fn = $pieces[0];
                        $ln = $pieces[1];
                        $check = "SELECT * FROM `users` WHERE firstname=:fn AND lastname=:ln";
                        $person = $conn->prepare($check);
                        $person->bindValue(':fn', $fn, PDO::PARAM_STR);
                        $person->bindValue(':ln', $ln, PDO::PARAM_STR);
                        $person->execute();
                        $row = $person->fetch(PDO::FETCH_ASSOC);
                        $id = $row["id"];
                        $assigned_to = $id;
                        // echo var_dump($assigned_to);
                        // exit();
                    }catch(PDOException $e){
                        echo "Something went wrong";
                    }
                    $created_by = $_SESSION["uid"];
                    try{
                        $query = "INSERT INTO `issues`(id,title,description,type,priority,assigned_to,created_by,created,updated)
                         VALUES(NULL,:title, :desc, :type, :priority, :assign, :creator , NOW(), NOW())";
                        $sql = $conn->prepare($query);
                        $sql->bindValue(':title', $title, PDO::PARAM_STR);
                        $sql->bindValue(':desc', $description, PDO::PARAM_STR);
                        $sql->bindValue(':type', $type, PDO::PARAM_STR);
                        $sql->bindValue(':priority', $priority, PDO::PARAM_STR);
                        $sql->bindValue(':assign', $assigned_to, PDO::PARAM_INT);
                        $sql->bindValue(':creator', $created_by, PDO::PARAM_INT);
                        $sql->execute();
                        $conn = null;
                        header("Location: home.php"); 
                        exit();
                    }catch(PDOException $error){
                        echo "Database connection failed<br><br>".$error->getMessage();
                    }
                    break;
                default:
                    header("Location: dashboard.php"); 
                    exit();
            }
        }    

        // Sending Data Back To User

        if(isset($_POST["request"]) && $_POST["request"] == "newuserform") {
            // echo $newuser;
            echo "<h1>New User</h1>";
            echo '<form method="POST" name="new-user" onsubmit="fsubmit(event);">';
            echo "<label>Firstname:</label><br>";
            echo '<input name="firstname"><br><br>';
            echo "<label>Lastname:</label><br>";
            echo '<input name="lastname"><br><br>';
            echo "<label>Password:</label><br>";
            echo '<input name="password" type="password"><br><br>';
            echo "<label>Email:</label><br>";
            echo '<input name="email" type="email"><br><br>';
            echo '<input name="newuserdata" type="submit" value="Submit" >';
            echo "</form>";
        }

        if(isset($_POST["request"]) && $_POST["request"] == "newissueform") {
            // echo $newissue;
            $all_users = $conn->prepare("SELECT * FROM `users`");
            $all_users->execute();
            $rows = $all_users->fetchAll(PDO::FETCH_ASSOC);
            echo '<h1>Create Issue</h1>';
            echo '<form method="POST" name="new-issue" onsubmit="fsubmit(event);">';
            echo '<label>Title:</label><br>';
            echo '<input name="title"><br><br>';
            echo '<label>Description:</label><br>';
            echo '<textarea name="description"></textarea><br><br>';
            echo '<label>Assigned to:</label><br>';
            echo '<input list="users" name="assigned-to">';
            echo '<datalist id="users">';
            foreach($rows as $row){
                echo "<option value=\"{$row['firstname']} {$row['lastname']}\">";
            }
            echo '</datalist><br><br>';
            echo '<label>Type:</label><br>';
            echo '<input list="types" name="type"><br><br>';
            echo '<datalist id="types">';
            echo '<option value="Bug">';
            echo '<option value="Proposal">';
            echo '<option value="Task">';
            echo '</datalist>';
            echo '<label>Priority:</label><br>';
            echo '<input list="priority" name="priority"><br><br>';
            echo '<datalist id="priority">';
            echo '<option value="Minor">';
            echo '<option value="Major">';
            echo '<option value="Critical">';
            echo '</datalist>';
            echo '<input name="newissuedata" type="submit" value="Submit">';
            echo '</form>';
            
        }

        if(isset($_POST["request"]) && $_POST["request"] == "gohome") {
            header("Location: home.php"); 
            exit();
        }

        if(isset($_POST["request"]) && $_POST["request"] == "mark-closed") {
            // echo $_POST['ticketId'];
            // exit();
            try {
                $id = $_POST['ticketId'];
                $stmt = "UPDATE `issues` SET status='CLOSED', updated=NOW() WHERE id=:ID";
                $update = $conn->prepare($stmt);
                $update->bindValue(':ID', $id, PDO::PARAM_INT);
                $update->execute();
                $stmt2 = "SELECT * FROM `issues` WHERE id=:ID";
                $update2 = $conn->prepare($stmt2);
                $update2->bindValue(':ID', $id, PDO::PARAM_INT);
                $update2->execute();
                $date = $update2->fetch(PDO::FETCH_ASSOC);
                echo "CLOSED&{$date['updated']}";
            } catch(PDOException $p) {
                echo "Something went wrong";
            }                  
        }

        if(isset($_POST["request"]) && $_POST["request"] == "mark-in-progress") {
            // echo $_POST['ticketId'];
            // exit();
            try {
                $id = $_POST['ticketId'];
                $stmt = "UPDATE `issues` SET status='IN PROGRESS', updated=NOW() WHERE id=:ID";
                $update = $conn->prepare($stmt);
                $update->bindValue(':ID', $id, PDO::PARAM_INT);
                $update->execute();
                $stmt2 = "SELECT * FROM `issues` WHERE id=:ID";
                $update2 = $conn->prepare($stmt2);
                $update2->bindValue(':ID', $id, PDO::PARAM_INT);
                $update2->execute();
                $date = $update2->fetch(PDO::FETCH_ASSOC);
                echo "IN PROGRESS&{$date['updated']}";
            } catch(PDOException $p) {
                echo "Something went wrong";
            }                  
        }

        // if "All" filter is selected
        if(isset($_POST["send_all"]) && $_POST["send_all"] == 'true') {
            $all_issues = $conn->prepare("SELECT * FROM `issues`");
            $all_issues->execute();
            $rows = $all_issues->fetchAll(PDO::FETCH_ASSOC);
            echo "
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th class=\"status-col\">Status</th>
                        <th>Assigned To</th>
                        <th>Created</th>
                    </tr>
                </thead>";
            foreach($rows as $issue){
                echo "<tr>";
                echo "<td><span id=\"tid\">#{$issue['id']} </span><a class='dbentry' id='{$issue['id']}' href='control.php' onclick='details(event);'>{$issue['title']}</a></td>";
                echo "<td>{$issue['type']}</td>";
                
                if ($issue['status']=="OPEN") {
                    echo "<td class=\"status-col\"><span class=\"open-issue\">{$issue['status']}</span></td>";
                }
                if ($issue['status']=="CLOSED") {
                    echo "<td class=\"status-col\"><span class=\"closed-issue\">{$issue['status']}</span></td>";
                }
                if ($issue['status']=="IN PROGRESS") {
                    echo "<td class=\"status-col\"><span class=\"in-progress-issue\">{$issue['status']}</span></td>";
                }
                
                // echo "<td>{$issue['status']}</td>";
                
                try {
                    $id = $issue['assigned_to'];
                    $check = "SELECT * FROM `users` WHERE id=:pid";
                    $person = $conn->prepare($check);
                    $person->bindValue(':pid', $id, PDO::PARAM_INT);
                    $person->execute();
                    $name = $person->fetch(PDO::FETCH_ASSOC);
                    $fname = $name["firstname"];
                    $lname = $name["lastname"];
                } catch(PDOException $p) {
                    echo "Something went wrong";
                }              

                echo "<td>{$fname} {$lname}</td>";
                echo "<td>{$issue['created']}</td>";
            echo "</tr>";
        }
        echo "</table>";
            $conn = null;
        }

        if(isset($_POST["send_open"]) && $_POST["send_open"] == 'true') {
            $open_issues = $conn->prepare("SELECT * FROM `issues` WHERE status='open'");
            $open_issues->execute();
            $rows = $open_issues->fetchAll(PDO::FETCH_ASSOC);
            echo "
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th class=\"status-col\">Status</th>
                        <th>Assigned To</th>
                        <th>Created</th>
                    </tr>
                </thead>";
            foreach($rows as $issue){
                echo "<tr>";
                echo "<td><span id=\"tid\">#{$issue['id']} </span><a class='dbentry' id='{$issue['id']}' href='control.php' onclick='details(event);'>{$issue['title']}</a></td>";
                echo "<td>{$issue['type']}</td>";
                
                if ($issue['status']=="OPEN") {
                    echo "<td class=\"status-col\"><span class=\"open-issue\">{$issue['status']}</span></td>";
                }
                if ($issue['status']=="CLOSED") {
                    echo "<td class=\"status-col\"><span class=\"closed-issue\">{$issue['status']}</span></td>";
                }
                if ($issue['status']=="IN PROGRESS") {
                    echo "<td class=\"status-col\"><span class=\"in-progress-issue\">{$issue['status']}</span></td>";
                }
                // echo "<td>{$issue['status']}</td>";
                
                try {
                    $id = $issue['assigned_to'];
                    $check = "SELECT * FROM `users` WHERE id=:pid";
                    $person = $conn->prepare($check);
                    $person->bindValue(':pid', $id, PDO::PARAM_INT);
                    $person->execute();
                    $name = $person->fetch(PDO::FETCH_ASSOC);
                    $fname = $name["firstname"];
                    $lname = $name["lastname"];
                } catch(PDOException $p) {
                    echo "Something went wrong";
                }              

                echo "<td>{$fname} {$lname}</td>";
                echo "<td>{$issue['created']}</td>";
            echo "</tr>";
        }
        echo "</table>";
            $conn = null;
        }

        if(isset($_POST["send_myisssues"]) && $_POST["send_myisssues"] == 'true') {
            $query = "SELECT * FROM `issues` WHERE assigned_to=:uid";
            $my_issues = $conn->prepare($query);
            $my_issues->bindValue(':uid', $_SESSION["uid"], PDO::PARAM_INT);
            $my_issues->execute();
            $rows = $my_issues->fetchAll(PDO::FETCH_ASSOC);
            echo "
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th class=\"status-col\">Status</th>
                        <th>Assigned To</th>
                        <th>Created</th>
                    </tr>
                </thead>";
            foreach($rows as $issue){
                echo "<tr>";
                echo "<td><span id=\"tid\">#{$issue['id']} </span><a class='dbentry' id='{$issue['id']}' href='control.php' onclick='details(event);'>{$issue['title']}</a></td>";
                echo "<td>{$issue['type']}</td>";
                
                if ($issue['status']=="OPEN") {
                    echo "<td class=\"status-col\"><span class=\"open-issue\">{$issue['status']}</span></td>";
                }
                if ($issue['status']=="CLOSED") {
                    echo "<td class=\"status-col\"><span class=\"closed-issue\">{$issue['status']}</span></td>";
                }
                if ($issue['status']=="IN PROGRESS") {
                    echo "<td class=\"status-col\"><span class=\"in-progress-issue\">{$issue['status']}</span></td>";
                }
                
                // echo "<td>{$issue['status']}</td>";
                
                try {
                    $id = $issue['assigned_to'];
                    $check = "SELECT * FROM `users` WHERE id=:pid";
                    $person = $conn->prepare($check);
                    $person->bindValue(':pid', $id, PDO::PARAM_INT);
                    $person->execute();
                    $name = $person->fetch(PDO::FETCH_ASSOC);
                    $fname = $name["firstname"];
                    $lname = $name["lastname"];
                } catch(PDOException $p) {
                    echo "Something went wrong";
                }              

                echo "<td>{$fname} {$lname}</td>";
                echo "<td>{$issue['created']}</td>";
            echo "</tr>";
        }
        echo "</table>";
            $conn = null;
        }
        
        if(isset($_POST["details"])) {
            $issue_details = $conn->prepare("SELECT * FROM `issues` WHERE id={$_POST['details']}");
            $issue_details->execute();
            $row = $issue_details->fetch(PDO::FETCH_ASSOC);

            // echo var_dump($row);exit();
            
            echo "<div id='full-details'>";
            echo "<h2>{$row['title']}</h2>";
            echo "<h4 id=\"{$row['id']}\">Issue #{$row['id']}</h4>";
            // echo "<div id='details-body'>";
            echo "<div id='details-content'>";
            echo "<p class=\"description\">{$row['description']}</p>";
            echo "<p>{$row['created']}</p>";
            echo "<p id=\"update-time\">{$row['updated']}</p>";
            echo "</div>";
            echo "<div id='details-container'>";
            echo "<div id='details-card'>";
            echo "<h5>Assigned To</h6>";

            try {
                $id = $row['assigned_to'];
                $check = "SELECT * FROM `users` WHERE id=:pid";
                $person = $conn->prepare($check);
                $person->bindValue(':pid', $id, PDO::PARAM_INT);
                $person->execute();
                $name = $person->fetch(PDO::FETCH_ASSOC);
                $fname = $name["firstname"];
                $lname = $name["lastname"];
            } catch(PDOException $p) {
                echo "Something went wrong";
            }              

            echo "<p>{$fname} {$lname}</p>";
            echo "<h5>Type</h6>";
            echo "<p>{$row['type']}</p>";
            echo "<h5>Priority</h6>";
            echo "<p>{$row['priority']}</p>";
            echo "<h5>Status</h6>";
            echo "<p id=\"ticket-status\">{$row['status']}</p>";
            echo "</div>";
            echo "<div class=\"button-group\">";
            echo "<button id='mark-closed' onclick='closeTicket(event);'>Mark as Closed</button>";
            echo "<button id='mark-in-progress' onclick='markInProgress(event);'>Mark In Progress</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            
            $conn = null;
        }
    }  

} else {
    // check if user is registered in database
    $useremail = strip_tags($_POST["email"]);
    $userpwd = strip_tags($_POST["password"]);
    $result;
    try{
        $query = "SELECT * FROM `users` WHERE email=:email";
        $user = $conn->prepare($query);
        $user->bindValue(':email', $useremail, PDO::PARAM_STR);
        $user->execute();
        $result = $user->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $error) {
        echo 'Failed Database Connection<br/><br/>';
        die($error->getMessage());
    }

    if(is_array($result)) {
        // verify user password
        if(password_verify($userpwd, $result["password"])) {
            $_SESSION["username"] = $result["email"];
            $_SESSION["password"] = $result["password"];
            $_SESSION["uid"] = $result["id"];
            $conn = null;
            // send user to dashboard after succesful login
            header("Location: dashboard.php"); 
            exit();
        } else {
            $conn = null;
            // redirect user to login page
            header("Location: index.html"); 
            exit();
        }
    } else {
        // failed login
        $conn = null;
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>BugMe Issue Tracker</title>
            <link rel="stylesheet" href="ui.css">
            <script src="ui.js"></script>
        </head>
        <body>
            <div id="container">
            <header>
                <img id="bug-icon" src="icons/bug.png" alt="bug icon">
                <p>BugMe Issue Tracker</p>
            </header>
            <main id="content">
                <form method="POST" action="control.php" name="user-login" onsubmit="fsubmit(event);>
                    <label for="email">Email:</label><br>
                    <input name="email" type="email"><br><br>
                    <label for="password">Password:</label><br>
                    <input name="password" type="password"><br><br>
                    <input name="formname" value="user-login" hidden>
                    <input id="login" type="submit" value="Login">
                </form>
                <p id="message">Only registered users may login!</p>
            </main> 
            </div>   
        </body>
        </html>
        ';
    }
}

?>
