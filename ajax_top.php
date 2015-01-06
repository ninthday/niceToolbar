<?php

require './inc/setup.inc.php';

require_once _APP_PATH . 'classes/myPDOConn.Class.php';
require_once _APP_PATH . 'classes/URLStatistic.Class.php';

$strOption = filter_input(INPUT_GET, 'op', FILTER_SANITIZE_STRING);
$strDataset = filter_input(INPUT_GET, 'ds', FILTER_SANITIZE_STRING);
$strBeginDay = filter_input(INPUT_GET, 'bd', FILTER_SANITIZE_STRING);
$strEndDay = filter_input(INPUT_GET, 'ed', FILTER_SANITIZE_STRING);

if (!$strOption || !$strDataset || !$strBeginDay || !$strEndDay) {
    $aryResult['rsStat'] = false;
    $aryResult['rsContents'] = "The parameter has problem!";
}

try {
    $pdoConn = \ninthday\niceToolbar\myPDOConn::getInstance('myPDOConnConfig.inc.php');
    $objUS = new \ninthday\niceToolbar\URLStatistic($pdoConn);

    switch ($strOption) {
        case 'url':
            // 設定 回傳前 30 筆排名
            $aryURLs = $objUS->getTopURLs($strDataset, $strBeginDay, $strEndDay, 30);
            $aryResult['rsStat'] = true;
            $aryResult['rsContents'] = $aryURLs;
            break;
        case 'poster':
            $aryPosters = $objUS->getTopPoster($strDataset, $strBeginDay, $strEndDay, 30);
            $aryResult['rsStat'] = true;
            $aryResult['rsContents'] = $aryPosters;
        default:
            break;
    }
} catch (\Exception $exc) {
    $aryResult['rsStat'] = false;
    $aryResult['rsContents'] = $exc->getMessage();
}

echo json_encode($aryResult);
