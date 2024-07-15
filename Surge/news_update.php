<?php
    include 'dbconnect.php'; 
    if(isset($_GET['games_id'])){
        $games_id = $_GET['games_id'];
        $sql = "SELECT * FROM games_index WHERE id = '$games_id'";
        $result = mysqli_query($dbc, $sql) or die('Query failed. ' . mysqli_error($dbc));
        $row = mysqli_fetch_assoc($result);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['games_name']; ?> News</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #121212;
        color: #FFFFFF;
        margin: 0;
        cursor: none; /* Hide the default cursor */
    }

    .custom-cursor {
        width: 20px;
        height: 20px;
        background-color: rgb(15, 0, 55); /* You can change the color */
        border: 1px solid white;
        border-radius: 50%;
        position: absolute;
        pointer-events: none; /* Ignore pointer events to avoid blocking clicks */
        transform: translate(-50%, -50%);
        transition: transform 0.1s ease; /* Smooth movement */
        z-index: 1000; /* Ensure the cursor is on top of all other elements */
    }

    header {
        width: 100%;
        height: 400px;
        background-image: url('img/<?php echo $row['new_images']; ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
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
        padding: 20px 40px;
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
        width: 200px;
        height: 130px;
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

    .alert{
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

    .ellipsis {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    </style>
</head>
<body>
    <div class="custom-cursor"></div>
    <header></header>
    <div class="tab-menu">
        <button class="tab-button return-button" onclick="window.location.href='<?php echo $row['url_link'] ?>';">Return to <?php echo $row['games_name']; ?></button>
        <div class="tab-buttons-container">
            <button class="tab-button active" onclick="openTab(event, 'news')">News</button>
        </div>
    </div>
    <main>
        <div id="news" class="tab-content active">

            <?php
                $games_id = $_GET['games_id'];
                $sql_new = "SELECT * FROM news_update WHERE id_games = '$games_id'";
                $results = mysqli_query($dbc, $sql_new) or die('Query failed. ' . mysqli_error($dbc));

                while($row_new = mysqli_fetch_assoc($results)) {?>  
 
            <article class="news-item">
                <img src="img/news/<?php echo $row_new['images'];  ?>" alt="" class="news-image">
                <div class="news-content">
                    <h2><?php echo strtoupper($row_new['title']);  ?> </h2>
                    <p><?php echo date('d M Y',strtotime($row_new['created_date']));  ?></p>
                    <p>
                        <?php 
                            $new = $row_new['news'];  
                            $out = strlen($new) > 100 ? substr($new,0,100)."..." : $new;
                            echo $out;
                        ?>
                            
                    </p>
                    <a href="#" class="read-more-link" onclick="toggleNews(this); return false;">Read More →</a>
                    <div class="full-news-content" style="display: none;">
                        <p><?php echo $row_new['news'];  ?></p>
                        <!-- <a href="#" class="read-less-link" onclick="toggleNews(this); return false;">Read Less ←</a> -->
                    </div>
                </div>
            </article>

            <?php }  ?> 

            <?php
                if(mysqli_num_rows($results) <= 0):?>

                <div class="alert">
                    No News / Update Available..
                </div>
                

            <?php endif?>

        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('mousemove', (event) => {
                const cursor = document.querySelector('.custom-cursor');
                cursor.style.left = `${event.pageX}px`;
                cursor.style.top = `${event.pageY}px`;
            });
        });

        function openTab(event, tabName) {
            var i, tabcontent, tablinks;
            
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }

            tablinks = document.getElementsByClassName("tab-button");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }

            document.getElementById(tabName).classList.add("active");
            event.currentTarget.classList.add("active");
        }

        function toggleNews(link) {
            // Find the parent news-item element
            var newsItem = link.closest('.news-item');
            
            // Toggle between showing/hiding the full news content
            var fullContent = newsItem.querySelector('.full-news-content');
            if (fullContent.style.display === 'none') {
                // Show full content and update link text to "Read Less"
                fullContent.style.display = 'block';
                link.innerText = 'Read Less ←';
            } else {
                // Hide full content and update link text to "Read More"
                fullContent.style.display = 'none';
                link.innerText = 'Read More →';
            }
        }
    </script>
</body>
</html>
