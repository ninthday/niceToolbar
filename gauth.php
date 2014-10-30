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
//    if (!empty($userData)) {
//        $objDBController = new DBController();
//        $existing_member = $objDBController->getUserByOAuthId($userData->id);
//        if (empty($existing_member)) {
//            $objDBController->insertOAuthUser($userData);
//        }
//    }
    $_SESSION['access_token'] = $gClient->getAccessToken();
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
                        <?php else: ?>
                            <img class="circle-image" src="<?php echo $userData["picture"]; ?>" width="100px" size="100px" /><br/>
                            <p class="welcome">Welcome <a href="<?php echo $userData["link"]; ?>" /><?php echo $userData["name"]; ?></a>.</p>
                            <p class="oauthemail"><?php echo $userData["email"]; ?></p>
                            <div class='logout'><a href='?logout'>Logout</a></div>
                        <?php endif ?>
                    </center>
                </div>
            </div>
            <div class="col-xs-3 col-md-3"></div>
        </div>

    </body>
</html>
