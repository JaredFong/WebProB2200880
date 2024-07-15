<?php
    session_start();
    include '../dbconnect.php'; 

    // Check if form is submitted
    if (isset($_POST['btn_submit'])) {

        $games_id = mysqli_real_escape_string($dbc, $_POST['games']);
        $title = mysqli_real_escape_string($dbc, $_POST['title']);
        $news = mysqli_real_escape_string($dbc, $_POST['news']);

        $target_dir = "../img/news/";
        $filename = basename($_FILES["images"]["name"]);
        $target_file = $target_dir . basename($_FILES["images"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $created_date = date('Y-m-d');
        

        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["images"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Insert data into database
        $sql_insert = "INSERT INTO news_update (id_games, title, news, images, created_date) VALUES ('$games_id', '$title', '$news', '$filename','$created_date')";
        if(mysqli_query($dbc, $sql_insert)) {
            $msj = "Records added successfully.";
        } else {
            $msj = "ERROR: Could not execute $sql_insert. " . mysqli_error($dbc);
        }
        
        // Upload image file
        if ($uploadOk == 0) {
           $msj = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["images"]["tmp_name"], $target_file)) {
                $msj = "The file ". htmlspecialchars( basename( $_FILES["images"]["name"])). " has been uploaded.";
            } else {
                $msj ="Sorry, there was an error uploading your file.";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News & Update</title>

    <link rel="stylesheet" href="../css/style1.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #FFAE00;
            margin: 0;
        }

        .tab-menu {
            display: flex;
            justify-content: space-between; 
            align-items: center; 
            background-color: rgba(51, 51, 51, 0); 
            border-top: 2px solid rgba(123, 123, 123, 0); 
            border-bottom: 2px solid rgb(255, 174, 0); 
            border-left: 2px solid black; 
            border-right: 2px solid black; 
            margin-top: 10px; 
            padding: 10px 20px; 
            overflow: hidden; 
            z-index: 1;
        }

        .tab-buttons-container {
            display: flex;
            align-items: center; 
        }

        .tab-button {
            background-color: #af7228b6;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1.2em;
            margin: 0 5px;
            border-radius: 30px; 
        }

        .return-button {
            background-color: #da7f0fb6; 
            color: white; 
            padding: 20px 40px; 
            border: none; 
            cursor: pointer; 
            transition: background-color 0.3s; 
            font-size: 1.2em; 
            border-radius: 30px; 
        }

        .tab-button:hover,
        .tab-button.active,
        .return-button:hover {
            background-color: #c34b4bca; 
        }

        .tab-content {
            display: none;
            padding: 20px;
            border: 2px solid rgba(0, 0, 0, 0); 
            border-radius: 8px; 
            margin-top: 10px; 
        }

        .tab-content.active {
            display: block;
        }

        .news-item {
            display: flex;
            background-color: #06145c00;
            margin: 10px;
            padding: 20px;
            border-bottom: 2px solid rgb(143, 143, 143); 
            border-radius: 8px; 
            position: relative;
            top: -20px; 
        }

        .news-image {
            width: 150px;
            height: auto;
            margin-right: 20px;
            border-radius: 8px;
        }

        .news-content h2 {
            margin-top: 0;
        }

        .news-content a {
            color: #FFA500;
            text-decoration: none;
            transition: color 0.3s;
        }

        .news-content a:hover {
            color: #FFD700;
        }

        @media (max-width: 599px) {
            .tab-menu {
                flex-direction: column;
                align-items: stretch;
            }

            .tab-button, .return-button {
                margin: 5px 0;
                width: 100%; 
            }

            .news-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .news-image {
                width: 100%;
                margin: 0 0 20px 0;
            }
        }

        .alert {
            background-color: #a05f0f;
            position: relative;
            padding: .75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-top-color: transparent;
            border-right-color: transparent;
            border-bottom-color: transparent;
            border-left-color: transparent;
            border-radius: .25rem;
        }

    </style>
</head>
<body>
    <div class="cursor"></div>
    <div class="content-wrapper"></div>
    <?php include 'menu.php'; ?>
    <br>
    <main class="content-wrapper">
        <section class="table-responsive">
            <legend>Create News & Update</legend>

            <?php if(!empty($msj)):?>

            <div class="alert">
                <?php echo $msj; ?>
            </div>

            <?php endif?>


            <div id="news" class="tab-content active">
                <br>
                <form action="insert_news.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="games">Games:</label>
                        <select class="form-control" id="games" name="games">
                            <?php
                            $sql_games = "SELECT * FROM games_index";
                            $result_games = mysqli_query($dbc, $sql_games) or die('Query failed. ' . mysqli_error($dbc));
                            while ($row_games = mysqli_fetch_assoc($result_games)) {
                                echo '<option value="' . $row_games['id'] . '">' . $row_games['games_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="news">News / Update:</label>
                        <textarea class="form-control" id="news" name="news" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="images">Upload Image:</label>
                        <input type="file" class="form-control-file" id="images" name="images" accept="image/*" required>
                    </div>
                    <button type="submit" name="btn_submit" class="btn btn-warning">Submit</button>
                </form>
            </div>
        </section>
    </main>


    <script src="../js/script.js"></script>
</body>
</html>
