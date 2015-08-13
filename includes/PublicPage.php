<?php
/**
 * This will be the class used to create pages
 * The class has been made abstract so that instantiations
 * must be created following the same format
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once '/home/unn_w14002403/public_html/case_project/includes/includes.php';

abstract class PublicPage extends Page
{
    public $logged_in = false;
    public $styles;
    public $error;
    public $db;
    public $redirectMsg ="";

    function __construct($authors, $arrayOfStyles)
    {
        $this->stylesheets[] = 'style.css'; //Set the master style sheet for public pages
        $this->stylesheets[] = 'navigation.css'; //Set the navigation style sheet for public pages
        parent::__construct($authors, $arrayOfStyles, false); //call the constructor
        $this->bodyContent();
        $this->insertFooter($authors);
    }

    /**
     *  This method is an extention of the insert_header() method
     * found in the superclass Page and works to add the code
     * between the <head> tags and the start of the content tags
     */
    protected function openBody()
    {
        ?>
            <body>
                <div id="admin_bar" class="<?php echoIfClearanceGranted(10,"show","hide")?>"><!--Add a class depending on logged in status-->
                    <ul class="outer_ul">
                        <li><img src="<?php echo RESOURCE_PATH; ?>/img/Southumbria-Logo-mini.png" alt="Southumbria Mini Logo"/> </li>
                        <li class="<?php echoIfClearanceGranted(0,"show","hide")?>"><a href="<?php echo RESOURCE_PATH; ?>/admin/index.php">Admin Dashboard</a>
                            <ul style="float: left;" class="admin_bar_nav inner_ul">
                                <li><a href="<?php echo RESOURCE_PATH; ?>/admin/index.php">Dashboard</a></li>
                                <li><a href="<?php echo RESOURCE_PATH; ?>/admin/users.php">User Management</a></li>
                                <li><a href="<?php echo RESOURCE_PATH; ?>/admin/stats.php">Statistics</a></li>
                                <li><a href="<?php echo RESOURCE_PATH; ?>/admin/board_admin.php">Message Board Admin</a></li>
                            </ul>
                        </li>
                        <li class="<?php echoIfClearanceGranted(0,"show","hide")?>"><a href="<?php echo RESOURCE_PATH; ?>/admin/stats.php">Statistics</a>
                            <ul style="float: left;" class="admin_bar_nav inner_ul">
                                <li><a href="<?php echo RESOURCE_PATH; ?>/admin/stats.php">User Statistics</a></li>
                                <li><a href="<?php echo RESOURCE_PATH; ?>/admin/geo_data.php">Geographical Data</a></li>
                                <li><a href="<?php echo RESOURCE_PATH; ?>/admin/usage_data.php">Usage Data</a></li>
                            </ul>
                        </li>
                        <li class="<?php echoIfClearanceGranted(0,"show","hide")?>"><a>Message Board Admin</a>
                            <ul style="float: left;" class="admin_bar_nav inner_ul">
                                <li><a href="<?php echo RESOURCE_PATH; ?>/admin/board_admin.php">Board Management</a></li>
                                <li><a href="<?php echo RESOURCE_PATH; ?>/admin/thread_admin.php">Thread Management</a></li>
                                <li><a href="<?php echo RESOURCE_PATH; ?>/admin/post_admin.php">Post Management</a></li>
                            </ul>
                        </li>



                        <ul class="admin_bar_nav outer_ul">
                        <li class="user_drop"><a>User Settings</a>
                            <ul class="admin_bar_nav inner_ul">
                                <li>Welcome <?php echo $_SESSION['firstName']; ?></li>
                                <li><a href="<?php echo RESOURCE_PATH; ?>/account_settings.php">View Account Details</a></li>
                                <li><a href="<?php echo RESOURCE_PATH; ?>/account_settings.php?edit=true">Edit Account Details</a></li>
                                <li><a href="<?php echo RESOURCE_PATH; ?>/change_pass.php">Change Password</a></li>
                                <li><a href="<?php
                                    if(sizeof($_GET)>0)
                                    {
                                        $i=0;
                                        foreach ($_GET as $key => $value)
                                        {
                                            echo $i==0 ? "?" : "&";
                                            echo "{$key}={$value}";
                                            $i++;
                                        }
                                        echo "&lo=true";
                                    }
                                    else
                                    {
                                        echo "?lo=true";
                                    }
                                    ?>">Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="admin_bar_nav outer_ul">
                    </ul>
                </div>

            <div id="wrapper">
                <div id="header">
                    <div class="logo">
                        <a href="<?php echo SITE_ADDRESS; ?>"><img src="<?php echo RESOURCE_PATH; ?>/img/Southumbria-Logo.png" alt="Southumbria Logo" width="200px"/></a>
                    </div>
                    <div id="login_form" class="<?php echoIfClearanceGranted(10,"hide","show")?>">
                        <form action="" method="post">
                            <fieldset>
                                <label class="<?php echo $this->error ? "error":""; ?>" for="username_login">Username: </label><input class="<?php echo $this->error ? "error":""; ?>" name="username_login" type="text" size="20"/><br />
                                <label class="<?php echo $this->error ? "error":""; ?>" for="password_login">Password: </label><input class="<?php echo $this->error ? "error":""; ?>" name="password_login" type="password" size="20"/><br />

                                <label class="new_user"><a href="<?php echo RESOURCE_PATH; ?>/signup.php">New User? - Register Here</a></label><input name="login" class="button" type="submit" value="submit"/><br />
                            </fieldset>
                        </form>
                    </div>


                    <div class="nav main_nav">
                            <ul class="outer_ul">
                                <li><a href="<?php echo RESOURCE_PATH; ?>/index.php">Home</a></li>
                                <li>
                                    <a href="<?php echo RESOURCE_PATH; ?>/msg/index.php">Message Board</a>
<!--                                    <ul class="inner_ul">-->
<!--                                        <li><a href="#">BB Link 1</a></li>-->
<!--                                        <li><a href="#">BB Link 2</a></li>-->
<!--                                        <li><a href="#">BB Link 3</a></li>-->
<!--                                    </ul>-->
                                </li>
                                <li>
                                    <a href="<?php echo RESOURCE_PATH; ?>/tours/index.php">Tours</a>
<!--                                    <ul class="inner_ul">-->
<!--                                        <li><a href="#">Tours Link 1</a></li>-->
<!--                                        <li><a href="#">Tours Link 2</a></li>-->
<!--                                        <li><a href="#">Tours Link 3</a></li>-->
<!--                                    </ul>-->
                                </li>
                                <li><a href="<?php echo RESOURCE_PATH; ?>/contact.php">Contact</a></li>
                                <li class="google">
                                    <div id="google_translate_element"></div>

                                    <script type="text/javascript">
                                        function googleTranslateElementInit() {
                                            new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                                        }
                                    </script>
                                    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                                </li>
                            </ul>


                    </div><!--END NAV-->
                </div><!--END HEADER-->

                <div id="container">
        <?php

    }

    /**
     * @param $authors Array : The author(s) of the page
     * This method takes either an array of authors or a single
     * author as a string
     * accepted values ar, 'Barber',
     * 'Carr', 'Coulby'
     */
    protected function insertFooter($authors)
    {
        if(!is_array($authors))
        {
            $authors = array($authors);
        }
        ?>
                </div><!--END Container-->
                <div id="footer">
                    <p>
                        Case Project<br />
                        Page Author(s):<br />
                        <?php
                            foreach($authors as $author)
                            {
                                /*
                                 * Switch between array of authors and output the correct
                                 * string accordingly
                                 */
                                switch($author)
                                {
                                    case "Barber":
                                        echo "w14028557 - Chris Barber<br />";
                                        break;
                                    case "Carr":
                                        echo "w14036662 - Connor Carr<br />";
                                        break;
                                    case "Coulby":
                                        echo "w14002403 - Graham Coulby<br />";
                                        break;
                                    case "Group":
                                        echo "w14028557 - Chris Barber<br />";
                                        echo "w14036662 - Connor Carr<br />";
                                        echo "w14002403 - Graham Coulby<br />";
                                        break;
                                    default:
                                        break;
                                }
                            }
                        ?>
                        Copyright<sup>&copy;</sup> <?php echo date("Y"); ?> Southumbria University<sup>&trade;</sup><br />
                        </p>
                    </div><!--END Footer-->
                </div><!--END Wrapper-->

                </body>
            </html>
        <?php
    }

    /**
     * This is the method that will be instantiated
     * on each page, this will contain the content that
     * will differ from page to page.
     */
    public abstract function bodyContent();
}