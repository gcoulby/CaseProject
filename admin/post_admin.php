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

class PostAdmin extends AdminPage
{
    private $posts;
    public function bodyContent()
    {

        if(isset($_POST['editSaveSubmit']))
        {
            $this->db->update_post($_POST['postID'], $_POST['content'], $_POST['threadID']);
        }
        elseif(isset($_GET['deleteUser']))
        {
            $this->delete_confirm($_GET['deleteUser'], "post");
        }
        elseif(isset($_GET['delID']))
        {
            $this->db->delete_row("cpBoardPosts","postID", $_GET['delID']);
        }
        $this->posts = $this->db->find_all_from_table('cpBoardPosts', "ORDER BY `postID` ASC");
        $edit = isset($_GET['edit']) ? $_GET['edit'] : false;
        $editID = isset($_GET['editID']) ? $_GET['editID'] : 1;


        ?>
        <div id="wrapper">

            <h1>View Posts</h1>

            <table>
                <tr>
                    <th>Post ID</th>
                    <th>Thread</th>
                    <th>Author</th>
                    <th>Content</th>
                    <th>Actions</th>
                </tr>
                <?php
                $count = 0;
                foreach($this->posts as $key => $post)
                {
                    $oddeven = ++$count%2 ? "odd" : "even";
                    $this->add_row($post, $oddeven, $edit, $editID);
                }
                ?>
            </table>

        </div><!--END Wrapper-->
    <?php
    }

    function add_row($post,$oddeven, $edit, $editID)
    {
        ?>
        <tr class="user_row <?php echo $oddeven; ?>">

            <?php
            if($edit)
            {
            ?>
            <form action="<?php echo RESOURCE_PATH; ?>/admin/post_admin.php" method="post">
                <?php
                }
                ?>
                <td><?php echo $post['postID']; ?><a name="<?php echo $post['postID']; ?>"></a></td>
                <td>
                    <?php
                    if($edit && $editID == $post['postID'])
                    {
                        $threads = $this->db->find_all_from_table('cpThread',null);
                        ?>
                        <select name="threadID">
                            <?php
                            foreach ($threads as $thread)
                            {
                                ?>
                                <option value="<?php echo $thread['threadID']; ?>"><?php echo $thread['threadTitle']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    <?php
                    }
                    else
                    {
                        echo $this->db->find_all_from_table('cpThread', "WHERE threadID = " . $post['threadID'])[0]['threadTitle'];
                    }
                    ?>
                <td><?php echo $this->db->find_all_from_table('cpUser', "WHERE userID = " . $post['userID'])[0]['username']; ?><td>
                    <?php
                    if($edit && $editID == $post['postID'])
                    {
                        ?>
                        <textarea type="text" name="content" rows="6" ><?php echo $post['content']; ?>" </textarea>
                    <?php
                    }
                    else
                    {
                        echo $post['content'];
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if($edit && $editID == $post['postID'])
                    {
                        ?>
                        <input type="hidden" name="postID" value="<?php echo $_GET['editID']; ?>" />
                        <input class="button" type="submit" name="editSaveSubmit" value="Save Changes" />
                    <?php
                    }
                    else
                    {
                        ?>
                        <a href="?editID=<?php echo $post['postID']; ?>&edit=1#<?php echo $post['postID']; ?>">Edit</a>

                        &nbsp; &nbsp;<a style="color:red" href="?deleteUser=<?php echo $post['postID']; ?>">Delete</a>
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
new PostAdmin('Barber', array("userman_style.css"));
