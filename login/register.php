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

    if(isset($_REQUEST['submit']))
    {
        $user = $_REQUEST['username'];
        $email = $_REQUEST['email'];
        $pw = $_REQUEST['password'];

        $hash = password_hash($pw, PASSWORD_DEFAULT);
        $sql = "insert into login_data (username, password, email) values ('$user', '$hash', '$email')";

        if(mysqli_query($conn, $sql))
        {
            header('Refresh: 3; url= login.html');
            echo "Successfully Registered!" ."<br>". "Please wait until we redirect";
            exit();
        }
        else
        {
            header('Refresh: 3; url= register.html');
            echo "Error ". mysqli_error($conn) ."<br>". "Please wait until we redirect";
            exit();
        }
    }
?>