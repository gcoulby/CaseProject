<?php
/**
 *
 * @author: Barber
 * @contributor: Coulby
 * Graham Writes:
 *
 * Chris requested that some of his admin functionality would
 * look better and be more similar to a real world message board
 * if it was featured in the backend.
 *
 * My involvement on this page was purely style based.
 * For Chris to utilise the 'backend' (or admin section) he was under
 * strict instruction to keep the same look and feel with regards
 * to style and interface. This table is designed to be functionally
 * and visually similar to the user management page. However, I had
 * no involvement in how Chris made this page work.
 */

include_once '../includes/includes.php';

class ThreadAdmin extends AdminPage
{
    private $threads;
    public function bodyContent()
    {

        if(isset($_POST['editSaveSubmit']))
        {
            echo $this->db->update_thread($_POST['threadID'], $_POST['threadTitle'], $_POST['boardID']);
        }
        elseif(isset($_GET['deleteUser']))
        {
            $this->delete_confirm($_GET['deleteUser'], "thread");
        }
        elseif(isset($_GET['delID']))
        {
            $this->db->delete_row("cpThread","threadID", $_GET['delID']);
        }
        $this->threads = $this->db->find_all_from_table('cpThread', "ORDER BY `threadID` ASC");
        $edit = isset($_GET['edit']) ? $_GET['edit'] : false;
        $editID = isset($_GET['editID']) ? $_GET['editID'] : 1;


        ?>
        <div id="wrapper">

            <h1>View Threads</h1>

            <table>
                <tr>
                    <th>Thread ID</th>
                    <th>Board</th>
                    <th>Author</th>
                    <th>Thread Title</th>
                    <th>Actions</th>
                </tr>
                <?php
                $count = 0;
                foreach($this->threads as $key => $thread)
                {
                    $oddeven = ++$count%2 ? "odd" : "even";
                    $this->add_row($thread, $oddeven, $edit, $editID);
                }
                ?>
            </table>

        </div><!--END Wrapper-->
    <?php
    }

    function add_row($thread,$oddeven, $edit, $editID)
    {
        ?>
        <tr class="user_row <?php echo $oddeven; ?>">
            <?php
            if($edit)
            {
            ?>
            <form action="<?php echo RESOURCE_PATH; ?>/admin/thread_admin.php" method="post">
                <?php
                }
                ?>
                <td><?php echo $thread['threadID']; ?><a name="<?php echo $thread['threadID']; ?>"></a></td>
                <td>
                    <?php
                    if($edit && $editID == $thread['threadID'])
                    {
                        $boards = $this->db->find_all_from_table('cpBoard',null);
                        ?>
                        <select name="boardID">
                            <?php
                            foreach ($boards as $board)
                            {
                                ?>
                                <option value="<?php echo $board['boardID']; ?>"><?php echo $board['boardTitle']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    <?php
                    }
                    else
                    {
                        echo $this->db->find_all_from_table('cpBoard', "WHERE boardID = " . $thread['boardID'])[0]['boardTitle'];
                    }
                    ?>
                <td><?php echo $this->db->find_all_from_table('cpUser', "WHERE userID = " . $thread['userID'])[0]['username'];?><td>
                    <?php
                    if($edit && $editID == $thread['threadID'])
                    {
                        ?>
                        <input type="text" name="threadTitle" value="<?php echo $thread['threadTitle']; ?>" />
                    <?php
                    }
                    else
                    {
                        echo $thread['threadTitle'];
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if($edit && $editID == $thread['threadID'])
                    {
                        ?>
                        <input type="hidden" name="threadID" value="<?php echo $_GET['editID']; ?>" />
                        <input class="button" type="submit" name="editSaveSubmit" value="Save Changes" />
                    <?php
                    }
                    else
                    {
                        ?>
                        <a href="?editID=<?php echo $thread['threadID']; ?>&edit=1#<?php echo $thread['threadID']; ?>">Edit</a>

                        &nbsp; &nbsp;<a style="color:red" href="?deleteUser=<?php echo $thread['threadID']; ?>">Delete</a>
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
}
new ThreadAdmin('Barber', array("userman_style.css"));
