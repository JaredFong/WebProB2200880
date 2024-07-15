<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,max-scale=1.0,viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../css/style1.css">
    <script src="../js/script.js"></script>
    <!-- HTML Meta Tags -->
    <title>Surge</title>
    <meta name="title" content="Surge">
    <meta name="description" content="ALL YOUR GAMING NEEDS MET IN ONE PLACE">
    <meta name="keywords" content="GAME GAMING CONSOLE KEYBOARD FPS">
</head>

<body>
    <div class="background"></div>
    <div class="overlay"></div>
    <div class="cursor"></div>
    
    <div class="content-wrapper"></div>
        <?php include 'menu.php';?>
        <main id="scroll-container">
            <section class="panel gallery">

                <?php

                include '../dbconnect.php'; 
                $sql = "SELECT * FROM games_index WHERE status = 'Active'";
                $result = mysqli_query($dbc, $sql) or die('Query failed. ' . mysqli_error());

                while($row = mysqli_fetch_assoc($result)) {?>  

                ?>

                <button class="image-button">
                    <img src="../img/<?php echo $row['index_images'] ?>" alt="<?php echo $row['games_name'] ?>">
                </button>
                <?php }  ?> 
            </section>
        </main>
    </div>



</body>
</html>
