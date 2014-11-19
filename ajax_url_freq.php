<?php

require './inc/setup.inc.php';

require_once _APP_PATH . 'classes/myPDOConn.Class.php';
require_once _APP_PATH . 'classes/URLStatistic.Class.php';

$strDataset = filter_input(INPUT_GET, 'ds', FILTER_SANITIZE_STRING);
$strBeginDay = filter_input(INPUT_GET, 'bd', FILTER_SANITIZE_STRING);
$strEndDay = filter_input(INPUT_GET, 'ed', FILTER_SANITIZE_STRING);
$strResolution = filter_input(INPUT_GET, 'res', FILTER_SANITIZE_STRING);

if (!$strDataset || !$strBeginDay || !$strEndDay) {
    echo "The parameter has problem!";
}

try {
    $pdoConn = \ninthday\niceToolbar\myPDOConn::getInstance('myPDOConnConfig.inc.php');

    $objUS = new \ninthday\niceToolbar\URLStatistic($pdoConn);

    if ($strResolution == "per-day") {
        $aryURLs = $objUS->getDailyURLFreq($strDataset, $strBeginDay, $strEndDay);
        $aryUsers = $objUS->getDailyUserFreq($strDataset, $strBeginDay, $strEndDay);
    } elseif ($strResolution == "per-hour") {
        $aryURLs = $objUS->getHourlyURLFreq($strDataset, $strBeginDay, $strEndDay);
        $aryUsers = $objUS->getHourlyUserFreq($strDataset, $strBeginDay, $strEndDay);
    }

    $aryStatus = array(
        array(
            "key" => "Users",
            "bar" => true,
            "color" => "#9CC4E4",
            "values" => $aryUsers
        ),
        array(
            "key" => "URLs",
            "color" => "#F20544",
            "values" => $aryURLs
        )
    );
    echo json_encode($aryStatus);
//    var_dump($aryStatus);
} catch (Exception $exc) {
    echo $exc->getMessage();
}

