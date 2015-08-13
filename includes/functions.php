<?php
/**
 * This file will contain static functions
 * @author: Barber, Carr, Coulby
 * @version: 23/02/2015
 */

///**
// * Checks to see if a user is logged in and
// * returns a string that will be used as a class
// * name and will show or hide a div element
// * @param $logged_in : A Boolean value
// * @return string : returns 'show' if logged
// * in or 'hide' if not
// */
function echoIfClearanceGranted($securityLevel,$stringToEcho, $stringIfFalse)
{
    echo isLoggedIn() && $_SESSION['securityLevel'] <= $securityLevel ? $stringToEcho : $stringIfFalse;
}

function isLoggedIn()
{
    if(isset($_SESSION['logged_in']))
    {
        return $_SESSION['logged_in'] ? true : false;
    }
}

// wrap print_r in preformatted text tags
// usage: print_pre($value)
function print_pre($value) {
    echo "<pre>",print_r($value, true),"</pre>";
}

function redirect_to($uri)
{
    header("Location: {$uri}");
    exit;
}

