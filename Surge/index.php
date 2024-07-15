<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,max-scale=1.0,viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/style1.css">
    <script src="js/script.js"></script>
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
        <header>
            <nav>
                <ul>
                    <img src="img/logo.jpg" alt="Site Logo" class="logo-icon">
                    <li><button class="invisible-button"><a href="forum.php">Community</a></li>
                    <li><button class="invisible-button"><a href="about_us.php">About Us</a></li>
                    <?php if(!empty($_SESSION['user_role'])):?>
                        <?php if($_SESSION['user_role']=='Admin'):?>
                            <li><button class="invisible-button"><a href="#">Administrator</a></li>
                        <?php endif?>
                    <?php endif?>
                    <?php if(empty($_SESSION['id'])):?>
                    <li><button class="invisible-button"><a href="login.php">Login</a></li>
                    <?php else:?>
                    <li><button class="invisible-button"><a href="logout.php">Logout</a></li>
                    <?php endif?>

                </ul>
            </nav>
        </header>
        <main id="scroll-container">
            <section class="panel hero">
                <div class="hero-text">
                    <h1 style="color: white;">Level Up Your Game: The Ultimate Hub for Gamers</h1>
                    <p style="color: white;">A haven for gamers to interact and discover.</p>
                    <a href="about_us.php" class="cta-button">About Us</a></button>
                </div>
            </section>
            <!-- <section class="panel intro">
                <h2></h2>
                <p style="color: white;">Catelog of games. ==></p>
            </section> -->
            <section class="panel gallery">

                <?php

                include 'dbconnect.php'; 
                $sql = "SELECT * FROM games_index WHERE status = 'Active'";
                $result = mysqli_query($dbc, $sql) or die('Query failed. ' . mysqli_error());

                while($row = mysqli_fetch_assoc($result)) {?>  

                ?>

                <button class="image-button" onclick="location.href='<?php echo $row['url_link'] ?>'">
                    <img src="img/<?php echo $row['index_images'] ?>" alt="<?php echo $row['games_name'] ?>">
                </button>
               <!--  <button class="image-button" onclick="location.href='rainbow-six.html'">
                    <img src="img/r6(2).png" alt="Rainbow Six Siege">
                </button>
                <button class="image-button" onclick="location.href='apex.html'">
                    <img src="img/apex.png" alt="Apex Legends">
                </button> -->

                <?php }  ?> 
            </section>
        </main>
    </div>



</body>
</html>
