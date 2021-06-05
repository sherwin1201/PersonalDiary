<?php include('server.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <h1>Welcome to Daily Journal</h1> 

    <button type="button" id="signup-btn">Register now</button>
    <button type="button" id="login-btn">Login</button>
    <div id="login">
        <h3>Login In</h3>
        <form action="registration.php" method="post">
            <?php include("errors.php"); ?>
            <input type="text" name="username" placeholder="Username"><br/>
            <input type="password" name="password" placeholder="Password"><br/>
            <button type="submit" name="login">Login</button>
        </form>
    </div>

    <div id="registration">
        <h3>Sign Up</h3>
        <form action="registration.php" method ="post">
            <?php include('errors.php'); ?>
            <input type="text" name="firstname" placeholder="First Name">
            <input type="text" name="lastname" placeholder="Last Name"><br/>
            <input type="text" name="username" placeholder="User Name"><br/>
            <input type="password" name="password" placeholder="Password"><br/>
            <input type="password" name="confirmpassword" placeholder="Confirm Password"><br/>
            <button type="submit" name="signup">Sign up</button>
        </form>
    </div>
</body>
</html>

<script>
    $(document).ready(function(){
        $("#registration").hide();
    });

    $("#login-btn").click(function(){
        $("#registration").hide();
        $("#login").show();

    })

    $("#signup-btn").click(function(){
        $("#registration").show();
        $("#login").hide();
    })
</script>