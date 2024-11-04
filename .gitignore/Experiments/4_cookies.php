<?php
// Start a session
session_start();

// Set a cookie
setcookie("user", "John Doe", time() + 3600); // Cookie lasts for 1 hour

// Check if the cookie is set
if(isset($_COOKIE["user"])) {
    echo "Cookie 'user' is set!<br>";
    echo "Value: " . $_COOKIE["user"] . "<br>";
} else {
    echo "Cookie 'user' is not set!<br>";
}

// Check for session timeout (5 minutes for this example)
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
    // Last request was more than 5 minutes ago
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
    echo "Session timed out!<br>";
} else {
    // Update last activity time
    $_SESSION['LAST_ACTIVITY'] = time();
    echo "Session is active.<br>";
}

// To delete the cookie
if (isset($_COOKIE["user"])) {
    // Set the cookie expiration time to the past
    setcookie("user", "", time() - 3600);
    echo "Cookie 'user' has been deleted!<br>";
} else {
    echo "Cookie 'user' was not found!<br>";
}

// Delete the session
session_unset(); // Unset session variables
session_destroy(); // Destroy the session
echo "Session has been deleted!<br>";
?>
