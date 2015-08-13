<?php
/**
 *
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once 'includes/includes.php';


class ChangePass extends PublicPage
{
    public function bodyContent()
    {
        ?>
        <style>
            table
            {
                margin:auto;
            }

            table td
            {
                padding:1.5em;
            }
            .submitbutton
            {
                text-align: right;
            }
            .error
            {
                color:red;
                font-style: italic;
            }
        </style>
        <h3>Change Password</h3>
        <p class="error"><?php echo $this->passwordErrors; ?></p>
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
        <?php
        $this->db->hashPass("password");
    }
}
new ChangePass('Coulby',array());