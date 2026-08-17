<?php
require_once __DIR__ . '/../includes/auth.php';

logout_user();
header('Location: /Shopping%20Cart/auth/login.php');
exit;