<?php
$aryNav = explode('-', $strNavOp);
$mainNav = $aryNav[0];
$subNav = $aryNav[1];
?>
<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <?php echo (!isset($aryNav))?'<li class="active"><a href="#">':'<li><a href="#">'; ?>Overview</a></li>
        <?php echo ($mainNav == 'ds')?'<li class="active"><a href="#">':'<li><a href="#">'; ?>Datasets</a></li>
        <?php echo ($mainNav == 'url')?'<li class="active"><a href="#">':'<li><a href="url_statistic.php">'; ?>Unshorten URL</a></li>
        <?php echo ($mainNav == 'mtn')?'<li class="active"><a href="#">':'<li><a href="#">'; ?>Mention</a></li>
    </ul>
    <ul class="nav nav-sidebar">
    <?php 
    switch ($mainNav) {
        case 'url':
            echo ($subNav == 'base')?'<li class="active"><a href="#">':'<li><a href="url_statistic.php">';
            echo 'Basic Statistic</a></li>';
            break;

        default:
            break;
    }
    ?>
    </ul>
    <div class="footer">
        <div class="container">
            <strong>Fire&Flood Proj.Nccu</strong><br>
            Since: 2010
        </div>
    </div>
</div>