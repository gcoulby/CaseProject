<?php

include_once '../includes/includes.php';

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 26/02/2015
 * Time: 11:15
 */

class BoardView extends MessageBoardPage
{
    public function msg_board_body()
    {
        if(isset($_POST['postSubmit']))
        {
            if($this->db->return_number_of_rows_by_condition("cpThread", "WHERE `threadTitle` = \"{$_POST['title']}\" AND `boardID` = \"{$_GET['id']}\"") == 0)
            {
                $this->db->add_thread_to_board($_GET['id'], $_SESSION['userID'], $_POST['title']);
                $this->db->add_post_to_thread($_SESSION['userID'], $this->db->find_all_from_table("cpThread", "WHERE threadTitle` = \"{$_POST['title']}\" AND `boardID` = \"{$_GET['id']}\"")[0]['threadID'] , $_POST['content']);
            }
            else
            {
                echo "Unfortunately there was a problem.";
            }
        }

        ?>
        <div class="thread_wrapper">
            <div class="main_content">
                <h2><?php echo $this->db->find_all_from_table("cpBoard", "WHERE `boardID` = {$_GET['id']}")[0]['boardTitle']; ?></h2>
                <div class="thread_containers">
                    <?php
                    $threads = $this->db->find_all_from_table("cpThread", "WHERE `boardID` = {$_GET['id']}");
                    echo sizeof($threads) == 0 ? "There are no threads in this board" : "";
                    foreach ($threads as $thread)
                    {
                        $this->single_thread($thread);
                    }
                    ?>
                </div>
                <p><a class="button" href="index.php"><< Back</a> </p>
            </div>
        </div>

        <hr />

        <div id="thread_form" class = "<?php echoIfClearanceGranted(1,"show","hide")?>">
            <form class="thread_form"  name="new_thread" action="" method="post">
                <h4>Create a New Thread</h4>
                Thread Title: <br><textarea name ="title" rows ="1" cols="100"></textarea><br>
                Opening Post: <br><textarea name="content" rows="10" cols="100"></textarea><br>
                <input type="submit" class="button" name="postSubmit" value="Submit">
            </form>
        </div>
    <?php
    }

    function single_thread($thread)
    {
        ?>
            <a class="board_link" href="thread_view.php?id=<?php echo $thread['threadID']; ?>">
                <div class="board_list">
                    <h4><?php echo $thread['threadTitle']; ?></h4>
<!--                    <a href="#" class="red button">Delete</a>-->
                </div>
            </a>
    <?php
    }
}

new BoardView('Barber', array("post_styles.css"));
