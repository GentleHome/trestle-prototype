<?php
require_once dirname(__FILE__) . '/./api/helpers/constants.php';
session_start();
unset($_SESSION[USER_ID]);
header("Location: index.php");
