<?php

include_once '../includes/includes.php';

/**
 *
 * @author Carr
 * @contributor Coulby | Helped with code planning and pseudo-code.
 * @version 01/03/2015
 */
class ToursMain extends PublicPage
{
    public function BodyContent()
    {
        $this->db->delete_old_rows("cpTour","tourDate", "tourID");
        ?>
        <div class="tour_wrapper">
            <div class="sidebar">
                <div class="filter_box">
                    <h3>Filter</h3><br />
                    <form action="" method="post">
                        <input name="date" type="date"><br />
                        <select name="tour_type">
                            <option value="accommodation">Accommodation</option>
                            <option value="library">Library</option>
                            <option value="union">Union</option>
                            <option value="computing">Computing</option>
                        </select><br />
                        <input id="spaces_checkbox" type="checkbox" name="space_available" /><label for="spaces_checkbox">Spaces Available</label><br />
                        <input type="checkbox" name="strict_check" /><label>Strict Search (Meet All Criteria)</label><br />
                        <input class="button" type="submit" name="filter_submit" value="Filter" />
                    </form>
                </div>

                <?php
                    $this->next_tour($this->db->find_all_from_table_and_sort("cpTour", "tourDate", "ASC")[0]);
                ?>

            </div>

            <div class="main_content">
                <div class="form_elements">
                    <div class="sort_selector">
                        <form method="get" action="">
                            <fieldset>
                                <select name="sort_filter">
                                    <option value="tourDate">Sort All Tours By...</option>
                                    <option value="tourName">Title</option>
                                    <option value="location">Location</option>
                                    <option value="tourDate">Date</option>
                                </select>
                                <input class="button" type="submit" value="Sort"/>
                            </fieldset>
                        </form>
                    </div>
                    <div class="search">
                        <form action="" method="post">
                            <input id="search_box" type="text" name="search_criteria" value="" />
                            <input class="search button" type="submit" name="search_submit" value="Search" />
                        </form>
                    </div>
                </div>
                <div class="tour_boxes">
                    <?php
                        if(isset($_GET['sort_filter']))
                        {
                            $tours = $this->db->find_all_from_table_and_sort("cpTour", $_GET['sort_filter'], "ASC");
                        }
                        elseif(isset($_POST['filter_submit']))
                        {
                            $tours = $this->filterTours();
                        }
                        elseif(isset($_POST['search_submit']))
                        {
                            $tours = $this->db->search_database_table("cpTour", array("description" => $_POST['search_criteria'], "tourName" => $_POST['search_criteria']), "OR");
                        }
                        else
                        {
                            $tours = $this->db->find_all_from_table_and_sort("cpTour", "tourDate", "ASC");
                        }

                        foreach ($tours as $tour)
                        {
                            $this->tour_box($tour);
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     *
     * @author Carr
     * @param $tour Array : A single tour (row) from the cpTour table
     */
    function tour_box($tour)
    {
        $tour_full = $tour['bookedUsers'] < $tour['maxUsers'] ? false : true;
        $waiting_list_full = $tour['waitingUsers'] < $tour['maxWaiting'] ? false : true;

        ?>
            <div class="tour_box">
                <h3 class="tour_box_title"><a href="tourinfo.php?id=<?php echo $tour['tourID']; ?>" alt="<?php echo $tour['tourName']; ?>"><?php echo $tour['tourName']; ?></a> </h3>
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
                <div class="tour_box_text">
                    <p style="clear: both;">Location: <?php echo $tour['location']; ?></p>
                    <p><?php $desc = $tour['description']; echo substr($desc,0,100)."..."?></p>
                    <p>Date: <?php echo date("jS F Y",strtotime($tour['tourDate'])); ?></p>
                    <p>Time: <?php echo date("H:i",strtotime($tour['tourDate'])); ?></p>
                </div>
                <div class="tour_box_img">
                    <img src="<?php echo $tour['image']; ?>" alt="<?php echo $tour['tourName']; ?> name" />
                </div>
            </div>
        <?php
    }

    /**
     *
     * @author Carr
     * @param $tour Array : A single tour (row) from the cpTour table
     */
    function next_tour($tour)
    {
        ?>
            <div class="next_tour">
                <h3>Next Tour</h3>
                <p>Tour Name: <?php echo $tour['tourName']; ?></p>
                <p>Location: <?php echo $tour['location']; ?></p>
                <p>Date: <?php echo date("jS F Y",strtotime($tour['tourDate'])); ?></p>
                <p>Time: <?php echo date("H:i",strtotime($tour['tourDate'])); ?></p>
<!--                <img src="--><?php //echo $tour['image']; ?><!--" alt="--><?php //echo $tour['tourName']; ?><!-- name" />-->
            </div>
        <?php
    }


    /**
     * This function handles the filterbox functionality and uses the same
     * search method as the search box, it loops through all the returned
     * tours and unsets any tours that are full if the user requests it
     * it also has the option to do strict searching, AND vs OR search.
     * @author Carr
     * @author Coulby
     * @return Array of Tours
     */
    function filterTours()
    {
        $strict = $_POST['strict_check'] ? "AND" : "OR";
        $tours = $this->db->search_database_table("cpTour", array("tourDate" => $_POST['date'], "tourType" => $_POST['tour_type']), $strict);
        $i=0;
        if($_POST['space_available'])
        {
            foreach ($tours as $tour)
            {
                if($tour['bookedUsers'] >= $tour['maxUsers'] && $tour['waitingUsers'] >= $tour['maxWaiting'])
                {
                    unset($tours[$i]);
                }
                $i++;
            }
        }
        return $tours;
    }
}

new ToursMain('Carr', array("tourstyle.css"));