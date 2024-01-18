<?php
// Initialize the session
session_start();

// Check if the user is logged in before destroying the session
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

    // Unset specific user-related session variables
    unset($_SESSION["loggedin"]);
    unset($_SESSION["user_id"]);
    unset($_SESSION["username"]);
    unset($_SESSION["user_type"]);

    // Destroy the session.
    session_destroy();

    // Regenerate the session ID for security
    session_regenerate_id(true);
}

// Redirect to login page
header("location: login.php");
exit;
