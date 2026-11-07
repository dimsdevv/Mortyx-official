<?php
/**
 * MORTYX — Logout Handler
 * Destroys session and redirects to homepage.
 */

require_once __DIR__ . '/../includes/Auth.php';

Auth::logout();

header('Location: ../index.php');
exit;

