<?php
include "set_error_handler.php";

trigger_error("this is a trigger error", E_USER_ERROR);


echo "this is a msg after trigger error";
