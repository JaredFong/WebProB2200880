<?php
    session_start();
    include '../dbconnect.php';

    if(isset($_POST['btn_update'])){

        $announcement = addslashes($_POST['announcement']);
        $sql_update = "UPDATE setting SET message = '$announcement' WHERE status = 'Announcement'";
        if(mysqli_query($dbc, $sql_update)) {
            $msj = "Announcement succesfully updated.";
        }else{
            $msj = "ERROR: Could not execute $sql_update. " . mysqli_error($dbc);
        }

    } 

    if(isset($_POST['btn_update_requlation'])){

        $bil = 1;
        $sql_regulation = "SELECT * FROM setting WHERE status = 'Regulation' ";
        $result_r = mysqli_query($dbc, $sql_regulation) or die('Query failed. ' . mysqli_error($dbc));
        
        // Prepare the update statement
        $stmt = $dbc->prepare("UPDATE setting SET message = ? WHERE id = ?");
        
        while ($row_r = mysqli_fetch_assoc($result_r)) {

            $message = $_POST['regulation'.$bil];
            $id = $row_r['id'];
            
            // Bind parameters and execute the update query
            $stmt->bind_param('si', $message, $id);
            $stmt->execute();
            
            $bil++;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Setting</title>

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
            <legend>Community Setting</legend>
            <?php if(!empty($msj)):?>

            <div class="alert">
                <?php echo $msj; ?>
            </div>

            <?php endif?>
            <div id="news" class="tab-content active">
                <br>
                <?php
                           
                    $sql_setting = "SELECT * FROM setting WHERE status = 'Announcement' ";
                    $result = mysqli_query($dbc, $sql_setting) or die('Query failed. ' . mysqli_error($dbc));
                    $row = mysqli_fetch_assoc($result);
                ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="news">Forum Announcement:</label>
                        <textarea class="form-control" id="announcement" name="announcement" rows="5" required><?php echo $row['message'] ?></textarea>
                    </div>
                    <button type="submit" name="btn_update" class="btn btn-warning">Update</button>
                </form>

         
            </div>

            <legend>Thread Regulation</legend>

            <div id="news" class="tab-content active">
                <br>
                <form method="post" action="">
                <table class="table table-dark " style="width:100%" id="">
                    <thead style="background-color: #ffae00; color:#333; font-weight: bold;">
                        <tr>
                            <td>#</td>
                            <td>Regulation</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $bil = 1;
                            $sql_regulation = "SELECT * FROM setting WHERE status = 'Regulation' ";
                            $result_r = mysqli_query($dbc, $sql_regulation) or die('Query failed. ' . mysqli_error($dbc));
                            while($row_r = mysqli_fetch_assoc($result_r)) { 
                        ?>
                        <tr style="background-color: #eee; color: #000;">
                            <td><?php echo $bil; ?></td>
                            <td><input class="form-control" type="text" name="regulation<?php echo $bil?>" value="<?php echo $row_r['message']; ?>" /></td>
                        </tr>
                        <?php $bil++; } ?> 
                    </tbody>
                </table>
                <button type="submit" name="btn_update_requlation" class="btn btn-warning">Update</button>

             </form>
                



         
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
