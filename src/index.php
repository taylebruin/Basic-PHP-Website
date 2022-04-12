<?php
session_start();

if (!$_SESSION["loggedin"]) {
    header("Location: views\login.php");
} else {
    // Read variables and create connection
    $mysql_servername = getenv("MYSQL_SERVERNAME");
    $mysql_user = getenv("MYSQL_USER");
    $mysql_password = getenv("MYSQL_PASSWORD");
    $mysql_database = getenv("MYSQL_DATABASE");

    // This section for DEBUGGING ONLY! COMMENT-OUT WHEN FINISHED
    // echo "<p>mysql_servername: $mysql_servername</p>";
    // echo "<p>mysql_user: $mysql_user</p>";
    // echo "<p>mysql_password: $mysql_password</p>";
    // echo "<p>mysql_database: $mysql_database</p>";

    $conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        //echo "Database Connection Success";
    }
    $stmt = $conn->prepare("SELECT * FROM `task` WHERE `user_id` = ?");
    $stmt->bind_param('i', $_SESSION["id"]);
    $stmt->execute();
}
?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Add an appropriate title in this tag -->
    <title>Taylers Amazing Website</title>
    <nav>
        <a href="https://unsplash.com/s/photos/turtle">Turtle Photos | </a>
        <a href="https://unsplash.com/s/photos/kittens">Kitten Photos | </a>
        <a href="https://unsplash.com/s/photos/goats">Goat Photos</a>
        <form action="actions/logout_action.php" method="post">
            <input type="submit" class="pretty-button" value="logout" action="actions/logout_action.php" method="post">
        </form>
    </nav>
    <h1>Awesome Animal Tasks</h1>
    <!-- Links to stylesheets -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <br>
    <input type="checkbox" id="dates" class="toggle-switch">
    <label for="dates">sort by date</label>
    <input type="checkbox" id="tasks" class="toggle-switch">
    <label for="tasks">filter completed tasks</label>
    <ul class="tasklist">
        <?php
        $stmt->bind_result($taskid, $useridfortask, $tasktext, $taskdate, $taskdone);
        while ($row = $stmt->fetch()) {
        ?>
            <li class="task" task-id="<?php echo $taskid ?>">
                <form action="actions/updatetask.php" style="display: inline;" method="post">
                    <button type="submit" class="checkboxbutton" id="<?php echo $taskid ?>">
                        <?php if ($taskdone == 1) {
                        ?> check_box<?php
                                } else {
                                    ?>
                            check_box_outline_blank
                        <?php
                                }  ?> </button>
                    <input type="hidden" value="<?php echo $taskid ?>" name="task_id">
                    <input type="hidden" value="<?php echo $taskdone ?>" name="task_done">
                </form>
                <span class="task-description <?php if ($taskdone == 1) {
                                                ?>  linethrough" <?php
                                                                }  ?> for="<?php echo $taskid ?>" task-id="<?php echo $taskid ?>"><?php echo $tasktext ?></span>
                <span class="task-date"><?php echo $$taskdate ?></span>
                <form action="actions/delete_task.php" style="display: inline;" method="post">
                    <button type="submit" class="task-delete material-icon">remove_circle</button>
                    <input type="hidden" value="<?php echo $taskid ?>" name="task_id">
                </form>
            </li>
            </li>

        <?php
        }
        ?>
    </ul>
    <form class="form-create-task" action="actions/addtask_action.php" method="post">
        <label for="action">Action needed:</label>
        <input type="text" class="form-settings" name="action" required> <br>
        <label for="date completed">Date completed:</label>
        <input type="date" id="date completed" name="date_completed"><br>
        <button class="pretty-button">Create Action</button>
    </form>
    <!-- Your visible elements -->
</body>

</html>