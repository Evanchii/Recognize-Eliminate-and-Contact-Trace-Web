<!-- Initialize Database -->
<?php include 'includes/dbconfig.php';?>
<!DOCTYPE html>
<html>
    <header>
        <title>Test</title>
    </header>
    <body>
        <?php
        $reference = $database->getReference('Test');
        echo($reference->getSnapshot()->getValue());
        ?>
    </body>
</html>