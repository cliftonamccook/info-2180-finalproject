<?php
session_start();

if (!isset($_SESSION["username"], $_SESSION["password"])) {
    header("Location: index.html");
    die();
}
require("dbconnect.php");
?>

<div id="dashboard-head">
    <h1>Issues</h1>
    <button id="create-issue" <?php if($_SESSION["username"] !== "admin@project2.com"){echo'style="display:none;"';}?> onclick="createIssue(event);">Create New Issue</button>
</div>
<div id="filters">
    <div id="filters-title">Filter By:</div>
    <button id="all-filter" class="default-filter" onclick="filterAll(event);">ALL</button>
    <button id="open-filter" onclick="filterOpen(event);">OPEN</button>
    <button id="my-filter" onclick="filterMine(event);">MY TICKETS</button>
</div>

<div id="data">
    <!--Tables and stuff load in here with AJAX-->
    <?php
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
                $row = $person->fetch(PDO::FETCH_ASSOC);
                $fname = $row["firstname"];
                $lname = $row["lastname"];
            } catch(PDOException $p) {
                echo "Something went wrong";
            }              

            echo "<td>{$fname} {$lname}</td>";
            echo "<td>{$issue['created']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    $conn = null;
?>
</div>
