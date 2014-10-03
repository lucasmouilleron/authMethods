<!-- /////////////////////////////////////////////////////////////// -->
<?php include __DIR__."/header.php" ?>

<!-- /////////////////////////////////////////////////////////////// -->
<div class="jumbotron">
    <h1>authMethods</h1>
    <p>Client < > Server authentication and authentication transfer methods in the web context.</p>
</div>

<!-- /////////////////////////////////////////////////////////////// -->
<div class="row">
    <div class="col-md-4">
        <h1>Table of contents</h1>
        <div id="toc"></div>
    </div>
    <div class="col-md-8">
        <div id="content">
            <?php echo mdFileToHTML(README_FILE, true)?>
        </div>
    </div>
</div>

<!-- /////////////////////////////////////////////////////////////// -->
<?php include __DIR__."/footer.php" ?>