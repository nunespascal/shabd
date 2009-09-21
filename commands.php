<?php
function get_user()
{
	session_start();
    if(isset($_SESSION["user"]))
    {
        return $_SESSION["user"];
    }

    return null;
}

function get_user_fail()
{
    $user = get_user();

    if(!$user)
    {
        header("HTTP/1.0 401 Unauthorized");
        exit;
    }

    return $user;
}

function check_wrong_method()
{
    if($_REQUEST["cmd"] == $_POST["cmd"])
        return;

    header("HTTP/1.0 403 Forbidden");
    exit();
}

if(isset($_REQUEST["cmd"]))
{
    $data = null;
    include "connect.php";

    switch($_REQUEST["cmd"])
    {
        case 'get':
            $idx = $_REQUEST["idx"];
            $result = mysql_fetch_object(mysql_query("SELECT * FROM words LIMIT " . $idx . ", 1"));
            $data = $result;
            break;

        case 'hit':
            check_wrong_method();

            $user = get_user_fail();
            $id = $_REQUEST["id"];
			$query = "INSERT INTO `performance` (`user_id`,`word_id`, `hits`, `misses`, `difficulty`)VALUES(".$user.",".$id.",1,0,0) ON DUPLICATE KEY UPDATE hits = hits + 1";
			print $query;
            $count =  mysql_query($query);
            $data = $count;
            break;

        case 'miss':
            check_wrong_method();

            $user = get_user_fail();
            $id = $_REQUEST["id"];
			$query = "INSERT INTO `performance` (`user_id`,`word_id`, `hits`, `misses`, `difficulty`)VALUES(".$user.",".$id.",0,1,0) ON DUPLICATE KEY UPDATE misses = misses + 1";
            $count =  mysql_query($query);
            $data = $count;
            break;

        case 'count':
            $row =  mysql_fetch_row(mysql_query("SELECT count(*) as count FROM words"));
            $data = $row;
            break;

        default:
    }

    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 2077 05:00:00 GMT');
    //header('Content-type: application/json');
    //header('Content-Disposition: attachment; filename="' . $_REQUEST['cmd'] . '.json"');

    echo json_encode($data);
    exit;
}
?>