<?php
/**
 * These methods will handle all of the validations for form fields
 * @author: Coulby
 * @version: 28/02/2015
 */

/**
 * Checks to see if a string of text is in a valid
 * email format and returns a message if not and 0 if it is
 *
 * @param $email : the string to check
 * @return string returns and error validation code followed
 * by an error message
 */
function validate_email($email)
{
    $out = "0";

    $email = clean_input($email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $out = "1" . "Please enter a valid Email.";
    }
    return $out;
}

/**
 * Checks to see if a string of text contains valid
 * Characters and returns a message if not and 0 if it does
 *
 * @param $input : the string to check
 * @return string returns and error validation code followed
 * by an error message
 */
function validate_text($input)
{
    $out = "0";
    $input = clean_input($input);
    if (!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $input)) //Alphanumeric only and must start with a letter
    {
        $out = "2" . "Please use only alphanumeric characters.";
    }
    return $out;
}

/**
 * Checks to see if a string of text is between the
 * minimum and maximum amount of chars allowed
 *
 * @param $input : the string to check
 * @param $min : the minimum number of chars allowed
 * @param $max : the maximum number of chars allowed
 * @return string returns and error validation code followed
 * by an error message
 */
function validate_length($input, $min, $max)
{
    $out = "0";
    $input = clean_input($input);
    if(strlen($input) < $min)
    {
        $out = "2" . "Password must be at least " . $min . " characters.";
    }
    elseif(strlen($input) > $max)
    {
        $out = "3" . "Password must be less than " . $max . " characters.";
    }
    return $out;
}

/**
 * ALL INPUT MUST BE CLEANED
 *
 * This method cleans input strings
 * and prevents html from being submitted
 * However this --->DOES NOT<--- escape
 * MySQL for MySQL escaping use either
 * PDO->prepare or PDO->query
 * Read More:
 * http://php.net/manual/en/pdo.query.php
 *
 * @param $input
 * @return string
 */
function clean_input($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlentities($input);
    return $input;
}