<?php

    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $db_name = "fashion_website_wpl";

    $conn = mysqli_connect($servername, $username, $password, $db_name);

    if($conn -> connect_error)
    {
        die("Connection Failed ".connect_error);
    }

    session_start();
    function alert($msg)
    {
        echo "<script>alert('$msg');</script>";
    }

    if(isset($_REQUEST['submit']))
    {
        $user = $_REQUEST['username'];
        $pw = $_REQUEST['password'];
        
        $query = mysqli_query($conn, "select password from login_data where username = '$user'");
        $results = mysqli_fetch_array($query);

        if(empty($results))
        {
            header('Refresh: 3; url= register.html');
            echo $user. " does not exist, register first!" ."<br>". "Please wait until we redirect";
            exit();
        }
        else
        {
            $isvalid = password_verify($pw, $results[0]);
            if($isvalid)
            {
                $_SESSION['user_name'] = $user;
                header('Refresh: 3; url= ../homepage/homepage.php');
                echo "Please wait until we redirect";
                exit();
            }
            else
            {
                header('Refresh: 3; url= login.html');
                echo "Password did not match" ."<br>". "Please wait until we redirect";
                exit();
            }
        }
    }

?>