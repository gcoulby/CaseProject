<?php
/**
 * This is the page subclass that will handle the registration
 * as well as handling errors with the form submission by sending
 * the results to validation methods.
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once 'includes/includes.php';

class AccountSettings extends PublicPage
{
    private $errors = 0;
    private $users = array();
    public function bodyContent()
    {
        if(isset($_POST['editSubmit']))
        {
            foreach($_POST as $key => $value)
            {
                if(empty($value))
                {
                    $this->errors++;
                    echo "<div style=\"color:red;\">" . ucwords($key) . " field cannot be empty" . "</div>";
                }
            }
            if($this->errors == 0)
            {
                if($this->db->edit_user_front_end($_SESSION['userID'],$_POST['firstName'],$_POST['surname'], $_POST['gender'],$_POST['email'],$_POST['country'],$_POST['subject']))
                {
                    echo "<div style=\"color:green; font-style: italic\">Profile Successfully Edited</div>";
                }
                else
                {
                    echo "<div style=\"color:red; font-style: italic\">Profile Edit Unsuccessful</div>";
                }
            }
        }
        if ($_GET['edit'])
        {
            $this->users = $this->db->find_all_from_table('cpUser', 'WHERE userID = ' . $_SESSION['userID']);
            $this->buildEditForm();
        }
        else
        {
            $this->users = $this->db->find_all_from_table('cpUser', 'WHERE userID = ' . $_SESSION['userID']);
            $this->buildProfilePage();
        }

    }

    /**
     * This method builds the profile page, which cannot be editted
     */
    public function buildProfilePage()
    {
        ?>
        <div style="padding:1em 2em;" class="view_user_box">
            <h1 style="text-align: center;"><?php echo $this->users[0]['username']; ?></h1>
            <table class="view_table">
                <tr>
                    <td>Name: </td>
                    <td><?php echo $this->users[0]['firstName']; ?></td>
                </tr>
                <tr>
                    <td>Surname: </td>
                    <td><?php echo $this->users[0]['surname']; ?></td>
                </tr>
                <tr>
                    <?php if($this->users[0]['userGroup'] == "1")
                    {
                        ?>
                        <td>Staff ID: </td>
                        <td><?php echo ucwords($this->users[0]['staffID']); ?></td>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td>Email: </td>
                    <td><?php echo $this->users[0]['email']; ?></td>
                </tr>
                <tr>
                    <td>Gender: </td>
                    <td><?php echo ucwords($this->users[0]['gender']); ?></td>
                </tr>
                <tr>
                    <td>Country: </td>
                    <td><?php echo ucwords($this->users[0]['country']); ?></td>
                </tr>
                <tr>
                    <td>Subject: </td>
                    <td><?php echo ucwords($this->users[0]['subject']); ?></td>
                </tr>
                <tr>
                    <td>Member Since: </td>
                    <td><?php
                    $date = strtotime( $this->users[0]['memberSince'] );
                    echo date( 'M d Y', $date ); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <a href="?edit=true" class="button green">Edit</a>
                    </td>
                </tr>
            </table>

            <br />
        </div>
        <?php
    }

    /**
     * This method builds the profile page which can be editted.
     */
    public function buildEditForm()
    {
        ?>
        <div class="view_user_box">
            <h1><?php echo $this->users[0]['username']; ?></h1>
            <p style="text-align: center; font-style: italic;">Should you wish to delete your account please <a href="<?php echo RESOURCE_PATH; ?>/contact.php?dU=1">click here</a> to send a message to the 'General Administrator'</p><br />
            <form action="<?php echo RESOURCE_PATH; ?>/account_settings.php" method="post">
                <fieldset>
                    <table>
                        <tr>
                            <td><label for="edit_name">Name: </label></td>
                            <td><input id="edit_name" size="30" type="text" name="firstName" value="<?php echo $this->users[0]['firstName']; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="edit_surname">Surname: </label></td>
                            <td><input id="edit_surname" size="30" type="text" name="surname" value="<?php echo $this->users[0]['surname']; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label>Email: </label></td>
                            <td><input type="text" size="30" size="30" name="email" value="<?php echo $this->users[0]['email']; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label>Gender:  </label></td>
                            <td>
                                <select name="gender">
                                    <option value="male" <?php echo $this->users[0]['gender'] == "male" ? "selected" : "";?>>Male</option>
                                    <option value="female" <?php echo $this->users[0]['gender'] == "female" ? "selected" : "";?>>Female</option>
                                    <option value="other" <?php echo $this->users[0]['gender'] == "other" ? "selected" : "";?>>Other</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Country:  </label></td>
                            <td>
                                <select name="country">
                                    <option value="England" <?php echo $this->users[0]['country'] == "England" ? "selected" : "";?>>England</option>
                                    <option value="Scotland" <?php echo $this->users[0]['country'] == "Scotland" ? "selected" : "";?>>Scotland</option>
                                    <option value="Wales" <?php echo $this->users[0]['country'] == "Wales" ? "selected" : "";?>>Wales</option>
                                    <option value="N.Ireland" <?php echo $this->users[0]['country'] == "N.Ireland" ? "selected" : "";?>>N.Ireland</option>
                                    <option value="International" <?php echo $this->users[0]['country'] == "International" ? "selected" : "";?>>International</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Subject:  </label></td>
                            <td>
                                <select name="subject">
                                    <option value="Languages" <?php echo $this->users[0]['subject'] == "Languages" ? "selected" : "";?>>Languages</option>
                                    <option value="Sciences" <?php echo $this->users[0]['subject'] == "Sciences" ? "selected" : "";?>>Sciences</option>
                                    <option value="Technology" <?php echo $this->users[0]['subject'] == "Technology" ? "selected" : "";?>>Technology</option>
                                    <option value="Numeracy" <?php echo $this->users[0]['subject'] == "Numeracy" ? "selected" : "";?>>Numeracy</option>
                                    <option value="Art" <?php echo $this->users[0]['subject'] == "Art" ? "selected" : "";?>>Art</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                        <td></td>
                        <td><input style="margin-left: 1.5em;" class="button green" type="submit" name="editSubmit" value="Save Changes" /></td>                        </tr>
                    </table>
                    </div>
                </fieldset>
            </form>
        </div>
        <?php
    }

}

new AccountSettings('Coulby', array('profile.css'));