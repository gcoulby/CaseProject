<?php
/**
 * This file will link all of the files together
 * with one include. This is similar to a Java or
 * C buiild path however in php you have to everything
 * the long way!!!!!
 */

define("BASE_PATH", "/home/unn_w14002403/public_html/case_project");
define("SITE_ADDRESS", "http://unn-w14002403.newnumyspace.co.uk/case_project/");
define("RESOURCE_PATH", "/case_project");

require_once 'functions.php';
require_once 'validations.php';
require_once 'Database.php';
//require_once 'Page.php';
//require_once 'AdminPage.php';
require_once 'Page.php';
require_once 'AdminPage.php';
require_once 'PublicPage.php';
require_once 'LoginPage.php';
require_once 'MessageBoardPage.php';
