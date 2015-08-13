<?php

include_once '../includes/includes.php';

/**
 * User: Chris
 * Date: 26/02/2015
 * Time: 11:15
 */

class ThreadView extends MessageBoardPage
{
    /*
     * Function to generate the main body of the page. Required due to abstract method msg_board_body()
     * inherited from MessageBoardPage.
     */
    public function msg_board_body()
    {
        if(isset($_GET['id']))
        {
            if(isset($_POST['postSubmit']))
            {
                $this->db->add_post_to_thread($_SESSION['userID'], $_GET['id'], $_POST['content']);
            }
            ?>

            <div class="post_wrapper">
                <div class="main_content">

                    <div class="post_containers">
                        <?php
                        $posts = $this->db->find_all_from_table("cpBoardPosts", "WHERE `threadID` = {$_GET['id']}");
                        echo sizeof($posts) == 0 ? "There are no posts in this thread" : "";
                        foreach ($posts as $post)
                        {
                            $this->single_post($post);
                        }
                        ?>
                    </div>

                    <p><a class="button" href="board_view.php?id=<?php echo $this->db->find_all_from_table("cpThread", "WHERE `threadID` = {$_GET['id']}")[0]['boardID']; ?>"><< Back</a> </p>
                    <div id = "submission_box" class = "<?php echoIfClearanceGranted(2,"show","hide")?>">
                        <hr />
                        <form class="thread_form" name="new_post" action="" method="post">
                            <h4>Add New Post</h4>
                            <br />
                            <textarea name="content" rows="10" cols="125"></textarea><br />
                            <input type="submit" class="button" name="postSubmit" value="Submit">
                            <?php
                            //DEBUG
//                            echo "Session Data:\n";
//                            print_pre($_SESSION);
                            //echo "Get: \n";
                            //print_pre($_GET);
                            //echo "Request: \n";
                            //print_pre($_REQUEST);
                            //echo "Post: \n";
                            //print_pre($_POST);
                            ?>
                        </form>
                    </div>


                </div>
            </div>
        <?php
        }
        else
        {
        }
    }

    /*
     * A singular post within the thread.
     * Retrieves user data from cpUser table in the database, stored as an array '$user'.
     * Placeholder image in place of user avatar.
     *
     */
    function single_post($post)
    {
        $user = $this->db->find_all_from_table("cpUser", "WHERE `userID` = {$post['userID']}")[0];
        ?>
        <div class="single_post">
            <div class="user_info_margin">
                <img src="<?php echo RESOURCE_PATH; ?>/img/avatar-placeholder.png" width="80%"/>
                <h3><?php echo $user['username']; ?></h3>
                <p>Member Since:</p>
                <p><?php
                    $date = strtotime( $user['memberSince'] );
                    echo date( 'M d Y', $date ); ?></p>
            </div>
            <div class="post_content">
                <p><?php echo $post['content']; ?></p>
            </div>

        </div>
    <?php
    }
}

new ThreadView('Barber', array("post_styles.css"));
