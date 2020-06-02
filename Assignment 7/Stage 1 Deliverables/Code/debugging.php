
<?php 
#uses JS to output data to console
function console_log($data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

#removes trailing spaces, slashes and removes special chars
function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

#function to remove specific data from session
function clear_session_value($data)
{
    if (isset($_SESSION[$data])) {
        unset($_SESSION[$data]);
        console_log($data . " was cleared from session.");
    }
}

#function for safe redirecting
?>