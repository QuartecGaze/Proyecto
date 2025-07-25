
<?php   
session_start();

$_SESSION = [];
session_destroy();
header("Location: Landing Page/index.html");
exit();