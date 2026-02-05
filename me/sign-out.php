<?php
session_start();
session_destroy();
header("Location: /web/me/sign-in.php");
exit();
?>