<?php
// this is the 'action' from the form in login.php.
// this file includes 'login.php' at the end of the file, so if there's an error, the form is redisplayed.
// this file requires the connection file and login_tools.php which does all the validation

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "includes/connect_db.php";
    require "admin_login_tools.php";

    // list() function declares separates an array into its components.
    // here, the components are returned from the validate() function.
    //		the validate() function takes 3 arguments: 1) db connection object 2) email from form 3) password from form.
    list($check, $data) = validate($dbc, $_POST["email"], $_POST["pass"]);

    if ($check) {
        // if the validate() function returned true as its first parameter:
        session_start();
        $_SESSION["adminId"] = $data["adminId"];
        $_SESSION["adminName"] = $data["adminName"];
        load("admin.php");
    } else {
        // if the validation failed, $data will contain the error message(s)
        $errors = $data;
    }

    $dbc->close();
}

include "admin_login.php";

?>
