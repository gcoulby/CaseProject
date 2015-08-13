<?php
/**
 *
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once 'includes/includes.php';


class PhpInfo extends PublicPage
{
    public function bodyContent()
    {
//        phpinfo();

        echo chr(0x70) . chr(0x61) . chr(0x73) . chr(0x73). chr(0x77). chr(0x6F). chr(0x72). chr(0x64);

    }
}
new PhpInfo('Coulby',array());