<?php

include_once '../includes/includes.php';

/**
 *
 * @author Connor Carr
 * @version 16/03/2015
 */

class TourInfo extends PublicPage
{
    public function BodyContent()
    {
        if(isset($_POST['join_tour']))
        {
            $this->db->add_user_to_tour($_POST['tourID'], $_SESSION['userID']);
        }
    ?>
            <div class="tour_wrapper">
                <div class="main_content">
                    <div class="tourInfo_box">
                        <?php
                        $tour = $this->db->find_all_from_table("cpTour", "WHERE `tourID` = '{$_GET['id']}'")[0];
                        $this->tour_box($tour);
                        ?>
                    </div>
                </div>
            </div>
        <?php
    }

    public function tour_box($tour)
    {
        $tour_full = $tour['bookedUsers'] < $tour['maxUsers'] ? false : true;
        $waiting_list_full = $tour['waitingUsers'] < $tour['maxWaiting'] ? false : true;
        ?>
        <div class="tourInfo_box_text">
            <h1><?php echo $tour['tourName']; ?></h1>
            <?php
            echo $tour_full ? "<span class=\"tour_full\">Tour Full</span>" : "";
            if(!$waiting_list_full && $tour_full)
            {
                ?>
                <form action="" method="post">
                    <input type="hidden" name="tour_id" value="<?php echo $id; ?>" />
                    <p><input class="waiting-list-button button" type="submit" name="waiting-submit" value="Join Waiting List" /></p>
                </form>
            <?php
            }
            ?>
            <p><?php echo $tour['description']; ?></p>
            <p>Location: <?php echo $tour['location']; ?></p>
            <p>Date: <?php echo date("jS F Y",strtotime($tour['tourDate'])); ?></p>
            <p>Time: <?php echo date("H:i",strtotime($tour['tourDate'])); ?></p>
        </div>
        <div class="tourInfo_box_img">
            <img src="<?php echo $tour['image']; ?>" alt="<?php echo $tour['tourName']; ?> name" />
        </div>
        <form action="" method="post">
        <input type="hidden" name="tour_id" value="<?php echo $id; echo $_GET['id']; ?>" />
        <input class="button" type="submit" name="join_tour" value="Join Tour" />
        </form>
        <?php print_pre($_POST); echo $_SESSION['tourID'];?>
        <?php
    }
}

new TourInfo('Carr', array("tourstyle.css"));