<?php
/**
 * This will be the class used to create pages
 * The class has been made abstract so that instantiations
 * must be created following the same format
 * @author: Coulby
 * @aithor: Barber
 * @contributor: Carr
 * @version: 23/02/2015
 */

include_once '/home/unn_w14002403/public_html/case_project/includes/includes.php';

abstract class Page
{
    public $logged_in = false;
    public $stylesheets = array();
    public $styles;
    public $error = false;
    public $db;
    public $redirectedPages = array("case_project/account_settings.php", "case_project/change_pass.php");
    public $passwordErrors = "";

    /**
     * The constructor for Page class, this is where the magic
     * happens for each page. The constructor starts/reads session
     * connects to the database and builds the page. This class is
     * abstract and can not be instantiated; therefore, sub classes
     * are needed which create the bodyContent(); method.
     * This method takes a lazy variable for authors this can either
     * be a string or an array. The common use case is that there will
     * be one author per page. However, occasionally there may be more
     * so in that instance an array will be accepted, given that all
     * strings will be converted to an array before being used.
     *
     *
     * @param $authors Array : a lazy variable which can either
     * be a string or an array. Accepted strings are 'Barber',
     * 'Carr' and 'Coulby'
     * @param $arrayOfStyles Array : Array of stylesheets to
     * be loaded into the header of each page. Stylesheets
     * must be stored in css directory or a sub directory therein
     * @author Coulby
     * @version 23/02/2015
     */
    public function __construct($authors, $arrayOfStyles, $isAdmin)
    {
        error_reporting(0);
        session_start();
        $this->db = new Database();
        if(isset($_GET))
        {
            if($_GET['lo'])
            {
                $this->logout();
                unset($_GET['lo ']);
            }
        }
//        $this->db->test_connection("cpUser");
        if(isset($_POST['login']))
        {
            if($_POST['username_login'] && $_POST['password_login'])
            {
                if($this->db->validate_login_credentials($_POST['username_login'], $_POST['password_login']))
                {
                    $row = $this->db->check_table_for_match('cpUser', 'username', $_POST['username_login']);
                    unset($row['password']);
                    if($row['accountStatus'] == 1)
                    {
                        $_SESSION = $row;
                        $_SESSION['logged_in'] = true;
                        $this->db->setLastLogin($row['userID']);
                        if($row['securityLevel'] == 0)
                        {
                            if($this->db->return_number_of_rows("cpAdminLastLogin","adminUserID", $row['userID'])>0)
                            {
                                $loginDetails = $this->db->find_all_from_table("cpAdminLastLogin","WHERE adminUserID = " . $_SESSION['userID']);
                                foreach ($loginDetails[0] as $key => $value)
                                {
                                    $_SESSION[$key] = $value;
                                }
                            }
                            $this->db->setLastAdminLogin($row['userID']);
                        }
                    }
                    else
                    {
                        echo "<p style=\"color:red; text-align:center;\">This account has been banned and therefore you cannot login</p>";
                    }
                }
                else
                {
                    $this->error = true;
                }
            }
        }

        $this->styles = $arrayOfStyles;
        if(($isAdmin && !isset($_SESSION['securityLevel'])) || ($isAdmin && $_SESSION['securityLevel'] > 0))
        {
            redirect_to(SITE_ADDRESS);
        }

        foreach ($this->redirectedPages as $redirectedPage)
        {
            $length = strlen($redirectedPage);

            if(substr($_SERVER['SCRIPT_NAME'],1,$length) == $redirectedPage && (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false))
            {
                redirect_to(SITE_ADDRESS);
            }
        }
        $this->changePassword();
        $this->insertHeader();
        $this->openBody();
        if(!isset($_SESSION['securityLevel']) || $_SESSION['securityLevel'] > 0)
        {
            $this->db->add_page_hit(); //Uncomment to start tracking usage
        }
    }

    /**
     * This forms the header and negates the need for a
     * header.php file.
     * All Pages will have this at the top of the page
     * ensuring the whole site has the same look and feel
     */
    protected function insertHeader()
    {
        ?>
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

            <html xmlns="http://www.w3.org/1999/xhtml">

            <head>

                <title>Southumbria University&trade;</title>
                <link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH; ?>/css/reset.css"/>
                <?php
                foreach($this->stylesheets as $stylesheet)
                {
                    ?>
                    <link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH; ?>/css/<?php echo $stylesheet; ?>"/>
                <?php
                }
                foreach($this->styles as $style)
                {
                    ?>
                    <link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH; ?>/css/<?php echo $style; ?>"/>
                <?php
                }
                ?>
                <link href='http://fonts.googleapis.com/css?family=Oxygen:400,700,300' rel='stylesheet' type='text/css'>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
                <meta name="google-translate-customization" content="83617e8ec585edc7-4f58fa81db1960b1-g2702467f60fcc32a-d" />

            </head>
        <?php
    }

    /**
     * This method is abstract and will be included in the sub class
     * of this one.
     *
     * This method is an extention of the insert_header() method
     * and works to add the code between the <head> tags and the
     * start of the content tags
     */
    protected abstract function openBody();

    /**
     * @param $authors Array : The author(s) of the page
     * This method takes either an array of authors or a single
     * author as a string
     * accepted values ar, 'Barber',
     * 'Carr', 'Coulby'
     */
    protected abstract function insertFooter($authors);

    /**
     * This method changes the password prior to HTML beign output
     * This is to ensure if the password is successfully changed the
     * user will be redirected to the home page and logged out.
     * It also handles the error handling for password changes.
     */
    protected function changePassword()
    {
        if($_POST['pass_reset_submit'])
        {
            unset($_POST['pass_reset_submit']);
            foreach ($_POST as $key => $value)
            {
            if(!empty($_POST[$value]))
            {
            $this->passwordErrors = "<p class=\"error\">" . ucwords(substr($key,0,strpos($key,"_"))) . " " . ucwords(substr($key,strpos($key,"_")+1)) . " cannot be blank</p>";
            }
            }
            if($_POST['new_password'] != $_POST['confirm_password'])
            {
                $this->passwordErrors = "<p class=\"error\">Passwords do not match</p>";
            }
            else
            {
                if(strlen($_POST['new_password']) < 8)
                {
                    $this->passwordErrors = "<p class=\"error\">New password must be at least 8 characters long</p>";
                }
                else
                {
                    $changeReturn = $this->db->change_password($_POST['old_password'],$_POST['new_password']);

                    if(is_bool($changeReturn))
                    {
                        if($changeReturn)
                        {
                            $this->logout();
                            $_SESSION['redirectMsg'] = "Password changed successfully, please login again.";
                            redirect_to(SITE_ADDRESS);

                        }
                        else
                        {
                            $this->passwordErrors = "<p class=\"error\">There was a problem changing your password,please try again.</p>";
                            $this->passwordErrors = "<p class=\"error\">If this problem persists please contact an administrator.</p>";
                        }
                    }
                    else
                    {
                        $this->passwordErrors = "<p class=\"error\">" . $changeReturn ."</p>";
                    }
                }
            }
        }
    }

    protected function logout()
    {
        session_destroy();
        session_start();
    }

}