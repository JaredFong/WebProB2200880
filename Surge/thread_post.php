<?php
session_start();
include 'dbconnect.php';
if(empty($_SESSION['id'])){
    print "<script>";
    print "alert('You dont have permission to enter this Forum!. Please login first.'); self.location='~/../index.php';"; 
    print "</script>"; 
}

if(isset($_GET['id'])){

    $thread_id = $_GET['id'];
    $sql = "SELECT tt.*, u.name, gi.new_images 
            FROM thread_title as tt
            LEFT JOIN user AS u ON u.id = tt.created_by
            LEFT JOIN games_index AS gi ON gi.id = tt.id_games
            WHERE tt.id = '$thread_id' ";
    $result = mysqli_query($dbc, $sql);
    $row = mysqli_fetch_assoc($result);

}

if (isset($_POST['btn_send'])) {

    $post = mysqli_real_escape_string($dbc, $_POST['post']);
    $posted_by = $_SESSION['id']; // Assuming the user ID is stored in the session
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $created_date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO thread_post (id_thread, posted, posted_by, created_date) VALUES (?, ?, ?, ?)";
    
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param('isds', $thread_id, $post, $posted_by, $created_date);
    
    if ($stmt->execute()) {
        echo "<script>alert('Thread post successfully save!'); window.location.href = 'thread_post.php?id=$thread_id';</script>";
    } else {
        echo "<script>alert('Error: Unable to post.'); window.location.href = 'thread_post.php?id=$thread_id';</script>";
    }
    
} 


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thread Post</title>
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
        <h2>📜 Thread Post </h2>

        <div class="jumbotron" style="background-image:url('img/<?php echo $row['new_images'] ?>');background-size:cover">
        <h1 class="display-4">Welcome!!</h1>
        <p class="lead thread-list"> <?php echo $row['thread_title']?></p>
        <hr class="my-4">
        <p style="color:#fff">by <?php echo $row['name']?> | created on <?php echo date('d M Y',strtotime($row['created_date']))?> </p>
        </div>

        <form action="" method="post">
            <div class="form-group">
                <label for="thread_title">New Post / Question</label>
                <textarea class="form-control" name="post" required></textarea>
            </div>

            <button type="submit" name="btn_send" class="btn btn-primary">Send</button>
            <button type="button" onclick="window.location.href='forum.php'" class="btn btn-success">Back to Forum</button>
        </form>

        <?php
            $bil = 1;

            $sql_post = "SELECT tt.*, u.name, u.profile_image
            FROM thread_post as tt
            LEFT JOIN user AS u ON u.id = tt.posted_by
            WHERE tt.id_thread = '$thread_id' ";
            $result_post = mysqli_query($dbc, $sql_post) or die('Query failed. ' . mysqli_error($dbc));
            while($row_post = mysqli_fetch_assoc($result_post)) { 
        ?>

        <div class="thread-list">
            <div class="thread">
                <img src="img/profile/<?php echo $row_post['profile_image'] ?>" alt="Profile Picture" class="thread-profile-pic">
                <div class="thread-content">
                    <a style="text-decoration:none;font-size: 16px;" href="#"><h6><?php echo $row_post['posted'] ?></h6></a><br>
                    <div style="font-size: 12px;">by <?php echo $row_post['name'] ?> on <?php echo $row_post['created_date'] ?></div>
                </div>
            </div>
        </div>

        <!-- <div class="media my-2 border" style="background-color:#fff">
            <img src="img/profile/<?php echo $row_post['profile_image'] ?>" width="80px" class="mr-3" alt="...">
            <div class="media-body">
            <p class="font-weight-normal my-0">Posted By - <?php echo $row_post['name'] ?>  at <?php echo $row_post['created_date'] ?></p>
                <h5 class="mt-0"><?php echo $row_post['posted'] ?></h5>
            </div>
        </div> -->
        <?php } ?>
    </div>
    </main>
    <script src="js/script2.js"></script>
</body>
</html>
