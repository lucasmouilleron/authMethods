<!-- /////////////////////////////////////////////////////////////// -->
<?php session_start()?>

<!-- /////////////////////////////////////////////////////////////// -->
<?php include __DIR__."/header.php" ?>
<?php require_once __DIR__."/libs/tools.php" ?>

<!-- /////////////////////////////////////////////////////////////// -->
<div class="jumbotron">
    <h1>PHP sessions</h1>
    <p><small>Last modification : <?php echo lastModification()?></small></p>
</div>

<!-- /////////////////////////////////////////////////////////////// -->
<h2>Actions</h2>
<ul>
    <li><a href="<?php echo getCurrentURLWithoutParams()?>?action=login&login=my-login&pass=my-password">Login and create session</a></li>
    <li><a href="<?php echo getCurrentURLWithoutParams()?>?action=logout">Logout and destroy session</a></li>
</ul>

<?php

switch(@$_GET["action"]) {
    case "login" :
    // validate user against db or whatever
    $_SESSION["login"] = $_GET["login"];
    $_SESSION["extra"] = "from session";
    break;
    case "logout" :
    unset($_SESSION["login"]);
    unset($_SESSION["extra"]);
    break;
    default:;
}

?>

<h2>Session status</h2>
<?php if (isset($_SESSION["login"])):?>
    Session is set : <?php echo $_SESSION["login"]?>
<?php else: ?>
    Session is not set.
<?php endif;?>

<!-- /////////////////////////////////////////////////////////////// -->
<?php include __DIR__."/footer.php" ?>