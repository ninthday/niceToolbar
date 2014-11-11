<?php
/**
 * 使用 Google 帳號登入
 * 
 * @author ninthday <jeffy@ninthday.info>
 * @version 1.0
 * @copyright (c) 2014, Jeffy Shih
 */
session_start();
require_once './inc/setup.inc.php';

// Include Google API init file
require_once _APP_PATH . 'inc/gAuth.inc.php';

//Logout
if (isset($_REQUEST['logout'])) {
    unset($_SESSION['access_token']);
    $gClient->revokeToken();
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); //redirect user back to page
}

//Authenticate code from Google OAuth Flow
//Add Access Token to Session
if (isset($_GET['code'])) {
    $gClient->authenticate($_GET['code']);
    $_SESSION['access_token'] = $gClient->getAccessToken();
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

//Set Access Token to make Request
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $gClient->setAccessToken($_SESSION['access_token']);
}

//Get User Data from Google Plus
//If New, Insert to Database
if ($gClient->getAccessToken()) {
    $userData = $objOAuthService->userinfo->get();
    try {
        if (!empty($userData)) {
            require_once _APP_PATH . 'classes/myPDOConn.Class.php';
            require_once _APP_PATH . 'classes/Authentication.Class.php';
            $pdoConn = \ninthday\niceToolbar\myPDOConn::getInstance('myPDOConnConfig.inc.php');
            $objUserAuth = new \ninthday\niceToolbar\Authentication($pdoConn);

            if ($objUserAuth->isExistandActived($userData)) {
                $_SESSION['access_token'] = $gClient->getAccessToken();
                header('Location: index.php');
            } else {
                $strMesg = "Your Account is not Active, Please contact adminstrator, thx!";
            }
        }
    } catch (Exception $exc) {
        echo $exc->getMessage();
    }
} else {
    $authUrl = $gClient->createAuthUrl();
}
?>

<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <title><?php echo _WEB_NAME ?></title>
        <meta name="description" content="This Website is for Fire and Flood Project in NCCU">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <!-- Optional theme -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

        <!-- Custom styles for this template -->
        <link href="http://getbootstrap.com/examples/dashboard/dashboard.css" rel="stylesheet">
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
            .box {font-family: Arial, sans-serif;background-color: #F1F1F1;border:0;width:340px;webkit-box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.3);box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.3);margin: 0 auto 25px;text-align:center;padding:10px 0px;}
            .box img{padding: 10px 0px;}
            .box a{color: #427fed;cursor: pointer;text-decoration: none;}
            .heading {text-align:center;padding:10px;font-family: 'Open Sans', arial;color: #555;font-size: 18px;font-weight: 400;}
            .circle-image{width:100px;height:100px;-webkit-border-radius: 50%;border-radius: 50%;}
            .welcome{font-size: 16px;font-weight: bold;text-align: center;margin: 10px 0 0;min-height: 1em;}
        </style>
    </head>
    <body>
        <div class="row">
            <div class="col-xs-3 col-md-3"></div>
            <div class="col-xs-6 col-md-6">
                <div class="jumbotron">
                    <h1>Welcome! niceToolBar ~</h1>
                    <p>This website is Toolbox for Flood & Fire Project at NCCU.</p>
                    <p>Please Sign-in with your Google Account.</p>
                    <center>
                        <!-- Show Login if the OAuth Request URL is set -->
                        <?php if (isset($authUrl)): ?>
                            <img src="images/user_circle.png" width="100px" size="100px" /><br/>
                            <a class='login' href='<?php echo $authUrl; ?>'><img class='login' src="images/sign-in-with-google.png" width="250px" size="54px" /></a>
                            <!-- Show User Profile otherwise-->
                            <?php
                        else:
                            ?>
                            <img class="circle-image" src="<?php echo $userData["picture"]; ?>" width="100px" size="100px" /><br/>
                            <p class="welcome">Welcome <a href="<?php echo $userData["link"]; ?>" /><?php echo $userData["name"]; ?></a>.</p>
                            <?php
                            if (isset($strMesg)) {
                                echo '<p class="bg-danger text-danger">' . $strMesg . '</p>';
                            }
                            ?>
                            <p><?php echo $userData["email"]; ?></p>
                            <div class='logout'><a href='?logout'>Logout</a></div>
                        <?php endif ?>
                    </center>
                </div>
            </div>
            <div class="col-xs-3 col-md-3"></div>
        </div>

    </body>
</html>
