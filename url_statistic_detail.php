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

$strDataset = filter_input(INPUT_GET, 'ds', FILTER_SANITIZE_STRING);
require_once _APP_PATH . 'classes/myPDOConn.Class.php';
require_once _APP_PATH . 'classes/URLStatistic.Class.php';

try {
    $pdoConn = \ninthday\niceToolbar\myPDOConn::getInstance('myPDOConnConfig.inc.php');
    $objUS = new \ninthday\niceToolbar\URLStatistic($pdoConn);
    $aryStatus = $objUS->getStatusByDataset($strDataset);
//    var_dump($aryStatus);
} catch (\Exception $exc) {
    echo $exc->getMessage();
}
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
        
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="http://getbootstrap.com/examples/dashboard/dashboard.css" rel="stylesheet">
        <link href="style/main.css" rel="stylesheet">
        <link href="resources/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet">
        <link href="resources/d3/nv.d3.css" rel="stylesheet">
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
                    <h1 class="page-header">DataSet: <?php echo $strDataset; ?></h1>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Information</div>
                                <div class="panel-body">
                                    <dl class="dl-horizontal">
                                        <dt>Begin Time</dt>
                                        <dd><?php echo $aryStatus['duration']['begin']; ?></dd>
                                        <dt>End Time</dt>
                                        <dd><?php echo $aryStatus['duration']['end']; ?></dd>
                                        <dt>Total rows</dt>
                                        <dd><?php echo number_format($aryStatus['basic']['total']) ?></dd>
                                        <dt>Unshortened</dt>
                                        <dd><?php echo number_format($aryStatus['basic']['unshorten']) ?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Workbench</div>
                                <div class="panel-body">
                                    <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <label for="duration" class="col-sm-2 control-label">Duration</label>
                                            <div class="col-sm-10">
                                                <div class="input-daterange input-group" id="datepicker">
                                                    <input type="text" class="input-sm form-control" name="start" />
                                                    <span class="input-group-addon">to</span>
                                                    <input type="text" class="input-sm form-control" name="end" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="resolution" class="col-sm-2 control-label">Resolution</label>
                                            <div class="col-sm-10">
                                                <label class="radio-inline">
                                                    <input type="radio" name="resolution" id="perdays" value="option1"> days
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="resolution" id="perhours" value="option1"> hours
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-default">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="sub-header">Basic statistic</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="chart">
                                <svg></svg>
                            </div>
                        </div>
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
        <script src="resources/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript">
            $('.input-daterange').datepicker({
                format: "yyyy-mm-dd",
                startView: 1,
                autoclose: true,
                todayHighlight: true
            });
        </script>
        <script src="resources/d3/d3.min.js"></script>
        <script src="resources/d3/nv.d3.min.js"></script>
        <script src="js/url_statistic_detail.js"></script>
    </body>
</html>
