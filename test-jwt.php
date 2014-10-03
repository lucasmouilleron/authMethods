<!-- /////////////////////////////////////////////////////////////// -->
<?php include __DIR__."/header.php" ?>
<?php require_once __DIR__."/libs/tools.php" ?>

<!-- /////////////////////////////////////////////////////////////// -->
<div class="jumbotron">
    <h1>JWT</h1>
    <p><small>Last modification : <?php echo lastModification()?></small></p>
</div>

<!-- /////////////////////////////////////////////////////////////// -->
<h2>Actions</h2>
<ul class="jwt">
    <li><a href="<?php echo getCurrentURLWithoutParams()?>?action=login&login=my-login&pass=my-password&expire=10">Login and create a 10s valid token</a></li>
    <li><a href="<?php echo getCurrentURLWithoutParams()?>?action=login&login=my-login&pass=my-password&expire=600">Login and create a 10min valid token</a></li>
    <li>
        <form class="form-inline" action="<?php echo getCurrentURLWithoutParams()?>" method="get">
            <div class="form-group">
                    <input class="form-control" type="text" name="token" placeholder="A token" value="<?php if (isset($_GET["token"])) echo $_GET["token"]?>" size="30"/>
            </div>
            <input type="hidden" name="action" value="test"/>
            <input class="btn btn-primary" type="submit" name="sumbit" value="test token"/>
        </form>
    </li>
</ul>

<?php

switch(@$_GET["action"]) {
    case "login" :
    // validate user against db or whatever
    $token = array(
        "login" => $_GET["login"],
        "extra" => "from jwt",
        "exp" => time() + $_GET["expire"]
        );
    $newJWT = JWT::encode($token, JWT_SERVERSIDE_PRIVATE_KEY);
    break;
    case "test" :
    try {
        $decodedJWT = JWT::decode($_GET["token"], JWT_SERVERSIDE_PRIVATE_KEY);
    } catch(Exception $ex) {
        $decodedJWT = false;
    }
    break;
    default:;
}

?>


<?php if (isset($newJWT)):?>
    <h2>Token created !</h2>
    <p class="well"><?php echo $newJWT?></p>
<?php endif;?>
<?php if (isset($decodedJWT)):?>
    <?php if($decodedJWT !== false): ?>
        <h2>Token decoded and valid !</h2>
        <p>decoded login -> <?php echo $decodedJWT->login?></p>
        <p class="well"><?php echo $_GET["token"]?></p>
    <?php else :?>
        <h2>Bad token !</h2>
        <p>Token decoded and is corrupted or expired !</p>
    <?php endif;?>
<?php endif;?>

<!-- /////////////////////////////////////////////////////////////// -->
<?php include __DIR__."/footer.php" ?>