<?php
/**
 * This will be the class used to create pages
 * The class has been made abstract so that instantiations
 * must be created following the same format
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once '/home/unn_w14002403/public_html/case_project/includes/includes.php';

abstract class AdminPage extends Page
{

    public function __construct($authors, $arrayOfStyles)
    {
        $this->stylesheets[] = 'admin_style.css';
        $this->stylesheets[] = 'admin_nav.css';
        parent::__construct($authors, $arrayOfStyles, true);
        $this->bodyContent();
        $this->insertFooter($authors);
    }

    protected function openBody()
    {
        ?>
            <body class="<?php echo isset($_GET['view']) ? 'shaded' : ''; ?>">
                <div class="nav">
                    <div>
                        <a href="<?php echo RESOURCE_PATH; ?>/index.php"><img class="logo" src="<?php echo RESOURCE_PATH; ?>/img/Southumbria-Logo-white.png" alt="Southumbria Logo White" width="250px"/></a>
                    </div>
                    <ul>
                        <li><a href="<?php echo RESOURCE_PATH; ?>/index.php">Visit Site</a></li>
                        <li><a href="<?php echo RESOURCE_PATH; ?>/admin/index.php">Dashboard</a></li>
                        <li class="<?php echo ($_SERVER['REQUEST_URI'] == "/admin/users.php") ? "active_link" : ""; ?>"><a href="<?php echo RESOURCE_PATH; ?>/admin/users.php">User Management</a></li>
                        <li class="submenu_activator " >
                            <a href="<?php echo RESOURCE_PATH; ?>/admin/stats.php">Statistics</a>
                            <ul class="submenu_ul <?php echo (
                                $_SERVER['REQUEST_URI'] == "/admin/stats.php" ||
                                $_SERVER['REQUEST_URI'] == "/admin/geo_data.php" ||
                                $_SERVER['REQUEST_URI'] == "/admin/usage_data.php"
                            ) ? "stay_open" : ""; ?>">
                                <li class="<?php echo ($_SERVER['REQUEST_URI'] == "/admin/stats.php") ? "active_link" : ""; ?>"><a href="<?php echo RESOURCE_PATH; ?>/admin/stats.php">User Statistics</a></li>
                                <li class="<?php echo ($_SERVER['REQUEST_URI'] == "/admin/geo_data.php") ? "active_link" : ""; ?>"><a href="<?php echo RESOURCE_PATH; ?>/admin/geo_data.php">Geographical Data</a></li>
                                <li class="<?php echo ($_SERVER['REQUEST_URI'] == "/admin/usage_data.php") ? "active_link" : ""; ?>"><a href="<?php echo RESOURCE_PATH; ?>/admin/usage_data.php">Usage Data</a></li>
                            </ul>
                        </li>
                        <li class="submenu_activator " >
                            <a href="<?php echo RESOURCE_PATH; ?>/admin/board_admin.php">Message Board Admin</a>
                            <ul class="submenu_ul <?php echo (
                                $_SERVER['REQUEST_URI'] == "/admin/board_admin.php" ||
                                $_SERVER['REQUEST_URI'] == "/admin/thread_admin.php" ||
                                $_SERVER['REQUEST_URI'] == "/admin/post_admin.php"
                            ) ? "stay_open" : ""; ?>">
                                <li class="<?php echo ($_SERVER['REQUEST_URI'] == "/admin/board_admin.php") ? "active_link" : ""; ?>"><a href="<?php echo RESOURCE_PATH; ?>/admin/board_admin.php">Board Management</a></li>
                                <li class="<?php echo ($_SERVER['REQUEST_URI'] == "/admin/thread_admin.php") ? "active_link" : ""; ?>"><a href="<?php echo RESOURCE_PATH; ?>/admin/thread_admin.php">Thread Management</a></li>
                                <li class="<?php echo ($_SERVER['REQUEST_URI'] == "/admin/post_admin.php") ? "active_link" : ""; ?>"><a href="<?php echo RESOURCE_PATH; ?>/admin/post_admin.php">Post Management</a></li>
                            </ul>
                        </li>
<!--                        <li><a href="#">Tours Admin</a></li>-->
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
        <?php
    }

    public abstract function bodyContent();

    /**
     * @param $author String : the author of the page
     * accepted values are 'Group', 'Barber',
     * 'Carr', 'Coulby'
     */
    protected function insertFooter($author)
    {
     ?>
            </body>
        </html>
    <?php
    }

    /**
     * This code is the same code I used on the user admin side
     * and just implemented it here for look and feel similarity
     * and speed of implementation.
     * @author Coulby
     * @param $id
     * @param $typeOfObjectToDelete : The type such as board or post
     * to delete
     */
    protected function delete_confirm($id, $typeOfObjectToDelete)
    {

        ?>
        <a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>"><div class="overlay_shade"></div></a>
        <div class="delete_confirm_box">
            <p>Are you sure you want to delete this <?php echo strtolower($typeOfObjectToDelete); ?>?</p>
            <a style="margin-left: 3em;" href="?delID=<?php echo $id; ?>" class="button red delete_confirm">Delete</a>&nbsp; &nbsp;<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>" class="button green delete_confirm">Cancel</a>
        </div>
    <?php
    }

}