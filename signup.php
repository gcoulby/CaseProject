<?php
/**
 * This is the page subclass that will handle the registration
 * as well as handling errors with the form submission by sending
 * the results to validation methods.
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once 'includes/includes.php';

class SignUp extends PublicPage
{
    private $errors = array('position' => 0,'staff' => 0, 'username' => 0, 'name' => 0, 'surname' => 0, 'gender' => 0, 'country' => 0, 'email' => 0, 'email_confirm' => 0, 'password' => 0, 'password_confirm' => 0, 'subject' => 0);

    public function bodyContent()
    {
        if (!$this->validate_form())
        {
            if($this->db->add_user_to_database($_POST['username'],$_POST['name'],$_POST['surname'],$_POST['gender'],$_POST['country'],$_POST['email'],$_POST['password'],$_POST['position'],$_POST['staff'],$_POST['subject']))
            {
                echo "<p class='error'>User Registration Successful. Please Login.</p>";
                unset($_POST);
            }
            else
            {
                echo "<p>Registration Unsuccessful. Please Try Again.</p>";
            }
        }
        ?>
            <h1 class="title">New User Registration</h1>

            <form id="signupForm" action="" method="post">
                <fieldset>
                    <?php
                    $this->addFormSelect('position', array("staff", "student", "parent"));
                    $this->addFormField('staff', false, false);
                    $this->addFormField('username', false, false);
                    $this->addFormField('name', false, false);
                    $this->addFormField('surname', false, false);
                    $this->addFormSelect('gender', array("male", "female", "other"));
                    $this->addFormSelect('country', array("England", "Scotland", "Wales", "N. Ireland", "International"));
                    $this->addFormField('email', false, false);
                    $this->addFormField('email_confirm', false, true);
                    $this->addFormField('password', true, false);
                    $this->addFormField('password_confirm', true, true);
                    $this->addFormSelect('subject', array("Languages", "Sciences", "Technologies", "Numeracy", "Arts"));
                    ?>
                    <input name="signup" class="button" type="submit" value="submit"/><br/>
                </fieldset>
            </form>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#position').on('change', function()
                {
                    if (this.value == "1")
                    {
                        $( ".staff" ).removeClass( "hide" );
                        $( "<br />" ).insertAfter( "input.staff" );
                    }
                    else
                    {
                        $( ".staff" ).addClass( "hide" );

//                        $('.fbcommentbox').next('p').remove();
                        $( "input.staff").next("br").remove();
                    }

                })
            });
        </script>
            <!--ADD JS HERE TO CONTROL THE DISPLAY OF staff-->
        <?php

    }


    /**
     * This method handles all of the validations and posts errors messages and codes
     * to instance variables stored in this class. These are then used to check to see
     * if an error corresponds to a particular field and will change the color of the field
     * to red... outputting the error message beside it.
     * @author Coulby
     */
    function validate_form()
    {
        $errors = false;
        if ($_POST['signup'])
        {
            foreach ($this->errors as $key => $value)
            {
                if (empty($_POST[$key]))
                {
                    if($key == 'position' || $key == "gender" || $key == "country" || $key == "subject")
                    {
                        $this->errors[$key] = "1" . "Please select an option";
                    }
                    elseif($key == 'staff')
                    {
                        if($_POST['position'] == "staff")
                        {
                            $this->errors[$key] = "1" . "This field can not be empty";
                        }
                    }
                    else
                    {
                        $this->errors[$key] = "1" . "This field can not be empty";
                    }
                }
                else
                {
                    if (strpos($key, "_") == false)
                    {
                        switch ($key)
                        {
//                            case "position":
//                                if($_POST[$key])
                            case "email":
                                if ($_POST[$key] != $_POST[$key . "_confirm"])
                                {
                                    $this->errors[$key] = "1" . "Emails do not match";
                                    $this->errors[$key . "_confirm"] = "1" . "Emails do not match";
                                }
                                else
                                {
                                    $this->errors[$key] = validate_email($_POST[$key]);
                                    $this->errors[$key . "_confirm"] = validate_email($_POST[$key]);
                                    if (empty($this->errors[$key]))
                                    {
                                        if (empty($this->errors[$key]))
                                        {
                                            if ($this->db->check_table_for_match('cpUser', $key, $_POST[$key]))
                                            {
                                                $this->errors[$key] = "1" . "Email already registered please use the login form.";
                                                $this->errors[$key . "_confirm"] = "1" . "Email already registered please use the login form.";
                                            }
                                        }
                                    }
                                }

                                $errors = empty($this->errors[$key]) && empty($this->errors[$key . "_confirm"]) && !$errors ? false : true;
                                break;
                            case "username":
                                $this->errors[$key] = validate_text($_POST[$key]);
                                if (empty($this->errors[$key]))
                                {
                                    if ($this->db->check_table_for_match('cpUser', $key, $_POST[$key]))
                                    {
                                        $this->errors[$key] = "1" . "Username already exists";
                                    }
                                }
                                $errors = empty($this->errors[$key]) && !$errors ? false : true;
                                break;
                            case "password":
                                if ($_POST[$key] != $_POST[$key . "_confirm"])
                                {
                                    $this->errors[$key] = "1" . "Passwords do not match";
                                    $this->errors[$key . "_confirm"] = "1" . "Passwords do not match";
                                }
                                $this->errors[$key] = validate_length($_POST[$key], 8, 16);
                                $this->errors[$key . "_confirm"] = validate_length($_POST[$key . "_confirm"], 8, 16);
                                $errors = empty($this->errors[$key]) && empty($this->errors[$key . "_confirm"]) && !$errors ? false : true;
                                break;
                            default:
                                $this->errors[$key] = validate_text($_POST[$key]);
                                $this->errors[$key] = validate_length($_POST[$key], 1, 20);
                                $errors = empty($this->errors[$key]) && !$errors ? false : true;
                                break;
                        } // END SWITCH
                    } //END IF
                } //END ELSE
                $errors = empty($this->errors[$key]) && !$errors ? false : true;
            } // END FOREACH
        }// END IF
        else
        {
            $errors = true;
        }
        return $errors;
    } //END FUNCTION


    /**
     * This method builds up a single field for the form which
     * incorporates access to the error messagees via the validation
     * @param $name
     * @param $isPassword
     * @param $confirmField
     */
    function addFormField($name, $isPassword, $confirmField)
    {
        if (substr($this->errors[$name], 0, 1) > 0)
        {
            ?>
                <span class='error'>(<?php echo substr($this->errors[$name], 1); ?>)&nbsp;&nbsp;&nbsp;</span>
            <?php
        }
        ?>
            <label class="<?php echo (substr($this->errors[$name], 0, 1) > 0) ? "error" : ""; ?><?php echo ($name == "staff") ? " {$name} hide" : " " . $name; ?>"
                   for="<?php echo $name; ?>">
                <?php
                    echo ($confirmField) ? "Confirm " : "";
                    if ($confirmField)
                    {
                        echo ucfirst(substr($name, 0, strpos($name, "_")));
                    }
                    elseif($name == "staff")
                    {
                        echo ucfirst($name) . " ID";
                    }
                    else
                    {
                        echo ucfirst($name);
                    }
                ?>:
            </label>
            <input id="<?php echo $name; ?>" class="<?php echo (substr($this->errors[$name], 0, 1) > 0) ? "error" : ""; ?><?php echo ($name == "staff") ? " {$name} hide" : " " . $name; ?>"
                   name="<?php echo $name; ?>" type="<?php echo ($isPassword) ? 'password' : 'text'; ?>" size="20" value="<?php echo (!$isPassword) ? $_POST[$name] : ""; ?>"/>
            <?php echo ($name != "staff") ? "<br />" : ""; ?>
        <?php
    }


    /**
     * This works in the same way as the last function
     * except it makes a select box.
     * @param $name
     * @param $options
     */
    function addFormSelect($name, $options)
    {

        if (substr($this->errors[$name], 0, 1) > 0)
        {
            ?>
                <span class='error'>(<?php echo substr($this->errors[$name], 1); ?>)&nbsp;&nbsp;&nbsp;</span>
            <?php
        }
        ?>
            <label class="<?php echo (substr($this->errors[$name], 0, 1) > 0) ? "error" : ""; ?>" for="<?php echo $name; ?>"><?php echo ucwords($name); ?>: </label>
            <select id="<?php echo $name; ?>" name="<?php echo $name; ?>" class="<?php echo (substr($this->errors[$name], 0, 1) > 0) ? "error" : ""; ?>">
                <option value="">Select...</option>
                <?php
                    $i = 1;
                    foreach($options as $option)
                    {
                        ?>
                            <option value="<?php echo ($name == "position") ? $i : $option; ?>" <?php echo (strtolower($_POST[$name]) == strtolower($option)) ? "selected" : ""; ?>><?php echo ucwords($option); ?></option>
                        <?php
                        $i++;
                    }
                ?>
            </select><br />
        <?php
    }
}

new SignUp('Coulby', array("signup.css"));