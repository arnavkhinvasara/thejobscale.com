<?php
session_start();
session_destroy();

header("location: ../login/");

echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';

?>