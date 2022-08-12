<?php

// Requirements
require_once 'vendor/autoload.php'; // Google API
require_once 'functions.inc.php';
require_once "dbh.inc.php";

// Token passed from Google
$id_token = $_POST['credential'];

// Call login Function which will verify token
loginGoogleUser($id_token);
