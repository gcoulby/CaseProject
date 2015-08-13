<?php
/**
 * This will be the class used to create pages
 * The class has been made abstract so that instantiations
 * must be created following the same format
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once '/home/unn_w14002403/public_html/case_project/includes/includes.php';

abstract class MessageBoardPage extends PublicPage
{
    function __construct($authors, $arrayOfStyles)
    {
        $this->stylesheets[] = 'msg_styles.css'; //Set the master style sheet for public pages
        parent::__construct($authors, $arrayOfStyles, false); //call the constructor

    }

    public function bodyContent()
    {
        ?>
            <div class="breadcrumbs">
                <p><a href="#">All Boards</a> -> <a href="#">All Thread</a> -> Current Thread</p>
            </div>
        <?php
        $this->msg_board_body();
    }

    public abstract function msg_board_body();
}