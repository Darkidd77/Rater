<?php
include "includes/header.html"; ?>

<!-- Display login form -->
<form action="admin_login_action.php" method="post" class="form-signin" role="form">
    <!-- Display error messages if the $errors array is set and not empty -->
    <?php if (isset($errors) && !empty($errors)) {
        echo '<p id="err_msg">Oops! There was a problem:<br>';
        foreach ($errors as $msg) {
            echo " - $msg<br>";
        }
        echo 'Please <a href="admin_login.php">try again</a>.</p>';
    } ?>

    <!-- Login form -->
	<h2 class="form-signin-heading">Login as admin</h2>

	<input type="text" name="email" placeholder="Email">
	<input type="password" name="pass"  placeholder="Password">
	<button class="btn btn-primary button" name="submit" type="submit">Login</button> <br />
	<small><a href="password.php">Reset Password?</a></small>

</form>

<?php include "includes/footer.html";
?>
