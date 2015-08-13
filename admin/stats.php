<?php
/**
 *
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once '../includes/includes.php';

class AdminStats extends AdminPage
{
    private $hits;

    public function bodyContent()
    {
        $this->load_pie_chart_scripts('gender', array("male", "female", "other"), "Users' Gender", "gender_chart");
        $this->load_bar_chart_scripts('country', array("England", "Scotland", "Wales", "N. Ireland", "International"), "Users' Nationalities", 'nationality_chart');
        $this->load_bar_chart_scripts('subject', array("Languages", "Sciences", "Technologies", "Numeracy", "Arts"), "Subjects of Interest", 'subjects_chart');
        $this->load_pie_chart_scripts('userGroup', array(0, 1, 2, 3), "User Group", "user_group_chart");
        ?>
        <div id="wrapper">
            <h1>User Statistics</h1>
            <br />
            <div id="gender_chart" class="google_chart"></div>
            <div id="nationality_chart" class="google_chart"></div>
            <div id="subjects_chart" class="google_chart row2"></div>
            <div id="user_group_chart" class="google_chart row2"></div>
        </div><!--END Wrapper-->
    <?php
    }

    /**
     * This code is part of Google's chart api
     * and has been adapted to comply with php
     * data pulled from a PDO object
     *
     * Parts which are not part of the original
     * api have been clearly marked
     *
     * @author Google Plc. & Graham Coulby
     * @date 2015
     * @author_uri https://google-developers.appspot.com/chart/interactive/docs/gallery/piechart
     */
    public function load_pie_chart_scripts($column, $values, $title, $div_name)
    {
        /**====================================================================/
         * My Code
         * -Build an array of data from the database
         * @author Coulby
        /*====================================================================**/
            $chartData = array();
            $sum = 0;
            foreach ($values as $value)
            {
                $chartData[$value] = $this->db->return_number_of_rows('cpUser',$column, $value);
                $sum += $chartData[$value];
            }
            foreach ($values as $value)
            {
                $chartData[$value] = ($chartData[$value] / $sum)*100;
            }

        /**====================================================================/
         * End of My Code
        /*====================================================================**/

        ?>
        <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>

        <script type="text/javascript">

            var arrayFromPHP = <?php json_encode($chartData); ?>
            google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Task', 'Hours per Day'],

                /**====================================================================/
                 * My Code
                 * -Insert data from PDO database as Javascript
                 * @author Coulby
                /*====================================================================**/
                    <?php
                        $i=0;
                        $count = count($chartData);

                        foreach($chartData as $key => $value)
                        {
                            if($column == "userGroup")
                            {
                                switch($key)
                                {
                                    case 0:
                                        $key = "Admin";
                                        break;
                                    case 1:
                                        $key = "Staff";
                                        break;
                                    case 2:
                                        $key = "Students";
                                        break;
                                    case 3:
                                        $key = "Parents";
                                        break;
                                    default:
                                        break;
                                }
                            }
                            echo "['";
                            echo ucwords($key);
                            echo "', {$value}]";
                            echo (++$i === $count) ? "" : ",";
                        }
                    ?>
                /**====================================================================/
                 * End of My Code
                 /*====================================================================**/

                ]);

                var options = {
                    title: "<?php echo $title; ?>",
                    is3D: true,
                    backgroundColor: "#FDFDFD",
                    slices: {
                        0: { color: '#2E3234' },
                        1: { color: '#B0B0B0' },
                        2: { color: '#909090'},
                        3: { color: '#505050' }
                    }
                };
                var chart = new google.visualization.PieChart(document.getElementById('<?php echo $div_name; ?>'));
                chart.draw(data, options);
            }
        </script>
    <?php
    }

    /**
     * This code is part of Google's chart api
     * and has been adapted to comply with php
     * data pulled from a PDO object
     *
     * Parts which are not part of the original
     * api have been clearly marked
     *
     * @author Google Plc. & Graham Coulby
     * @date 2015
     * @author_uri https://google-developers.appspot.com/chart/interactive/docs/gallery/columnchart
     */
    public function load_bar_chart_scripts($column, $values, $title, $div_name)
    {
        /**====================================================================/
         * My Code
         * -Build an array of data from the database
         * @author Coulby
        /*====================================================================**/
        $chartData = array();
        foreach ($values as $value)
        {
            $chartData[$value] = $this->db->return_number_of_rows('cpUser',$column, $value);
        }

        /**====================================================================/
         * End of My Code
         * @author Coulby
         /*====================================================================**/
        ?>
        <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['bar']}]}"></script>
        <script type="text/javascript">

            google.setOnLoadCallback(drawStuff);

            function drawStuff() {
                var data = new google.visualization.arrayToDataTable([
                    ['Country', 'Total'],
                /**====================================================================/
                 * My Code
                 * -Insert data from PDO database as Javascript
                 * @author Coulby
                 /*====================================================================**/
                    <?php
                        $i=0;
                        $count = count($chartData);

                        foreach($chartData as $key => $value)
                        {
                            echo "['";
                            echo ucwords($key);
                            echo "', {$value}]";
                            echo (++$i === $count) ? "" : ",";
                        }
                    ?>
                /**====================================================================/
                 * End of My Code
                 /*====================================================================**/
                ]);

                var options = {
                    title: "<?php echo $title; ?>",
//                    width: 200,
                    legend: { position: 'none' },
                    colors:['#2E3234','grey'],
                    axes: {
                        x: {
                            0: { side: 'bottom', label: '<?php echo ucwords($column); ?>'} // Top x-axis.
                        }
                    },
                    bar: { groupWidth: "90%"}
                };

                var chart = new google.charts.Bar(document.getElementById('<?php echo $div_name; ?>'));
                chart.draw(data, google.charts.Bar.convertOptions(options));
            };

        </script>
    <?php
    }

}
new AdminStats('Coulby', array("stats.css","userman_style.css"));
