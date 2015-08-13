<?php
/**
 *
 * @author: Coulby
 * @version: 02/03/2015
 */

include_once '../includes/includes.php';

class AdminUserManagement extends AdminPage
{
    private $users = array();

    public function bodyContent()
    {
        if(isset($_POST['editSubmit']))
        {
            $staffID=null;
            if(isset($_POST['staffID']))
            {
                $staffID = $_POST['staffID'];
            }
            $this->db->edit_user_admin($_POST['val'],$_POST['firstName'],$_POST['surname'], $staffID, $_POST['gender'],$_POST['email'],$_POST['country'],$_POST['subject'],$_POST['userGroup'],$_POST['securityLevel'],$_POST['userStatus']);
        }
        if(isset($_GET['view']))
        {
            $this->users = $this->db->find_all_from_table('cpUser', 'WHERE userID = ' . $_GET['view']);
            $this->view_user();
        }
        elseif(isset($_GET['edit']))
        {
            $this->users = $this->db->find_all_from_table('cpUser', 'WHERE userID = ' . $_GET['edit']);
            $this->edit_user();
        }
        elseif(isset($_GET['del']))
        {
            $this->users = $this->db->find_all_from_table('cpUser', 'WHERE userID = ' . $_GET['del']);
            $this->view_user();
            $this->delete_user_confirm($_GET['del']);
        }
        elseif($_GET['deleteUser'] == true)
        {
            if($this->db->return_number_of_rows('cpUser', 'userID', $_SESSION['userToDelete'] > 0))
            {
                $this->db->delete_row('cpUser', 'userID', $_SESSION['userToDelete']);
            }
        }
        if(isset($_POST['filter']) && $_POST['filter'] >= 0)
        {
            $this->users = $this->db->find_all_from_table('cpUser', 'WHERE userGroup = ' . $_POST['filter']);
        }
        else
        {
            $this->users = $this->db->find_all_from_table('cpUser', NULL);
        }
        ?>
        <div id="wrapper">
            <h1>User Management</h1>

            <br />
            <form action="" method="post">
                <fieldset>
                    <label for="filter">Filter users by: </label>
                    <select id="filter" name="filter">
                        <option value="-1">All</option>
                        <option <?php echo isset($_POST['filter']) && $_POST['filter'] == 0 ? "selected" : ""; ?> value="0">Admin</option>
                        <option <?php echo $_POST['filter'] == 1 ? "selected" : ""; ?> value="1">Staff</option>
                        <option <?php echo $_POST['filter'] == 2 ? "selected" : ""; ?> value="2">Student</option>
                        <option <?php echo $_POST['filter'] == 3 ? "selected" : ""; ?> value="3">Parent</option>
                    </select>
                    <input class="button" type="submit" name="filter_sub" value="Filter" />
                </fieldset>
            </form>
            <br /><br />
            <table style="width:80%">
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>Member Since</th>
                    <th>Actions</th>
                </tr>
                <?php
                    $count = 0;
                    foreach($this->users as $key => $user)
                    {
                        $oddeven = ++$count%2 ? "odd" : "even";
                        $this->add_user_row($user, $oddeven);
                    }
                ?>
            </table>
        </div><!--END Wrapper-->
    <?php
    }

    function view_user()
    {
        ?>
        <a href="users.php"><div class="overlay_shade"></div></a>
        <div class="view_user_box">
            <img class="user_image" src="<?php echo RESOURCE_PATH; ?>/img/avatar-placeholder.png" alt="Avatar Placeholder" />
<!--            --><?php //print_pre($this->users); ?>
            <h1><?php echo $this->users[0]['username']; ?></h1>
            <p>Name: <?php echo $this->users[0]['firstName']; ?></p>
            <p>Surname: <?php echo $this->users[0]['surname']; ?></p>
            <?php if($this->users[0]['userGroup'] == "1")
            {
                ?>
                <p style="clear: both">Staff ID: <?php echo ucwords($this->users[0]['staffID']); ?></p>
            <?php
            }
            ?>
            <p style="clear: both">Email: <?php echo $this->users[0]['email']; ?></p>
            <p>Gender: <?php echo ucwords($this->users[0]['gender']); ?></p>
            <p>Country: <?php echo ucwords($this->users[0]['country']); ?></p>
            <p>Subject: <?php echo ucwords($this->users[0]['subject']); ?></p>
            <p>Member Since: <?php
                $date = strtotime( $this->users[0]['memberSince'] );
                echo date( 'M d Y', $date ); ?></p>
            <p>User Group: <?php echo ucwords($this->users[0]['userGroup']); ?></p>
            <p>Security Level:
                <?php
                switch($this->users[0]['securityLevel'])
                {
                    case 0:
                        echo "Admin";
                        break;
                    case 1:
                        echo "Moderator";
                        break;
                    case 2:
                        echo "User";
                        break;
                    default:
                        break;
                }
                ?>
            </p>
            <p>User Status: <?php
                if($this->users[0]['accountStatus'] == 0)
                {
                    echo "Banned";
                }
                else
                {
                    echo "Active";
                }
                ?></p>
            <a href="?edit=<?php echo $_GET['view']; ?>" class="button green">Edit</a><a href="?del=<?php echo $_GET['view']; ?>" class="button red">Delete</a>
        </div>

    <?php
    }

    function delete_user_confirm($id)
    {
        $_SESSION['userToDelete'] = $id;
        ?>
            <a href="users.php"><div class="overlay_shade over2"></div></a>
            <div class="delete_confirm_box">
                <p>Are you sure you want to delete <?php echo $this->users[0]['username']; ?>?</p>
                <a style="margin-left: 3em;" href="?deleteUser=true" class="button red delete_confirm">Delete</a>&nbsp; &nbsp;<a href="<?php echo RESOURCE_PATH; ?>/admin/users.php" class="button green delete_confirm">Cancel</a>
            </div>
        <?php
    }

    function edit_user()
    {
        ?>
        <a href="users.php"><div class="overlay_shade"></div></a>
        <div class="view_user_box">
            <img class="user_image" src="<?php echo RESOURCE_PATH; ?>/img/avatar-placeholder.png" alt="Avatar Placeholder" />
            <!--            --><?php //print_pre($this->users); ?>
            <h1><?php echo $this->users[0]['username']; ?></h1>
            <form action="<?php echo RESOURCE_PATH; ?>/admin/users.php" method="post">
                <fieldset>
                    <input type="hidden" name="val" value="<?php echo $this->users[0]['userID']; ?>" />
                    <p><label for="edit_name">Name: </label><input id="edit_name" type="text" name="firstName" value="<?php echo $this->users[0]['firstName']; ?>" /></p>
                    <p><label for="edit_surname">Surname: </label><input id="edit_surname" type="text" name="surname" value="<?php echo $this->users[0]['surname']; ?>" /></p>
                    <div style="clear: both; margin-left: -12em;">
                        <?php if($this->users[0]['userGroup'] == "1")
                        {
                            ?>
                            <label>Staff ID: </label><input type="text" name="staffID" value="<?php echo $this->users[0]['staffID'];?>" />
                        <?php
                        }
                        ?>
                        <p>
                            <label>Email: </label><input type="text" size="30" name="email" value="<?php echo $this->users[0]['email']; ?>" />
                        </p>
                        <p>
                            <label>Gender:  </label>
                            <select name="gender">
                                <option value="male" <?php echo $this->users[0]['gender'] == "male" ? "selected" : "";?>>Male</option>
                                <option value="female" <?php echo $this->users[0]['gender'] == "female" ? "selected" : "";?>>Female</option>
                                <option value="other" <?php echo $this->users[0]['gender'] == "other" ? "selected" : "";?>>Other</option>
                            </select>
                        </p>
                        <p><label for="edit_country">Country: </label><input id="edit_country" type="text" name="country" value="<?php echo $this->users[0]['country']; ?>" /></p>
                        <p><label for="edit_subject">Subject: </label><input id="edit_subject" type="text" name="subject" value="<?php echo $this->users[0]['subject']; ?>" /></p>
                        <p>
                            <label>User Group:  </label>
                            <select name="userGroup">
                                <option value="0" <?php echo $this->users[0]['userGroup'] == "0" ? "selected" : "";?>>Admin</option>
                                <option value="1" <?php echo $this->users[0]['userGroup'] == "1" ? "selected" : "";?>>Staff</option>
                                <option value="2" <?php echo $this->users[0]['userGroup'] == "2" ? "selected" : "";?>>Student</option>
                                <option value="3" <?php echo $this->users[0]['userGroup'] == "3" ? "selected" : "";?>>Parent</option>
                            </select>
                        </p>
                        <!--            <p>User Group: --><?php //echo ucwords($this->users[0]['userGroup']); ?><!--</p>-->

                        <p>
                            <label>Security Level:  </label>
                            <select name="securityLevel">
                                <option value="0" <?php echo $this->users[0]['securityLevel'] == "0" ? "selected" : "";?>>Admin</option>
                                <option value="1" <?php echo $this->users[0]['securityLevel'] == "1" ? "selected" : "";?>>Moderator</option>
                                <option value="2" <?php echo $this->users[0]['securityLevel'] == "2" ? "selected" : "";?>>User</option>
                            </select>
                        </p>
                        <p>
                            <label>User Status:  </label>
                            <select name="userStatus">
                                <option value="1" <?php echo $this->users[0]['accountStatus'] == "1" ? "selected" : "";?>>Active</option>
                                <option value="0" <?php echo $this->users[0]['accountStatus'] == "0" ? "selected" : "";?>>Banned</option>
                            </select>
                        </p>
                        <input style="margin-left: 15em;" class="button green" type="submit" name="editSubmit" value="Save Changes" />
                    </div>
                </fieldset>
            </form>
        </div>

    <?php
    }

    function add_user_row($user, $oddeven)
    {
        ?>
        <tr class="user_row <?php echo $oddeven; ?>">
            <td><?php echo $user['userID']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['firstName']; ?></td>
            <td><?php echo $user['surname']; ?></td>
            <td><?php
                echo substr($user['email'],0, strpos($user['email'],"@"));
                echo " @ ";
                echo substr($user['email'], strpos($user['email'],"@") +1);
                ?>
            </td>

            <td><?php
                $date = strtotime( $user['memberSince'] );
                echo date( 'M d Y', $date ); ?></td>
            <td><a href="?view=<?php echo $user['userID']; ?>">View</a>&nbsp; &nbsp;<a href="?edit=<?php echo $user['userID']; ?>">Edit</a></td>
        </tr>
        <?php
    }
}
new AdminUserManagement('Coulby', array("userman_style.css"));
