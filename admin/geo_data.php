<?php
/**
 *
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once '../includes/includes.php';

class AdminGeoData extends AdminPage
{
    private $hits;

    public function bodyContent()
    {
        $this->load_world_geo_chart_scripts();
        $this->load_uk_geo_chart_scripts();
        ?>
        <div id="wrapper">
            <h1>Worldwide Geo Data</h1>
            <br />
            <div id="regions_div" style="width: 900px; height: 500px;"></div>
            <br />&nbsp;<br />
            <hr />
            <br />
            <h1>UK Geo Data</h1>
            <br />
            <div id="uk_div" style="width: 900px; height: 500px;"></div>
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
     * @author_uri https://google-developers.appspot.com/chart/interactive/docs/gallery/geochart
     */
    public function load_world_geo_chart_scripts()
    {
        /**====================================================================/
         * My Code
         * -Build an array of data from the database
         * @author Coulby
        /*====================================================================**/
        $hits = $this->db->find_all_from_table('cpPageHits',NULL);
        $countries = array();
        $chartData = array();
        $i = 0;

        foreach ($hits as $hit)
        {
            $countries[$i] = $hits[$i]['country'];
            $i++;
        }
        $countries = array_filter(array_unique($countries));

        foreach ($countries as $country)
        {
            $chartData[$country] = $this->db->return_number_of_rows('cpPageHits','country', $country);
        }
        /**====================================================================/
         * End of My Code
        /*====================================================================**/
        ?>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">
            google.load("visualization", "1", {packages:["geochart"]});
            google.setOnLoadCallback(drawRegionsMap);

            function drawRegionsMap() {

                var data = google.visualization.arrayToDataTable([
                    ['Country', 'Visitors'],
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
                            echo "['$key', {$value}]";
                            echo (++$i === $count) ? "" : ",";
                        }
                    ?>
                /**====================================================================/
                 * End of My Code
                 /*====================================================================**/
                ]);

                var options = {
                    colors: ['#ccfccd', 'green']
                };

                var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

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
     * @author_uri https://google-developers.appspot.com/chart/interactive/docs/gallery/geochart
     */
    public function load_uk_geo_chart_scripts()
    {
        /**====================================================================/
         * My Code
         * -Build an array of data from the database
         * @author Coulby
        /*====================================================================**/
        $hits = $this->db->find_all_from_table('cpPageHits',NULL);
        $cities = array();
        $chartData = array();
        $i = 0;

        foreach ($hits as $hit)
        {
            if($hit['country'] == "GB")
            {
                $cities[] = $hit['city'];
            }
        }
        foreach ($cities as $city)
        {
            $chartData[$city] = $this->db->return_number_of_rows('cpPageHits','city', $city);
        }
        /**====================================================================/
         * End of My Code
        /*====================================================================**/
        ?>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">
            google.load("visualization", "1", {packages:["geochart"]});
            google.setOnLoadCallback(drawRegionsMap);

            function drawRegionsMap() {

                var data = google.visualization.arrayToDataTable([
                    ['City', 'Visitors'],
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
                            echo "['$key', {$value}]";
                            echo (++$i === $count) ? "" : ",";
                        }
                    ?>
                /**====================================================================/
                 * End of My Code
                 /*====================================================================**/
                ]);

                var options = {
                    region: 'GB',
                    displayMode: 'markers',
                    colors: ['#8de08e', 'green']
                };

                var chart = new google.visualization.GeoChart(document.getElementById('uk_div'));

                chart.draw(data, options);
            }
        </script>
    <?php
    }

}
new AdminGeoData('Coulby', array("stats.css","userman_style.css"));
