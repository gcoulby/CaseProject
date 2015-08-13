<?php
/**
 *
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once '../includes/includes.php';

class AdminUsageData extends AdminPage
{
    private $hits;

    public function bodyContent()
    {

        if(isset($_POST['filter']) && $_POST['filter'] >= 0)
        {
            switch($_POST['filter'])
            {
                case 0:
                    $this->hits = $this->db->find_all_from_table('cpPageHits', "WHERE  `hitDate` >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) ORDER BY `hitID` DESC");
                    break;
                case 1:
                    $this->hits = $this->db->find_all_from_table('cpPageHits', "WHERE  `hitDate` >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK) ORDER BY `hitID` DESC");
                    break;
                case 2:
                    $this->hits = $this->db->find_all_from_table('cpPageHits', "WHERE  `hitDate` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ORDER BY `hitID` DESC");
                    break;
                case 3:
                    $this->hits = $this->db->find_all_from_table('cpPageHits', "WHERE  `hitDate` >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ORDER BY `hitID` DESC");
                    break;
            }
        }
        else
        {
            $this->hits = $this->db->find_all_from_table('cpPageHits', "ORDER BY `hitID` DESC LIMIT 10");
        }
        ?>
        <div id="wrapper">
            <h1>Usage Data</h1>
            <br />
            <form action="" method="post">
                <fieldset>
                    <label for="filter">Show page hits for: </label>
                    <select id="filter" name="filter">
                        <option value="-1">Last 10 Entries</option>
                        <option <?php echo isset($_POST['filter']) && $_POST['filter'] == 0 ? "selected" : ""; ?> value="0">1 Day</option>
                        <option <?php echo $_POST['filter'] == 1 ? "selected" : ""; ?> value="1">1 Week</option>
                        <option <?php echo $_POST['filter'] == 2 ? "selected" : ""; ?> value="2">1 Month</option>
                        <option <?php echo $_POST['filter'] == 3 ? "selected" : ""; ?> value="3">1 Year</option>
                    </select>
                    <input class="button" type="submit" name="filter_sub" value="Filter" />
                </fieldset>
            </form>
            <br /><br />
            <div><table style="width:80%;">
                    <tr>
                        <th>Hit ID</th>
                        <th>Hit Date</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Page URI</th>
                        <th>Referral URI</th>
                    </tr>
                    <?php
                    $count = 0;
                    foreach($this->hits as $key => $hit)
                    {
                        $oddeven = ++$count%2 ? "odd" : "even";
                        $this->add_row($hit, $oddeven);
                    }
                    ?>
                </table>
            </div>
            <br />&nbsp;<br />
        </div><!--END Wrapper-->
    <?php
    }

    public function add_row($hitData, $oddeven)
    {
        ?>
        <tr class="user_row <?php echo $oddeven; ?>">

            <td><?php echo $hitData['hitID']; ?></td>
            <td><?php
                $date = strtotime( $hitData['hitDate'] );
                echo date( 'M d Y', $date ); ?></td>
            <td><?php echo $hitData['country']; ?></td>
            <td><?php echo $hitData['city']; ?></td>
            <td><?php echo $hitData['pageURI']; ?></td>
            <td><?php echo $hitData['referalURI']; ?></td>
        </tr>
    <?php
    }

}
new AdminUsageData('Coulby', array("stats.css","userman_style.css"));
