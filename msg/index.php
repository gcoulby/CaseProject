<?php

include_once '../includes/includes.php';

/**
 * User: Chris
 * Date: 26/02/2015
 * Time: 11:15
 */

class MsgLandingPage extends MessageBoardPage
{
    /*
     * Creates an array $boards containing all rows of the cpBoard table of the database.
     * Iterates through the array $boards passing the individual board data to the single_board() function.
     */
    public function msg_board_body()
    {
        if(isset($_POST['boardSubmit']))
        {
            $this->db->add_board_to_database($_POST['boardTitle']);
        }

        ?>
        <div class="board_wrapper">
            <div class="main_content">

                <h2>Southumbria University Message Board</h2>

                <div class="board_containers">
                    <?php
                    $boards = $this->db->find_all_from_table("cpBoard",null);
//                    print_pre($boards);
                    foreach ($boards as $board)
                    {
                        $this->single_board($board);
                    }
                    ?>
                </div>
            </div>
        </div>

        <hr />

        <div id="board_form" class = "<?php echoIfClearanceGranted(0,"show","hide")?>">
            <form class="thread_form" name="new_board" action="" method="post">
                <h4>Create a New Board</h4>
                Board Title: <br><textarea name ="boardTitle" rows ="1" cols="100"></textarea><br>
                <input type="submit" class="button" name="boardSubmit" value="Submit">
            </form>
        </div>

    <?php
    }

    /*
     * @param $board An array containing a boardID and boardTitle, from the cpBoard table of the
     * database.
     * Displays the given board title, attaches a hyperlink to it by appending the url with the boardID
     */
    function single_board($board)
    {
//        print_pre($board); //echos the array! this allows you to see what keys you need to pass into the echo statement- is not included in final
        ?>
        <a class="board_link" href="board_view.php?id=<?php echo $board['boardID']; ?>">
        <div class="board_list">
            <h4><?php echo $board['boardTitle']; ?></h4>
        </div>
        </a>
        <?php
    }
}

new MsgLandingPage('Barber', array("post_styles.css"));
