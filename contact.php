<?php
/**
 *
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once 'includes/includes.php';


class ContactUs extends PublicPage
{
    private $generalAdminEmail  = "";
    private $messageAdminEmail  = "";
    private $toursAdminEmail    = "";
    private $errors = 0;

    public function bodyContent()
    {

        if(isset($_POST['submit']))
        {
            $this->send_email();
        }

        ?>
        <h1>Contact Us</h1>

        <div class="img_div">
            <img class="contact_image" src="img/harvard-university.jpg" alt="University Front" align="left"/>
        </div>
        <div class="content_div">
            <h4>Address</h4>
            <p>
                Oldfort Town Grounds<br />
                Nosille Avenue<br />
                Oldfort over Nyte<br />
                Southumberland<br />
                AR6 3FG<br />
                Tel: 0237 869 9700
            </p>
            <h4>Send a Message</h4>
            <form method="post" action="">
                <label>Choose Receiver: </label><br />
                <select class="<?php echo ($this->errors == 1 || $this->errors == 6) ? "error" : ""; ?>" name="receiver">
                    <option>Select...</option>
                    <option value="1" <?php echo $this->is_errors() && $_POST['receiver'] == 1 || $_GET['dU'] ? " selected " : ""; ?>>General Administrator</option>
                    <option value="2" <?php echo $this->is_errors() && $_POST['receiver'] == 2 ? " selected " : ""; ?>>Message Board Administrator</option>
                    <option value="3" <?php echo $this->is_errors() && $_POST['receiver'] == 3 ? " selected " : ""; ?>>Tours Administrator</option>
                </select><br />
                <label>Subject: </label><br />
                <input class="<?php echo ($this->errors == 2) ? "error" : ""; ?>" type="text" name="subject" value="<?php
                if($this->is_errors())
                {
                    echo $_POST['subject'];
                }
                elseif($_GET['dU'])
                {
                    echo "Account Deletion";
                }
                ?>"/><br />
                <label>Message: </label><br />
                <textarea class="<?php echo ($this->errors == 3) ? "error" : ""; ?>" name="message" rows="5"><?php
                    if($this->is_errors())
                    {
                        echo $_POST['message'];
                    }
                    elseif($_GET['dU'])
                    {
                        echo "I would like delete my account. My account details are: \n\n";
                        echo "Username: " . $_SESSION['username'] . "\n";
                        echo "Email: " . $_SESSION['email'] . "\n";
                        echo "ID: " . $_SESSION['userID'];
                    }
                    ?>
                </textarea><br/>
                <input type="hidden" name="dUID" value="<?php echo $_SESSION['userID']; ?>" />
                <label>What is 2+2?: </label><input class="<?php echo ($this->errors == 4 || $this->errors == 5) ? "error" : ""; ?> captcha" type="text" name="captcha"/>
                <input class="button submit" type="submit" name="submit" value="Send"/>
            </form>

        </div>
    <?php
    }


    /**
     * This method sends and email to the administrators
     * if all the validations are passed. It also handles
     * errors for the form.
     */
    function send_email()
    {
        if(isset($_POST['subject']) && isset($_POST['message']) && isset($_POST['captcha']) && isset($_POST['receiver']))
        {
            $subject  = $_POST['subject'];
//            $message  = $_POST['message'];
            $message = isset($_POST['dUID']) ? $_POST['message'] . "\n Confirmation ID = " . $_POST['dUID'] : $_POST['message'];
            $captcha  = $_POST['captcha'];
            $receiver = $_POST['receiver'];

            $to=null;
            switch($receiver)
            {
                case 1:
                    $to = $this->generalAdminEmail;
                    break;
                case 2:
                    $to = $this->messageAdminEmail;
                    break;
                case 3:
                    $to = $this->toursAdminEmail;
                    break;
                default:
                    $to = null;
                    break;
            }
            if($captcha == 4)
            {
                if(!is_null($to))
                {
                    $subject = trim($subject);
                    $txt = trim($message);
                    $headers = "From: contact_us_form@southumbria.ac.uk";

                    if(mail($to,$subject,$txt,null,'-fweb@numyspace.co.uk'))
                    {
                        $this->errors = 0;
                        echo "<p style=\"color:#008000\">Message Delivered Successfully</p>";
                    }
                    else
                    {
                        $this->errors = 7;
                        echo "<p style=\"color:red\">Message Delivery was Unsuccessful</p>";
                    }
                }
                else
                {
                    $this->errors = 6;
                    echo "<p style=\"color:red\">You did not select a recipient</p>";
                }
            }
            else
            {
                $this->errors = 5;
                echo "<p style=\"color:red\">Your answer to the captcha was incorrect</p>";
            }
        }
        else
        {
            $this->errors = !isset($_POST['receiver']) ? 1 : "";
            $this->errors = !isset($_POST['subject'])  ? 2 : "";
            $this->errors = !isset($_POST['message'])  ? 3 : "";
            $this->errors = !isset($_POST['captcha'])  ? 4 : "";
            echo "<p style=\"color:red\">All fields in the form must be completed to send a message.</p>";
        }
    }

    function is_errors()
    {
        return $this->errors != 0 ? true : false;
    }
}
new ContactUs('Coulby',array("contact_style.css"));
