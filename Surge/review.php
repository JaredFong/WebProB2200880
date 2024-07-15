<?php
session_start();
include 'dbconnect.php';

if(isset($_GET['games_id'])){

    $games_id = $_GET['games_id'];
    $_SESSION['id_games'] = $games_id;
    $sql = "SELECT * FROM games_index WHERE id = '$games_id' ";
    $result = mysqli_query($dbc, $sql);
    $row = mysqli_fetch_assoc($result);
}

if (isset($_POST['btn_send'])) {

    $post = htmlentities($_POST['post']);
    $id_games = mysqli_real_escape_string($dbc, $_POST['id_games']);
    $posted_by = $_SESSION['id']; // Assuming the user ID is stored in the session
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $created_date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO reviews (id_games, posted, posted_by, created_date) VALUES (?, ?, ?, ?)";
    
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param('isds', $id_games, $post, $posted_by, $created_date);
    
    if ($stmt->execute()) {
        echo "<script>alert('Your review successfully ssubmit!'); window.location.href = 'review.php?games_id=$id_games';</script>";
    } else {
        echo "<script>alert('Error: Unable to post.'); window.location.href = 'review.php?games_id=$id_games';</script>";
    }
    
} 

$review_per_page = 5; 
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; 

$offset = ($current_page - 1) * $review_per_page;

$sql_review = "SELECT a.id, a.posted, a.posted_by, a.created_date, b.name, b.profile_image, c.games_name, c.index_images
               FROM reviews AS a
               LEFT JOIN user AS b ON b.id = a.posted_by
               LEFT JOIN games_index AS c ON c.id = a.id_games
               WHERE a.id_games = '$games_id'
               ORDER BY a.id DESC
               LIMIT ?, ?";


$stmt = $dbc->prepare($sql_review);
if ($stmt === false) {
    die('Error preparing statement: ' . $dbc->error);
}


$stmt->bind_param('ii', $offset, $review_per_page);
$stmt->execute();
$stmt->bind_result($id, $posted, $posted_by, $created_date, $name, $profile_image, $games_name, $index_images);

$result_review = array();
while ($stmt->fetch()) {
    $result_review[] = array(
        'id' => $id,
        'posted' => $posted,
        'posted_by' => $posted_by,
        'created_date' => $created_date,
        'name' => $name,
        'profile_image' => $profile_image,
        'games_name' => $games_name,
        'index_images' => $index_images
    );
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
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
        .thread-list .thread {
          display: flex;
          align-items: center;
          background-color: rgb(52, 58, 63);
          padding: 15px;
          margin: 10px 0;
          border: 1px solid rgba(221, 221, 221, 0.5);
          border-radius: 5px;
          text-align: left;
        }
    </style>
</head>
<body>
    <div class="cursor"></div>
    <header>
        <div class="banner">
            <img src="img/logo.jpg" alt="Site Logo" class="logo-icon">
            <h1 class="site-title">REVIEWS</h1>
            <button class="home-button" onclick="window.location.href='<?php echo $row['url_link']?>'">Return to <?php echo $row['games_name']?> page </button>
        </div>
    </header>
    <main>
        <div class="search">
            <input type="text" id="searchInput" placeholder="Search for user review...">
            <button id="searchButton">Search</button>
        </div>
        <div class="threads">
            <div class="jumbotron" style="background-color: #343a3f; color: #fff; font-weight:700; font-size: 45px; text-align: center; padding:50px 50px 50px 50px; border-radius: 5px;">
            <?php echo $row['games_name']?> : User Reviews
            </div>

            <?php if(!empty($_SESSION['id'])) :?>

            <form action="" method="post">
                <div class="form-group">
                    <label for="thread_title">Post a review</label>
                    <textarea class="form-control" name="post" required></textarea>
                    <input type="hidden" name="id_games" value="<?php echo $row['id'] ?>">
                </div>
                <button type="submit" name="btn_send" class="btn btn-primary">Submit</button>
            </form>

           <?php else:?>

            <div class="alert alert-success">Please sign in first</div>

            <?php endif?>

            <div class="thread-list">

                <?php foreach ($result_review as $row_review) {

                    $id = $row_review['id'];
                    $sql_count = "SELECT count(id) as 'count' FROM thread_post WHERE id_thread = '$id'";
                    $result_count = mysqli_query($dbc, $sql_count) or die('Query failed. ' . mysqli_error($dbc));
                    $row_count = mysqli_fetch_assoc($result_count);

                ?>
                    <div class="thread">
                        <img src="img/profile/<?php echo $row_review['profile_image'] ?>" alt="Profile Picture" class="thread-profile-pic">
                        <div class="thread-content">
                            <a style="text-decoration:none;font-size: 14px; color: #fff" href="#"><h6><?php echo $row_review['posted'] ?></h6></a><br>
                            <div style="font-size: 12px;color: #fff;">by <?php echo $row_review['name'] ?> on <?php echo $row_review['created_date'] ?></div>
                        </div>
                    </div>

                <?php } ?>
            </div>

            <div class="pagination" style="text-align:center">

                <?php $id_games = $row['id'];?>
                <?php if ($current_page > 1) : ?>
                    <a class='btn btn-primary' href="?games_id=<?php echo $id_games; ?>&page=<?php echo $current_page - 1; ?>">Previous</a>
                <?php endif; ?>

                <?php
                
                $sql_count_review = "SELECT COUNT(*) AS total_review FROM reviews WHERE id_games = '$id_games'";
                $result_count_threads = mysqli_query($dbc, $sql_count_review);
                if (!$result_count_threads) {
                    die('Query failed: ' . mysqli_error($dbc));
                }
                $row_count_review = mysqli_fetch_assoc($result_count_threads);
                $total_pages = ceil($row_count_review['total_review'] / $review_per_page);

                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<a class='btn btn-primary' href='?games_id=$id_games&page=$i'>$i</a>";
                }
                ?>

                <?php if ($current_page < $total_pages) : ?>
                    <a class='btn btn-primary' href="?games_id=<?php echo $id_games; ?>&page=<?php echo $current_page + 1; ?>">Next</a>
                <?php endif; ?>
            </div> 
        </div>
    </main>
    <script src="js/script2.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
    (function() {
        $('#searchButton').click(function() {
            var searchTerm = $('#searchInput').val().trim();
            if (searchTerm !== '') {
                // Perform AJAX request to fetch search results
                $.ajax({
                    url: 'search_review.php',
                    type: 'POST',
                    data: { search: searchTerm },
                    success: function(response) {
                        $('.thread-list').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
    })();
    </script>

</body>
</html>

<?php
// Close database connection
$dbc->close();
?>
