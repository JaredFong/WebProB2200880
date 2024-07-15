<?php
session_start();
include 'dbconnect.php';
if(empty($_SESSION['id'])){
    print "<script>";
    print "alert('You dont have permission to enter this Forum!. Please login first.'); self.location='~/../index.php';"; 
    print "</script>"; 
}

if (isset($_POST['btn_submit'])) {

    $thread_title = mysqli_real_escape_string($dbc, $_POST['thread_title']);
    $game_id = mysqli_real_escape_string($dbc, $_POST['game_id']);
    $created_by = $_SESSION['id']; // Assuming the user ID is stored in the session

    $sql = "INSERT INTO thread_title (thread_title, id_games, created_by) VALUES (?, ?, ?)";
    
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param('sii', $thread_title, $game_id, $created_by);
    
    if ($stmt->execute()) {
        echo "<script>alert('Thread started successfully!'); window.location.href = 'forum.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to start thread.'); window.location.href = 'start_thread.php';</script>";
    }
    
} 

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start New Thread</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style4.css">
    <style type="text/css">
        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            margin-right: 0.2rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            text-decoration: none;
        }
        .btn-primary {
            color: #fff;
            background: linear-gradient(to right, rgba(66, 178, 219, 0.6), rgb(0, 67, 143));
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            border: none;
        }
        .btn-success:hover {
            color: #fff;
            background: linear-gradient(to right, rgba(51, 51, 51, 0.8), rgba(102, 102, 102, 0.8));
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            border: none;
        }
        .btn-success {
            color: #fff;
            background: linear-gradient(to right, rgba(51, 51, 51, 0.8), rgba(102, 102, 102, 0.8));
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            border: none;
        }
    </style>
</head>
<body>
     <div class="cursor"></div>
     <header>
        <div class="banner">
            <img src="img/logo.jpg" alt="Site Logo" class="logo-icon">
            <h1 class="site-title">FORUMS</h1>
            <button class="home-button" onclick="window.location.href='index.php'">Return to Home</button>
        </div>
    </header>
    <main>
    <div class="threads">
        <h2>📜 Start Thread </h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="thread_title">Thread Title</label>
                <input type="text" class="form-control" id="thread_title" name="thread_title" required>
            </div>
            <div class="form-group">
                <label for="game_id">Select Game</label>
                <select id="game_id" name="game_id" class="form-control" required>
                    <?php

                    $sql_games = "SELECT id, games_name FROM games_index";
                    $result_games = mysqli_query($dbc, $sql_games);
                    while ($row_games = mysqli_fetch_assoc($result_games)) {
                        echo "<option value='{$row_games['id']}'>{$row_games['games_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="btn_submit" class="btn btn-primary">Create</button>
            <button type="button" onclick="window.location.href='forum.php'" class="btn btn-success">Back to Forum</button>
        </form>
    </div>
    </main>
    <script src="js/script2.js"></script>
</body>
</html>
