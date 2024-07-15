<?php
session_start();
include 'dbconnect.php';

if (isset($_POST['search'])) {
    $searchTerm = mysqli_real_escape_string($dbc, $_POST['search']);

    // Query to search for threads
    $sql_search = "SELECT a.*, b.name, c.games_name, c.index_images
                   FROM thread_title AS a
                   LEFT JOIN user AS b ON b.id = a.created_by
                   LEFT JOIN games_index AS c ON c.id = a.id_games
                   WHERE a.thread_title LIKE '%$searchTerm%' OR b.name LIKE '%$searchTerm%'
                   ORDER BY a.id DESC";

    $result_search = mysqli_query($dbc, $sql_search);

    if (mysqli_num_rows($result_search) > 0) {
        while ($row_thread = mysqli_fetch_assoc($result_search)) {
            $id = $row_thread['id'];
            $sql_count = "SELECT count(id) as 'count' FROM thread_post WHERE id_thread = '$id'";
            $result_count = mysqli_query($dbc, $sql_count);
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

                             ?>
                            </p>
                    <!-- <p>Replies: <?php echo $row_count['count']; ?> |
                        Last Action: 
                    </p> -->
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No threads found matching your search.</p>";
    }
    // Close database connection
    mysqli_close($dbc);
}
?>
