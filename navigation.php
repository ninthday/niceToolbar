<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">niceToolBar</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li></li>
                <li><a href="#">Help</a></li>
            </ul>
            <form class="navbar-form navbar-right">
                <div class="pull-left"><img class="user-image" src="<?php echo $userData["picture"]; ?>" width="40px" size="40px" /></div>
                <div class="pull-right" style="padding-left: 10px;"><a href="<?php echo $userData["link"]; ?>">
                        <?php echo $userData["name"]; ?></a><br />
                    <a type="button" class="btn-xs btn-danger" href="<?php echo _WEB_ADDR . 'gauth.php' ?>?logout">Logout</a></div>
            </form>
        </div>
    </div>
</div>