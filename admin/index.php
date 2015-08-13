<?php
/**
 *
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once '../includes/includes.php';

class AdminDashHome extends AdminPage
{
    public function bodyContent()
    {
        ?>
        <div id="wrapper">
            <h1>Admin Dashboard</h1>
            <br />
            <h3>Welcome <?php echo $_SESSION['firstName']; ?></h3>
            <p style="font-weight: 600; font-style: italic;">For security reasons, please ensure that these login details match your last login.</p>
            <div style="margin: 2em;">
                <h4>Details of Last Login</h4>
                <p>The last time you logged in was <b><?php echo date("H:i:s",strtotime($_SESSION['lastLoginDate'])); ?></b> on the <b><?php echo date("jS F Y",strtotime($_SESSION['lastLoginDate'])); ?></b></p>
                <p>You last logged in from <?php echo $_SESSION['lastLoginCity']; ?>, <?php echo $_SESSION['lastLoginCountry']; ?></p>
                <p>
                    <?php
                    if($_SESSION['lastLoginIP'] == $_SERVER['REMOTE_ADDR'])
                    {
                        echo "The IP of your last login matches your current IP: " . $_SESSION['lastLoginIP'];
                    }
                    else
                    {
                        echo "The IP of your last login was: " . $_SESSION['lastLoginIP'] . ", which differs from your current IP: " . $_SERVER['REMOTE_ADDR'];
                        echo "<br />";
                        echo "You should check your last login details carefully if you use a static IP and know you last logged in from this machine.";
                    }
                    ?>
                </p>
                <p>
                    If you are unsure about whether you account has been compromised, please change your password.
                </p>
                <p class="error"><?php echo $this->passwordErrors; ?></p>
                <h4>Change Password</h4>
                <form action="" method="post">
                    <fieldset>
                        <table>
                            <tr>
                                <td><label>Old Password: </label></td>
                                <td><input type="password" name="old_password" /></td>
                            </tr>
                            <tr>
                                <td><label>New Password: </label></td>
                                <td><input type="password" name="new_password" /></td>
                            </tr>
                            <tr>
                                <td><label>Confirm Password: </label></td>
                                <td><input type="password" name="confirm_password" /></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="submitbutton"><input class="button" type="submit" name="pass_reset_submit" /></td>
                            </tr>
                        </table>
                    </fieldset>
                </form>
            </div>
        </div><!--END Wrapper-->
        <?php
    }
}
new AdminDashHome('Coulby', array("changePass.css"));
