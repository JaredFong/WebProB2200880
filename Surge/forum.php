<?php
session_start();
include 'dbconnect.php';

if (empty($_SESSION['id'])) {
    echo "<script>alert('You don\'t have permission to enter this Forum! Please login first.'); window.location.href = 'index.php';</script>";
    exit();
}

$threads_per_page = 5; // Number of threads per page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Default to page 1 if not set

$offset = ($current_page - 1) * $threads_per_page;

$sql_thread = "SELECT a.id, a.thread_title, a.created_by, a.created_date, b.name, c.games_name, c.index_images
               FROM thread_title AS a
               LEFT JOIN user AS b ON b.id = a.created_by
               LEFT JOIN games_index AS c ON c.id = a.id_games
               ORDER BY a.id DESC
               LIMIT ?, ?";


$stmt = $dbc->prepare($sql_thread);
if ($stmt === false) {
    die('Error preparing statement: ' . $dbc->error);
}


$stmt->bind_param('ii', $offset, $threads_per_page);
$stmt->execute();
$stmt->bind_result($id, $thread_title, $created_by, $created_date, $name, $games_name, $index_images);

$result_thread = array();
while ($stmt->fetch()) {
    $result_thread[] = array(
        'id' => $id,
        'thread_title' => $thread_title,
        'created_by' => $created_by,
        'created_date' => $created_date,
        'name' => $name,
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
    <title>Forum Page</title>
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
        <div class="search">
            <input type="text" id="searchInput" placeholder="Search for threads...">
            <button id="searchButton">Search</button>
        </div>
        <div class="info-sections">
            <div class="rules">
                <h2>📜 Rules & Regulations</h2>
                <ul>
                    <?php
                    // Fetch regulations
                    $sql_regulation = "SELECT * FROM setting WHERE status = 'Regulation'";
                    $result_regulation = mysqli_query($dbc, $sql_regulation);
                    while ($row_r = mysqli_fetch_assoc($result_regulation)) {
                        echo "<li>❗" . htmlspecialchars($row_r['message']) . "</li>";
                    }
                    ?>
                </ul>
            </div>
            <div class="forum-info">
                <h2>📌Forum</h2>
                <?php
                // Fetch announcement
                $sql_announcement = "SELECT * FROM setting WHERE status = 'Announcement'";
                $result_announcement = mysqli_query($dbc, $sql_announcement);
                if (!$result_announcement) {
                    die('Query failed: ' . mysqli_error($dbc));
                }
                $row_a = mysqli_fetch_assoc($result_announcement);
                ?>
                <div class="forum-description">
                    <p><?php echo htmlspecialchars($row_a['message']); ?></p>
                </div>
            </div>
        </div>
        <div class="threads">
            <h2>💬 Threads 🗨️</h2>
            <div class="thread-list">
                <?php foreach ($result_thread as $row_thread) {
                    $id = $row_thread['id'];
                    $sql_count = "SELECT count(id) as 'count' FROM thread_post WHERE id_thread = '$id'";
                    $result_count = mysqli_query($dbc, $sql_count) or die('Query failed. ' . mysqli_error($dbc));
                    $row_count = mysqli_fetch_assoc($result_count);
                ?>
                    <div class="thread">
                        <img src="img/<?php echo $row_thread['index_images'] ?>" alt="Profile Picture" class="thread-profile-pic">
                        <div class="thread-content">
                            <a style="text-decoration:none" href="thread_post.php?id=<?php echo $id?>"><h3><?php echo $row_thread['thread_title'] ?> [<?php echo $row_thread['name'] ?>]</h3></a>
                            <p>Replies: <?php echo $row_count['count']; ?> |

                                <?php
                                // SQL Query
                                $sql_last = "SELECT tp.id, DATE_FORMAT(tp.created_date, '%Y-%m-%d %H:%i:%s') AS last_action_time, u.name AS last_action_by
                                             FROM thread_post AS tp
                                             LEFT JOIN user AS u ON tp.posted_by = u.id
                                             WHERE tp.id_thread = '$id'
                                             ORDER BY tp.id DESC
                                             LIMIT 1";

                                $stmt_last = $dbc->prepare($sql_last);
                                $stmt_last->execute();

                                $stmt_last->bind_result($id, $last_action_time, $last_action_by);

                                $result_last = array();


                                while ($stmt_last->fetch()) {
                                    $result_last[] = array(
                                        'id' => $id,
                                        'last_action_time' => $last_action_time,
                                        'last_action_by' => $last_action_by,
                                    );
                                }

                                if($row_count['count']==0){
                                    echo "No posts found for the specified thread.";
                                }

                                
                                foreach ($result_last as $row_last) {

                                    $last_action_time = $row_last['last_action_time'];
                                    $last_action_by = $row_last['last_action_by'];

                             
                                    if ($last_action_time) {
                                        $last_action_time = new DateTime($last_action_time);
                                        $formatted_date = $last_action_time->format('F j, Y, g:i A');

                  
                                        $now = new DateTime();
                                        $diff = $now->diff($last_action_time)->days;

                                        if ($diff == 0) {
                                            $formatted_date = 'Today, ' . $last_action_time->format('g:i A');
                                        } elseif ($diff == 1) {
                                            $formatted_date = 'Yesterday, ' . $last_action_time->format('g:i A');
                                        }

                                       
                                    } else {
                                        $formatted_date = 'No actions yet';
                                        
                                    }

                                    echo "Last Action: $formatted_date by $last_action_by";

                                } 


                                //$result_last = $stmt_last->get_result();
/*
                                if ($row_last = $result_last->fetch_assoc()) {

                                    $last_action_time = $row_last['last_action_time'];
                                    $last_action_by = $row_last['last_action_by'];

                             
                                    if ($last_action_time) {
                                        $last_action_time = new DateTime($last_action_time);
                                        $formatted_date = $last_action_time->format('F j, Y, g:i A');

                  
                                        $now = new DateTime();
                                        $diff = $now->diff($last_action_time)->days;

                                        if ($diff == 0) {
                                            $formatted_date = 'Today, ' . $last_action_time->format('g:i A');
                                        } elseif ($diff == 1) {
                                            $formatted_date = 'Yesterday, ' . $last_action_time->format('g:i A');
                                        }
                                    } else {
                                        $formatted_date = 'No actions yet';
                                    }

                                   
                                    echo "Last Action: $formatted_date by $last_action_by";
                                } else {
                                    echo "No posts found for the specified thread.";
                                }*/

                                ?>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="pagination" style="text-align:center">
                <!-- Previous button -->
                <?php if ($current_page > 1) : ?>
                    <a class='btn btn-primary' href="?page=<?php echo $current_page - 1; ?>">Previous</a>
                <?php endif; ?>

                <!-- Page numbers -->
                <?php
                $sql_count_threads = "SELECT COUNT(*) AS total_threads FROM thread_title";
                $result_count_threads = mysqli_query($dbc, $sql_count_threads);
                if (!$result_count_threads) {
                    die('Query failed: ' . mysqli_error($dbc));
                }
                $row_count_threads = mysqli_fetch_assoc($result_count_threads);
                $total_pages = ceil($row_count_threads['total_threads'] / $threads_per_page);

                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<a class='btn btn-primary' href='?page=$i'>$i</a>";
                }
                ?>

                <!-- Next button -->
                <?php if ($current_page < $total_pages) : ?>
                    <a class='btn btn-primary' href="?page=<?php echo $current_page + 1; ?>">Next</a>
                <?php endif; ?>
            </div>
            <button class="new-topic" onclick="window.location.href='start_thread.php'">Start Thread</button>
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
                        url: 'search_thread.php',
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
