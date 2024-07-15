<?php
    session_start();
    include '../dbconnect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News & Update</title>

    <link rel="stylesheet" href="../css/style1.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.css"> 
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/autofill/2.7.0/css/autoFill.bootstrap4.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script> 
    
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

        .table-dark td, .table-dark th, .table-dark thead th {
            border-color: #ffae00;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #ffae00;
            font-weight: bold;
        }

        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #ffae00;
        }

        .table-dark {
            color: #fff;
        }

        table {
            border-collapse: collapse;
        }

        .button-delete {
            background-color: #9a3f41;
            color: white;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1.2em;
            margin: 0 5px;
            text-decoration: none;  
        }

        .page-item.active .page-link {
          z-index: 1;
          color: #fff;
          background-color: #333;
          border-color: #333;
        }


        


    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="cursor"></div>
    <div class="content-wrapper"></div>
    <?php include 'menu.php'; ?>
    <br>
    <main class="content-wrapper">
        <section class="table-responsive">
            <legend>News & Update</legend>
            <div id="news" class="tab-content active">
                <div class="tab-menu">
                    <div class="tab-buttons-container">
                        <button class="tab-button active" onclick="window.location.href='insert_news.php'">Create News</button>
                    </div>
                </div>
                <br>

                <table class="table table-dark " style="width:100%" id="myTable">
                    <thead style="background-color: #ffae00; color:#333; font-weight: bold;">
                        <tr>
                            <td>#</td>
                            <td>Images</td>
                            <td>Games</td>
                            <td>Title</td>
                            <td>News</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $bil = 1;
                            $sql_new = "SELECT a.*, b.games_name 
                                       FROM news_update AS a
                                       LEFT JOIN games_index AS b ON b.id = a.id_games";
                            $results = mysqli_query($dbc, $sql_new) or die('Query failed. ' . mysqli_error($dbc));
                            while($row_new = mysqli_fetch_assoc($results)) { 
                        ?>
                        <tr style="background-color: #eee; color: #000;">
                            <td><?php echo $bil; ?></td>
                            <td><img class="news-image" src="../img/news/<?php echo $row_new['images']; ?>"></td>
                            <td><strong><?php echo $row_new['games_name']; ?></strong></td>
                            <td><?php echo strtoupper($row_new['title']); ?></td>
                            <td><?php echo $row_new['news']; ?></td>
                            <td><a href="delete_news.php?id=<?php echo $row_new['id']; ?>" class="btn btn-danger">Delete</td>
                        </tr>
                        <?php $bil++; } ?> 
                    </tbody>
                </table>
                <?php if(mysqli_num_rows($results) <= 0): ?>
                    <div class="alert">
                        No News / Update Available..
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable').DataTable({
                
            });
        });
    </script>

    <script src="../js/script.js"></script>
</body>
</html>
