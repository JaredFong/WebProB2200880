<?php
session_start();
include 'dbconnect.php';

if (isset($_POST['search'])) {
    $searchTerm = mysqli_real_escape_string($dbc, $_POST['search']);
    $id_games  = $_SESSION['id_games'];

    $sql_search = "SELECT a.id, a.posted, a.posted_by, a.created_date, b.name, b.profile_image, c.games_name, c.index_images
                   FROM reviews AS a
                   LEFT JOIN user AS b ON b.id = a.posted_by
                   LEFT JOIN games_index AS c ON c.id = a.id_games
                   WHERE a.id_games = '$id_games' AND a.posted LIKE '%$searchTerm%'
                   ORDER BY a.id DESC";

    $result_search = mysqli_query($dbc, $sql_search);
    if (!$result_search) {
        die('Query failed: ' . mysqli_error($dbc));
    }

    $result_review = array();
    while ($row = mysqli_fetch_assoc($result_search)) {
        $result_review[] = $row;
    }

    if (count($result_review) > 0) {
        
        foreach ($result_review as $row_review) {
            echo '<div class="thread">';
            echo '<img src="img/profile/' . $row_review['profile_image'] . '" alt="Profile Picture" class="thread-profile-pic">';
            echo '<div class="thread-content">';
            echo '<a style="text-decoration:none;font-size: 14px; color: #fff" href="#"><h6>' . $row_review['posted'] . '</h6></a><br>';
            echo '<div style="font-size: 12px;color: #fff">by ' . $row_review['name'] . ' on ' . $row_review['created_date'] . '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="alert alert-warning">No reviews found for the search term.</div>';
    }
} else {
    echo '<div class="alert alert-danger">No search term provided.</div>';
}

$dbc->close();
?>