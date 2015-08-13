<?php
/**
 *
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once 'includes/includes.php';


class Index extends PublicPage
{
    public function bodyContent()
    {
        ?>
            <p class="message"><?php echo $_SESSION['redirectMsg']; ?></p>

        <?php
        unset($_SESSION['redirectMsg']);
        $this->db->hashPass("password");

        ?>
            <img class="home_image" src="img/harvard-university.jpg" alt="University Front" align="left" style="margin-right:2em;"/>
            <h2>About Us</h2>
            <p>Host to some of the most prestigious professors in the South-West, Southumbria University is a turning point toward student success. Having gained outstanding Ofsted inspection results for the past five years, achieving excellence in all areas of the reports, we are sure students who choose Southumbria will be as prepared as they can be for their careers.</p>
            <p>However, university life is not all work. Situated just one mile from the river Nyte, Southumbria sits in the exuberant city of Oldfort. With a wealth of history and culture melded with seemless integration to modern culture, fashion and nightlife, Oldfort certainly has something for everyone.</p>
            <p>Oldfort has been voted best student city for three years in a row, due to a plethora of funding streams that are attained through Southumberland Council. Student's studying in Oldfort report that even after four years of study they still feel Oldfort has more to offer.</p>
            <p>Southumbria University is also host to some of the most prestigious sports teams in the country. With gold medalists in swimming, cross country, and various track events as well as coming top of the University Rugby League for four years in a row. Students studying at Southumbria are actively encouraged to participate in such activities and studies have shown that over 95% of students who undertake the discipline and responsibility of Team Southumbria, also achieve academic excellence.</p>

        <?php

    }
}
new Index('Group',array());