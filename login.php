<?php
/**
 *
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once 'includes/includes.php';


class Login extends LoginPage
{
    public function bodyContent()
    {
        ?>
            <form action="<?php echo $_SERVER['HTTP_REFERER']; ?>" method="post">
                <fieldset>
                    <label for="username_login">Username: </label><br />
                    <input name="username_login" type="text"/><br />
                    <label for="password_login">Password: </label><br />
                    <input name="password_login" type="password"/><br />

                    <label class="new_user"><a href="<?php echo RESOURCE_PATH; ?>/signup.php">New User? - Register Here</a></label>
                    <input name="login" class="button" type="submit" value="submit"/><br />
                </fieldset>
            </form>
        <?php
    }
}
new Login('Coulby',array());