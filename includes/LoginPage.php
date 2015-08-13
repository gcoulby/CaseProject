<?php
/**
 * This will be the class used to create pages
 * The class has been made abstract so that instantiations
 * must be created following the same format
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once '/home/unn_w14002403/public_html/case_project/includes/includes.php';

abstract class LoginPage extends Page
{
    public $logged_in = false;
    public $styles;
    public $error;
    public $db;

    function __construct($authors, $arrayOfStyles)
    {
        $this->stylesheets[] = 'loginstyle.css'; //Set the master style sheet for public pages
        $this->stylesheets[] = 'navigation.css'; //Set the navigation style sheet for public pages
        parent::__construct($authors, $arrayOfStyles, false); //call the constructor
        $this->storePostToSession();
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
                    <div class="login_box">
                        <div class="login_header">
                            <div class="logo">
                                <a href="<?php echo SITE_ADDRESS; ?>"><img src="<?php echo RESOURCE_PATH; ?>/img/Southumbria-Logo.png" alt="Southumbria Logo" /></a>
                            </div>
                        </div>
                        <div class="login_box_content">
                        <p class="notice">The action you requested requires you to be logged in.</p>
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
        ?>
                        </div><!--END LOGIN_BOX_CONTENT-->
                    </div><!--END Login Box-->
                </body>
            </html>
        <?php
    }

    public function StorePostToSession()
    {
        if(isset($_POST))
        {
            foreach ($_POST as $key => $value)
            {
                $_SESSION['POST'][$key] = $value;
            }
        }
    }

    /**
     * This is the method that will be instantiated
     * on each page, this will contain the content that
     * will differ from page to page.
     */
    public abstract function bodyContent();
}