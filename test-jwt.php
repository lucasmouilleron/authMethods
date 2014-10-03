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

<form class="form-inline">
    <a href="<?php echo getCurrentURLWithoutParams()?>?action=login&login=my-login&pass=my-password&expire=10" class="btn btn-primary">Login and create a 10s valid token</a>
    <a href="<?php echo getCurrentURLWithoutParams()?>?action=login&login=my-login&pass=my-password&expire=600" class="btn btn-primary">Login and create a 10min valid token</a>
</form>

<form class="form-inline" action="<?php echo getCurrentURLWithoutParams()?>" method="get">
    <div class="form-group">
        <input class="form-control" type="text" name="token" placeholder="A token" value="<?php if (isset($_GET["token"])) echo $_GET["token"]?>" size="30"/>
    </div>
    <input type="hidden" name="action" value="test"/>
    <input class="btn btn-primary" type="submit" name="sumbit" value="test token"/>
</form>

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
    <h2>Decoded</h2>
    <div class="alert alert-success">
        <p><strong>Token created !</strong></p>
    </div>
    <p class="well"><?php echo $newJWT?></p>
<?php endif;?>
<?php if (isset($decodedJWT)):?>
    <h2>Decoded</h2>
    <?php if($decodedJWT !== false): ?>
        <div class="alert alert-success">
            <p><strong>Token decoded and valid !</strong></p>
            <p>decoded login -> <?php echo $decodedJWT->login?></p>
        </div>
        <p class="well"><?php echo $_GET["token"]?></p>
    <?php else :?>
        <div class="alert alert-danger">
            <p><strong>Bad token !</strong></p>
            <p>Token decoded and is corrupted or expired !</p>
        </div>
    <?php endif;?>
<?php endif;?>

<!-- /////////////////////////////////////////////////////////////// -->
<?php include __DIR__."/footer.php" ?>