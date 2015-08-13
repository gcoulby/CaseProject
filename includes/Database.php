<?php
/**
 * This is the Database object that will be used throughout the site
 * This has been made an object for security and also to contain
 * methods regarding databases and to create another level of abstraction
 * to create further abstraction the Database is connected to
 * via PHP Data Objects (PDO)
 * @author: Coulby
 * @author: Barber
 * @contributor : Carr
 * @version: 27/02/2015
 */

require_once 'validations.php'; //get the validation functions

class Database
{
    /**
     * These private variables hold the login details and
     * and inaccessible outside this file.
     */
    private $hostname = "localhost";
    private $db_name = "";
    private $username = "";
    private $password = "";
    private $dbsn;
    private $db;
    private $error;
    private $seed = '<.Lij)(-=#';
    private $seed2 = 'X3r5(!-+=~';

    /**
     * @author: Coulby
     * This is the constructor for the database class
     * this is what establishes the connection to the
     * PDO object and saves it to $this->db
     * If there is an error on the connection it is
     * saved in $this->error.
     */
    function __construct()
    {
        $this->password = "";
        $this->dbsn = "mysql:host={$this->hostname};dbname={$this->db_name}";
        try {
            $this->db = new PDO($this->dbsn, $this->username, $this->password);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * @author: Coulby
     * This method is for testing purposes
     * simply echos the tables in a database
     * to ensure the database connectivity is
     * working.
     */
    function test_connection($table)
    {
        if ($this->db) {
            $sql = "SELECT * FROM {$table}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            print_pre($stmt->fetchAll(PDO::FETCH_ASSOC));
            echo "<p>CONNECTION SUCCESSFUL</p>";
        } else {
            echo "<p>$this->error</p>";
        }
    }


    /**
     * @author: Coulby
     * This works similar to find_all_from_table except
     * it sorts the output
     *
     * @param $table : The table to query
     * @param $column : The column in the table to sort
     * @param $order : The order in which to sort 'ASC' or 'DSC'
     * @return array : Array of rows from the table sorted.
     */
    function find_all_from_table_and_sort($table, $column, $order)
    {
        return $this->find_all_from_table($table, "ORDER BY `{$column}` {$order}");
    }

    /**
     * @author: Coulby
     * This method allows for multiple conditions to be passed
     * in with a query
     * @param $table : The table to query
     * @param $condition : The condition that needs to be met
     * for example SELECT * FROM 'cp_users' WHERE userID = 1;
     * @return array : An array of rows
     */
    function find_all_from_table($table, $condition)
    {
        $users = array();

        if ($this->db) {
            $sql = "SELECT * FROM {$table}";
            if (!is_null($condition)) {
                $sql .= " " . $condition;
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
//                    print_pre($stmt->errorInfo()); //Uncomment for error reporting
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $key => $row) {
                foreach ($row as $row_key => $value) {
                    if (is_int($row_key)) {
                        unset($row[$row_key]);
                    }
                }
                $users[] = $row;
            }
            return $users;
        }
    }

    /**
     * @author: Coulby
     * Searches the table for a match to given column
     * and field conditions returns a row if found
     * @param $table : the table to search in
     * @param $column : the column in the table
     * @param $field : the field to search for match
     * @return mixed|PDOStatement
     */
    function check_table_for_match($table, $column, $field)
    {
        if ($this->db) {
            $sql = "SELECT * FROM `{$table}` WHERE {$column}='{$field}'";
//            $stmt = $this->db->query($sql);
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $stmt = $stmt->fetch();
            foreach ($stmt as $key => $value) {
                if (is_int($key)) {
                    unset($stmt[$key]);
                }
            }
            return $stmt;
        }
    }

    /**
     * @author: Coulby
     * Searches the database based on the parameters provided
     *
     * @param $table : The table to search in
     * @param $keyValueArrayOfTerms Array : and array of
     * terms stored in a key => value pair where the key
     * is the column to search in and the value is the
     * string to look for
     * @param $andOr : can be 'AND' or 'OR' chooses whether
     * all or some of the search queries should be checked
     * @return array : Returns an array of rows that match
     * the search
     */
    function search_database_table($table, $keyValueArrayOfTerms, $andOr)
    {
        $ucAndOr = strtoupper($andOr);
        $arrayOfRows = array();
        $sql = "SELECT * FROM `{$table}`";
        if (!is_null($keyValueArrayOfTerms)) {
            $i = 0;
            foreach ($keyValueArrayOfTerms as $key => $value) {
                if ($i == 0) {
                    $sql .= " WHERE  `{$key}` LIKE '%{$value}%' ";
                } else {
                    $sql .= " " . $ucAndOr . " `{$key}` LIKE '%{$value}%' ";
                }
                $i++;
            }
        }
        echo $sql;
//        $stmt = $this->db->query($sql);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll();
        foreach ($rows as $key => $row) {
            foreach ($row as $row_key => $value) {
                if (is_int($row_key)) {
                    unset($row[$row_key]);
                }
            }
            $arrayOfRows[] = $row;
        }
        return $arrayOfRows;
    }

    /**
     * @author: Coulby
     * This adds a user to the database
     *    -bet you didn't see that coming
     * Takes a list of self explanatory variables
     * It also uses a neat PDO method called
     * bindParam() where the insert statement doesn't
     * immediately load the variable in the values
     * instead it places a placeholder variable in the values
     * this variable has the parameter bound to it whilst checking
     * it meets the requirement for example a VARCHAR of less than 45
     * chars that would match what the database expects.
     *
     * @param $username
     * @param $firstName
     * @param $surname
     * @param $gender
     * @param $country
     * @param $email
     * @param $password
     * @param $userGroup
     * @param $staffID
     * @param $subject
     * @return bool
     */
    function add_user_to_database($username, $firstName, $surname, $gender, $country, $email, $password, $userGroup, $staffID, $subject)
    {
        $member_since = date("Y-m-d");
        $staffID = empty($staffID) ? NULL : $staffID;

        $password = $this->hashPass($password);

        $sql = "INSERT INTO  cpUser(
                userID ,
                username ,
                firstName ,
                surname ,
                gender ,
                country ,
                email ,
                `password` ,
                memberSince ,
                userGroup ,
                securityLevel ,
                accountType ,
                staffID ,
                accountStatus,
                `subject`
                )
                VALUES (
                NULL ,
                :username,
                :firstName,
                :surname,
                :gender,
                :country,
                :email,
                :pass,
                :memberSince,
                :userGroup,
                '2',
                0,
                :staffID,
                '1',
                :subjectOfInterest
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR, 45);
        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR, 45);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR, 45);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR, 12);
        $stmt->bindParam(':country', $country, PDO::PARAM_STR, 12);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR, 45);
        $stmt->bindParam(':pass', $password, PDO::PARAM_STR, 100);
        $stmt->bindParam(':memberSince', $member_since);
        $stmt->bindParam(':userGroup', $userGroup, PDO::PARAM_STR, 15);
        $stmt->bindParam(':staffID', $staffID, PDO::PARAM_STR, 45);
        $stmt->bindParam(':subjectOfInterest', $subject, PDO::PARAM_STR, 45);
        $bool = $stmt->execute();
//        print_pre($stmt->errorInfo()); //Uncomment for error reporting
        return $bool;
    }

    /**
     * @author: Coulby
     * @param $password
     * @return string
     */
    function hashPass($password)
    {
        $passwordArr = $this->splitString($password);
        $seedOneArr = $this->splitString($this->seed);
        $seedTwoArr = $this->splitString($this->seed2);
        return hash("sha256", $seedOneArr[0] . $passwordArr[0] . $seedOneArr[1] . $seedTwoArr[0] . $passwordArr[1] . $seedTwoArr[1], false);
    }

    /**
     * @author: Coulby
     * @param $string
     * @return array
     */
    function splitString($string)
    {
        $split = str_split($string, strlen($string) / 2);

        if (sizeof($split) > 2) {
            $split[1] .= $split[2];
            unset($split[2]);
        }
        return $split;
    }

    /**
     * @author: Barber
     * @param $boardID
     * @param $userID
     * @param $threadTitle
     * @param $content
     */
    function add_new_thread_with_post($boardID, $userID, $threadTitle, $content)
    {
        if ($this->return_number_of_rows_by_condition("cpThread", "WHERE `threadTitle` = \"{$threadTitle}\" AND `boardID` = \"{$boardID}\"") == 0) {
            $this->add_thread_to_board($boardID, $userID, $threadTitle);
            $this->add_post_to_thread($userID, $this->find_all_from_table("cpThread", "WHERE `threadTitle` = \"{$threadTitle}\" AND `boardID` = \"{$boardID}\"")[0]['threadID'], $content);
        }
    }

    /**
     * @author: Coulby
     * @param $table
     * @param $condition
     * @return int
     */
    public function return_number_of_rows_by_condition($table, $condition)
    {
        if ($this->db) {
            $sql = "SELECT * FROM {$table}";
            if (!is_null($condition)) {
                $sql .= " " . $condition;
            }
//            $stmt = $this->db->query($sql);

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return count($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    }

    /**
     * @author: Barber
     * @param $boardID
     * @param $userID
     * @param $threadTitle
     * @return bool
     */
    function add_thread_to_board($boardID, $userID, $threadTitle)
    {
        $sql = "INSERT INTO  cpThread(
                        threadID,
                        boardID,
                        userID,
                        threadTitle
                        )
                        VALUES (
                        NULL ,
                        :boardID ,
                        :userID ,
                        :threadTitle
                        )";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':boardID', $boardID, PDO::PARAM_INT, 10);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT, 10);
        $stmt->bindParam(':threadTitle', $threadTitle, PDO::PARAM_STR, 70);
        $bool = $stmt->execute();
//        print_pre($stmt->errorInfo()); //Uncomment for error reporting
        return $bool;
    }

    /**
     * @author: Barber
     * @param $userID
     * @param $threadID
     * @param $content
     * @return bool
     */
    function add_post_to_thread($userID, $threadID, $content)
    {
        $sql = "INSERT INTO  cpBoardPosts(
                postID,
                userID ,
                threadID ,
                content
                )
                VALUES (
                NULL ,
                :userID ,
                :threadID ,
                :content
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT, 10);
        $stmt->bindParam(':threadID', $threadID, PDO::PARAM_INT, 10);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $bool = $stmt->execute();
//        print_pre($stmt->errorInfo()); //Uncomment for error reporting
        return $bool;
    }

    /**
     * @author: Coulby
     * @collaboration : Carr
     * @param $userID
     * @param $tourID
     * @return bool
     */
    public function add_user_to_tour($userID, $tourID)
    {
        $sql = "INSERT INTO  cpUserTour(
                bookingID,
                tourID ,
                userID
                )
                VALUES (
                NULL ,
                :tourID ,
                :userID
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tourID', $tourID, PDO::PARAM_INT, 10);
        $stmt->bindParam(':userID', $threadID, PDO::PARAM_INT, 10);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $bool = $stmt->execute();
//        print_pre($stmt->errorInfo()); //Uncomment for error reporting
        return $bool;
    }

    /**
     * @author: Coulby
     * @return bool
     */
    public function add_page_hit()
    {
        $hitDate = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/geo"));
        $hashedIP = $this->hashPass($ip);
        if ($this->return_number_of_rows_by_condition("cpPageHits", " WHERE DATE_ADD(hitDate, INTERVAL 5 MINUTE) >= NOW( ) AND  `hashedIP` = '{$hashedIP}'") == 0) {
            $sql = "INSERT INTO cpPageHits(
                hitDate,
                hashedIP,
                country,
                city,
                pageURI,
                referalURI
                ) VALUES (
                :hitDate,
                :hashedIP,
                :country,
                :city,
                :pageURI,
                :referalURI
                )";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':hitDate', $hitDate);
            $stmt->bindParam(':hashedIP', $hashedIP, PDO::PARAM_STR, 100);
            $stmt->bindParam(':country', $details->country, PDO::PARAM_STR, 45);
            $stmt->bindParam(':city', $details->city, PDO::PARAM_STR, 45);
            $stmt->bindParam(':pageURI', $_SERVER['REQUEST_URI'], PDO::PARAM_STR, 45);
            $stmt->bindParam(':referalURI', $_SERVER['HTTP_REFERER'], PDO::PARAM_STR, 45);
            $bool = $stmt->execute();
//        print_pre($stmt->errorInfo()); //Uncomment for error reporting
            return $bool;
        } else {
            return false;
        }
    }

    /**
     * @author: Coulby
     * This method will change the password before any HTML
     * has been output, this will allow a redirect to homepage
     * to be called if the password is changed successfully.
     * @param $oldPass
     * @param $newPass
     * @return bool|string : This can either return true or false or it will
     * return a message to be displayed
     */
    function change_password($oldPass, $newPass)
    {
        if ($this->db) {
            if ($this->validate_login_credentials($_SESSION['username'], $oldPass)) {
                $newPass = $this->hashPass(clean_input($newPass));
                $sql = "UPDATE cpUser SET `password`='{$newPass}' WHERE `userID`='{$_SESSION['userID']}'";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute();
            } else {
                return "The old password you entered does not match your current password in the database";
            }
        }
    }

    /**
     * @author: Coulby
     * This method will see if a user exists in the table of users
     * that matches the username and password entered into the login
     * form.
     *
     * @param $username : the username to validate
     * @param $password : the password to validate
     * @return bool
     */
    function validate_login_credentials($username, $password)
    {
        $username = clean_input($username);
        $password = clean_input($password);
        $password = $this->hashPass($password);
        if ($this->db) {
            $sql = "SELECT * FROM `cpUser` WHERE `username`='{$username}' AND `password`='{$password}'";
//            $stmt = $this->db->query($sql);
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
//            print_pre($stmt->errorInfo()); //Uncomment for error reporting
            return (count($stmt->fetchAll(PDO::FETCH_ASSOC)) == 1) ? true : false;
        }
    }

    /**
     * @author: Coulby
     * Updates the row of one user to include the date/time
     * that the user logged in. This will be displayed on the site
     * the NEXT time the user logs in.
     *
     * @param $id INT : The ID of the current subscriber logging in
     * @return bool : returns true if the row is updated
     */
    function setLastLogin($id)
    {
        $date = date("Y-m-d H:i:s");
        $sql = "UPDATE cpUser SET lastLogin = '{$date}' WHERE userID = {$id}";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * @author: Coulby
     * Updates the row of one admin user to include the details
     * of the last login, this is so the admin can ensure that
     * their details match.
     *
     * @param $id INT : The ID of the current subscriber logging in
     * @return bool : returns true if the row is updated
     */
    function setLastAdminLogin($id)
    {
        $date = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'];
        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/geo"));

        if ($this->return_number_of_rows('cpAdminLastLogin', 'adminUserID', $id) > 0) {
            $this->delete_row('cpAdminLastLogin', 'adminUserID', $id);
        }

        $sql = "INSERT INTO cpAdminLastLogin(
                adminUserID,
                lastLoginDate,
                lastLoginIP,
                lastLoginCountry,
                lastLoginCity
                ) VALUES (
                :adminUserID,
                :lastLoginDate,
                :lastLoginIP,
                :lastLoginCountry,
                :lastLoginCity
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':adminUserID', $id, PDO::PARAM_INT, 8);
        $stmt->bindParam(':lastLoginDate', $date);
        $stmt->bindParam(':lastLoginIP', $ip, PDO::PARAM_STR, 45);
        $stmt->bindParam(':lastLoginCountry', $details->country, PDO::PARAM_STR, 45);
        $stmt->bindParam(':lastLoginCity', $details->city, PDO::PARAM_STR, 45);
        return $stmt->execute();
    }


    /**
     * @author: Coulby
     * @param $table
     * @param $column
     * @param $value
     * @return int
     */
    public function return_number_of_rows($table, $column, $value)
    {
        $ucValue = ucwords($value);
        $lcValue = strtolower($value);
        if ($this->db) {
            $sql = "SELECT * FROM {$table} WHERE `{$column}`='{$value}'";
//            $stmt = $this->db->query($sql);
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return count($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    }

    /**
     * @author: Coulby
     * Deletes row where row ID = $id
     *
     * @param $table String : The table to search in
     * @param $column String : the column to query (most commonly unique primary key)
     * @param $id INT : the unique ID to query
     * @return bool : Returns true if row is deleted
     */
    function delete_row($table, $column, $id)
    {
        $sql = "DELETE FROM " . $table . " WHERE " . $column . " = " . $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * @author: Coulby
     * @param $id
     * @param $firstName
     * @param $surname
     * @param $staffID
     * @param $gender
     * @param $email
     * @param $country
     * @param $subject
     * @param $userGroup
     * @param $securityLevel
     * @return bool
     */
    function edit_user_admin($id, $firstName, $surname, $staffID, $gender, $email, $country, $subject, $userGroup, $securityLevel,$accountStatus)
    {
        $sql = " UPDATE `cpUser`
                    SET `firstName` = :firstName,
                        `surname` = :surname,
                        `staffID` = :staffID,
                        `email` = :email,
                        `gender` = :gender,
                        `country` = :country,
                        `subject` = :subject,
                        `userGroup` = :userGroup,
                        `securityLevel` = :securityLevel,
                        `accountStatus` = :accountStatus
                  WHERE `userID` = \"{$id}\"";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR, 45);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR, 45);
        $stmt->bindParam(':staffID', $staffID);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR, 45);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR, 12);
        $stmt->bindParam(':country', $country, PDO::PARAM_STR, 12);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR, 45);
        $stmt->bindParam(':userGroup', $userGroup, PDO::PARAM_INT, 8);
        $stmt->bindParam(':securityLevel', $securityLevel, PDO::PARAM_INT, 8);
        $stmt->bindParam(':accountStatus', $accountStatus, PDO::PARAM_INT, 8);

        $bool = $stmt->execute();
//                print_pre($stmt->errorInfo()); //Uncomment for error reporting
        return $bool;
    }

    /**
     * @author: Coulby
     * @param $id
     * @param $firstName
     * @param $surname
     * @param $gender
     * @param $email
     * @param $country
     * @param $subject
     * @return bool
     */
    function edit_user_front_end($id, $firstName, $surname, $gender, $email, $country, $subject)
    {
        $sql = " UPDATE `cpUser`
                    SET `firstName` = :firstName,
                        `surname` = :surname,
                        `email` = :email,
                        `gender` = :gender,
                        `country` = :country,
                        `subject` = :subject
                  WHERE `userID` = \"{$id}\"";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR, 45);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR, 45);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR, 45);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR, 12);
        $stmt->bindParam(':country', $country, PDO::PARAM_STR, 12);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR, 45);

        $bool = $stmt->execute();
//                print_pre($stmt->errorInfo()); //Uncomment for error reporting
        return $bool;
    }

    /**
     * @author: Coulby
     * Deletes row where the date is less than the current date
     *
     * @param $table String : The table to search in
     * @param $dateColumnName String : The column name which has date objects to be queried
     * @param $idColumnName String : The column name that holds unique ids for the table
     * @return bool : Returns true if row is deleted
     */
    function delete_old_rows($table, $dateColumnName, $idColumnName)
    {
        $bool = false;
        $rows = $this->find_all_from_table($table, "WHERE  `{$dateColumnName}` <  CURDATE()");
        foreach ($rows as $row) {
            echo "The tour with ID number {$row[$idColumnName]} has passed and would be deleted if this method was live<br />";
            $bool = $this->delete_row($table, $idColumnName, $row[$idColumnName]);
        }
        return $bool;
    }

    /**
     * @author: Barber
     * @param $boardID
     * @param $boardTitle
     * @return bool
     */
    function update_board($boardID, $boardTitle)
    {
        $sql = "UPDATE `cpBoard`
            SET `boardTitle` = :boardTitle
            WHERE `boardID` = \"{$boardID}\"";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':boardTitle', $boardTitle, PDO::PARAM_STR, 70);

        $bool = $stmt->execute();

        return $bool;
    }

    /**
     * @author: Barber
     * @param $threadID
     * @param $threadTitle
     * @param $boardID
     * @return bool
     */
    function update_thread($threadID, $threadTitle, $boardID)
    {
        $sql = "UPDATE `cpThread`
            SET `threadTitle` = :threadTitle,
                `boardID` = :boardID
            WHERE `threadID` = \"{$threadID}\"";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':threadTitle', $threadTitle, PDO::PARAM_STR, 70);
        $stmt->bindParam(':boardID', $boardID, PDO::PARAM_INT, 2);

        $bool = $stmt->execute();

        return $bool;

    }

    /**
     * @author: Barber
     * @param $postID
     * @param $content
     * @param $threadID
     * @return bool
     */
    function update_post($postID, $content, $threadID)
    {
        $sql = "UPDATE `cpBoardPosts`
            SET `content` = :content,
                `threadID` = :threadID
            WHERE `postID` = \"{$postID}\"";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->bindParam(':threadID', $threadID, PDO::PARAM_INT, 2);

        $bool = $stmt->execute();

        return $bool;
    }
}
