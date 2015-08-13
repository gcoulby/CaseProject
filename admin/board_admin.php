<?php
/**
 *
 * @author: Barber
 * @contributor: Coulby
 *
 * Chris requested that some of his admin functionality would
 * look better and be more similar to a real world message board
 * if it was featured in the backend.
 *
 * Graham Writes: My involvement on this page was purely style based
 * for Chris to utilise the 'backend' (or admin section) he was under
 * strict instruction to keep the same look and feel with regards
 * to style and interface. This table is designed to be functionally
 * and visually similar to the user management page. However, I had
 * no involvement in how Chris made this page work.
 */

include_once '../includes/includes.php';

class BoardAdmin extends AdminPage
{
    private $boards;
    public function bodyContent()
    {

        if(isset($_POST['editSaveSubmit']))
        {
            echo $this->db->update_board($_POST['boardID'], $_POST['boardTitle']);
        }
        elseif(isset($_GET['deleteUser']))
        {
           $this->delete_confirm($_GET['deleteUser'], "board");
        }
        elseif(isset($_GET['delID']))
        {
            $this->db->delete_row("cpBoard","boardID", $_GET['delID']);
        }
        $this->boards = $this->db->find_all_from_table('cpBoard', "ORDER BY `boardID` ASC");
        $edit = isset($_GET['edit']) ? $_GET['edit'] : false;
        $editID = isset($_GET['editID']) ? $_GET['editID'] : 1;
        



        ?>
        <div id="wrapper">

            <h1>View Boards</h1>

            <table>
                <tr>
                    <th>Board ID</th>
                    <th>Board Title</th>
                    <th>Actions</th>
                </tr>
                <?php
                $count = 0;
                foreach($this->boards as $key => $board)
                {
                    $oddeven = ++$count%2 ? "odd" : "even";
                    $this->add_row($board, $oddeven, $edit, $editID);
                }
                ?>
            </table>

        </div><!--END Wrapper-->
    <?php
    }

    function add_row($board,$oddeven, $edit, $editID)
    {
        ?>
            <tr class="user_row <?php echo $oddeven; ?>">
                <?php
                 if($edit)
                 {
                     ?>
                        <form action="<?php echo RESOURCE_PATH; ?>/admin/board_admin.php" method="post">
                     <?php
                 }
                ?>
                <td><?php echo $board['boardID']; ?><a name="<?php echo $board['boardID']; ?>"></a></td>
                <td>
                    <?php
                        if($edit && $editID == $board['boardID'])
                        {
                            ?>
                                <input type="text" name="boardTitle" value="<?php echo $board['boardTitle']; ?>" />
                            <?php
                        }
                        else
                        {
                            echo $board['boardTitle'];
                        }
                    ?>
                </td>
                <td>
                    <?php
                    if($edit && $editID == $board['boardID'])
                    {
                        ?>
                        <input type="hidden" name="boardID" value="<?php echo $_GET['editID']; ?>" />
                        <input class="button" type="submit" name="editSaveSubmit" value="Save Changes" />
                    <?php
                    }
                    else
                    {
                        ?>
                        <a href="?editID=<?php echo $board['boardID']; ?>&edit=1#<?php echo $board['boardID']; ?>">Edit</a>

                        &nbsp; &nbsp;<a style="color:red" href="?deleteUser=<?php echo $board['boardID']; ?>">Delete</a>
                        <?php
                    }
                    ?>
                </td>
                <?php
                    if($edit)
                    {
                        ?>
                            
                            </form>
                        <?php


                    }
                ?>
            </tr>
        <?php
    }

    /**
     * This code is the same code I used on the user admin side
     * and just implemented it here for look and feel similarity
     * and speed of implementation.
     * @author Coulby
     * @param $id
     */
    function delete_board_confirm($id)
    {

        ?>
        <a href="users.php"><div class="overlay_shade"></div></a>
        <div class="delete_confirm_box">
            <p>Are you sure you want to delete this board?</p>
            <a style="margin-left: 3em;" href="?delID=<?php echo $id; ?>" class="button red delete_confirm">Delete</a>&nbsp; &nbsp;<a href="<?php echo RESOURCE_PATH; ?>/admin/board_admin.php" class="button green delete_confirm">Cancel</a>
        </div>
    <?php
    }
}
new BoardAdmin('Barber', array("userman_style.css"));
