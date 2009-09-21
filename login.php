<?php
function get_password_hash($password)
{
    return $password;
}

if(isset($_POST["username"]))//login
{
    include "connect.php";

    $q = "SELECT `id`,`password_hash` FROM `users` WHERE email = '".$_POST["username"]."'";
    $user = mysql_fetch_object(mysql_query($q));
    if($user!= null && $user->password_hash == get_password_hash($_POST["password"]))
    {
        session_start();
        $_SESSION['user'] = $user->id;
		print "success";
        exit;
    }
    else
    {
		print "error";
        exit;
    }
}
?>