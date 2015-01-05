<?php

require './inc/setup.inc.php';

require_once _APP_PATH . 'classes/myPDOConn.Class.php';
require_once _APP_PATH . 'classes/URLStatistic.Class.php';

$strDataset = filter_input(INPUT_GET, 'ds', FILTER_SANITIZE_STRING);
$strBeginDay = filter_input(INPUT_GET, 'bd', FILTER_SANITIZE_STRING);
$strEndDay = filter_input(INPUT_GET, 'ed', FILTER_SANITIZE_STRING);

if (!$strDataset || !$strBeginDay || !$strEndDay) {
    echo "The parameter has problem!";
}

try {
    $pdoConn = \ninthday\niceToolbar\myPDOConn::getInstance('myPDOConnConfig.inc.php');
    $objUS = new \ninthday\niceToolbar\URLStatistic($pdoConn);

    // 設定 回傳前 30 筆排名
    $aryDomainnames = $objUS->getDailyTopNDomain($strDataset, $strBeginDay, $strEndDay, 30);
    $aryResult['rsStat'] = true;
    $aryResult['rsContents'] = $aryDomainnames;
} catch (\Exception $exc) {
    $aryResult['rsStat'] = false;
    $aryResult['rsContents'] = $exc->getMessage();
}

echo json_encode($aryResult);
