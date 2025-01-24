<?php
// this page contains two functions
// 1. load 		- called from login_action with 'home.php' as argument
// 2. validate  - called form login_action with form details as arguments

// can set default values for parameters in case no arguments were specified when function was called.
function load($page = "admin_login.php")
{
    // $_SERVER['HTTP_HOST'] contains the server ip address (127.0.0.1 in this case)
    // dirname() function returns directory name as a string.
    //  $_SERVER['PHP_SELF'] contains the filename (and path) of the currently executing script. (don't know if it's this file or the one that 'include's this file.)
    $url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]);
    $url = rtrim($url, "/\\");
    $url .= "/" . $page;
    // header() function used here to redirect processing to the url provided.
    header("Location: $url");
    // exit() exits the current script, i.e. this file.
    exit();
}

function validate($dbc, $email = "", $pwd = "")
{
    $errors = [];

    if (empty($email)) {
        $errors[] = "Enter your email address. Fool.";
    } else {
        $e = $dbc->real_escape_string(trim($email));
    }

    if (empty($pwd)) {
        $errors[] = "Enter your password. Fool.";
    } else {
        $p = $dbc->real_escape_string(trim($pwd));
    }

    if (empty($errors)) {
        $q = "SELECT adminId, adminName FROM Admin WHERE adminEmail ='$e' AND adminPassword='$p'";
        $r = $dbc->query($q);
        if ($r->num_rows == 1) {
            // using fetch_array() with MYSQLI_ASSOC formats the result set as an 'associative' array - one that uses column names as keys instead of numbers
            // eg $row['user_id'] and $row['first_name'] instead of $row[0] and $row[1]
            $row = $r->fetch_array(MYSQLI_ASSOC);

            return [true, $row]; // if one row found, return 'success' and the row as an associative array ('return' exits the function immediately)
        } else {
            $errors[] = "Email address and password not found.";
        }
    }

    return [false, $errors]; // if there are errors, return 'fail' and the error array
}

?>
