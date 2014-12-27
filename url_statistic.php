<?php
/**
 * Sample of the file
 * 
 * @author ninthday <jeffy@ninthday.info>
 */
session_start();
require './inc/setup.inc.php';

// Include Google API init file
require_once _APP_PATH . 'inc/gAuth.inc.php';

//Set Access Token to make Request
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $gClient->setAccessToken($_SESSION['access_token']);
    $userData = $objOAuthService->userinfo->get();
} else {
    header('Location: ' . _WEB_ADDR . 'gauth.php');
}

require_once _APP_PATH . 'classes/myPDOConn.Class.php';
require_once _APP_PATH . 'classes/URLStatistic.Class.php';

// 設定側邊目錄使用
$strNavOp = 'url-base';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="This Website is for Fire and Flood Project in NCCU">
        <meta name="author" content="Ninthday (jeffy@ninthday.info)">
        <title><?php echo _WEB_NAME ?></title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <!-- Optional theme -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="http://getbootstrap.com/examples/dashboard/dashboard.css" rel="stylesheet">
        <link href="style/main.css" rel="stylesheet">
        <link href="css/datepicker.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;
            }

            @media (max-width: 980px) {
                /* Enable use of floated navbar text */
                .navbar-text.pull-right {
                    float: none;
                    padding-left: 5px;
                    padding-right: 5px;
                }
            }
        </style>
    </head>
    <body>
        <!-- Include Navigation bar file -->
        <?php include_once './navigation.php'; ?>
        <div class="container-fluid">
            <div class="row">
                <!-- Include Side file -->
                <?php include_once './sidebar.php'; ?>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">URL Statistic</h1>

                    <div class="row placeholders">
                        <div class="col-xs-6 col-sm-3 placeholder">
                            <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
                            <h4>Label</h4>
                            <span class="text-muted">Something else</span>
                        </div>
                        <div class="col-xs-6 col-sm-3 placeholder">
                            <img data-src="holder.js/200x200/auto/vine" class="img-responsive" alt="Generic placeholder thumbnail">
                            <h4>Label</h4>
                            <span class="text-muted">Something else</span>
                        </div>
                        <div class="col-xs-6 col-sm-3 placeholder">
                            <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
                            <h4>Label</h4>
                            <span class="text-muted">Something else</span>
                        </div>
                        <div class="col-xs-6 col-sm-3 placeholder">
                            <img data-src="holder.js/200x200/auto/vine" class="img-responsive" alt="Generic placeholder thumbnail">
                            <h4>Label</h4>
                            <span class="text-muted">Something else</span>
                        </div>
                    </div>
                    <div class="row placeholders">
                        <?php
                        try {
                            $pdoConn = \ninthday\niceToolbar\myPDOConn::getInstance('myPDOConnConfig.inc.php');
                            $objUS = new \ninthday\niceToolbar\URLStatistic($pdoConn);
                            $aryTables = $objUS->getAllURLTableName();
                            $aryStatus = $objUS->getTablesStatus($aryTables);
                            //var_dump($aryStatus);
                        } catch (\Exception $exc) {
                            echo $exc->getMessage();
                        }
                        ?>
                    </div>

                    <h2 class="sub-header">Basic statistic</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Data Sets</th>
                                    <th>Begin</th>
                                    <th>End</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">Unshortened</th>
                                    <th class="text-right">In Process</th>
                                    <th class="text-right">Error</th>
                                    <th class="text-right">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($aryStatus as $row) {
                                    echo '<tr>';
                                    echo '<td>' . $i . '.</td>';
                                    echo '<td><a href="url_statistic_detail.php?ds=' . $row['table_name'] . '">' . $row['table_name'] . '</a></td>';
                                    echo '<td>' . $row['duration']['begin'] . '</td>';
                                    echo '<td>' . $row['duration']['end'] . '</td>';
                                    echo '<td class="text-right">' . number_format($row['basic']['total']) . '</td>';
                                    echo '<td class="text-right">' . number_format($row['basic']['unshorten']) . '</td>';
                                    echo '<td class="text-right">' . number_format($row['basic']['inproc']) . '</td>';
                                    echo '<td class="text-right">' . number_format($row['basic']['error']) . '</td>';
                                    echo '<td class="text-right">' . number_format((1-($row['basic']['inproc']/$row['basic']['total']))*100, 2) . '</td>';
                                    echo '</tr>';
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="http://getbootstrap.com/assets/js/docs.min.js"></script>
    </body>
</html>
