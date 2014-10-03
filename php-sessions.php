<!-- /////////////////////////////////////////////////////////////// -->
<?php session_start()?>

<!-- /////////////////////////////////////////////////////////////// -->
<?php include "includes/header.php" ?>

<!-- /////////////////////////////////////////////////////////////// -->
<div class="jumbotron">
    <h1>PHP sessions</h1>
    <p><small>Last modification : <?php echo lastModification()?></small></p>
</div>

<!-- /////////////////////////////////////////////////////////////// -->
<?php

switch($_GET["action"]) {
    case "login" :
    $_SESSION["login"] = $login;
    $_SESSION["extra"] = "from session";
    break;
    case "logout" :
    unset($_SESSION["login"]);
    unset($_SESSION["extra"]);
    break;
    case "test" :
    break;
    default:;
}

?>

<!-- /////////////////////////////////////////////////////////////// -->
<?php include "includes/footer.php" ?>