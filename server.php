<?php 
   session_start(); //Starts a new session

   $firstname = "";
   $lastname = "";   
   $username = "";
   $errors = array();
   $personaldiary = "";
   $workdiary = "";
   
   //Connecting to database
   $database = mysqli_connect('localhost', 'root', '', 'dailyjournal');

   //Registering user
   if(isset($_POST['signup'])){   //_POST gets values from form. Value in [ ] is the name attribute
       //Recieving user input
       //sqli_eal_escape_string escapes special characters for use in database
       $firstname = mysqli_real_escape_string($database, $_POST['firstname']);
       $lastname = mysqli_real_escape_string($database, $_POST['lastname']);
       $username = mysqli_real_escape_string($database, $_POST['username']);
       $password = mysqli_real_escape_string($database, $_POST['password']);
       $confirmpassword = mysqli_real_escape_string($database, $_POST['confirmpassword']);
       
       //Form validation
       if(empty($firstname))
            array_push($errors, "First name is required");
        if(empty($lastname))
            array_push($errors, "Last name is required"); 
        if(empty($username))
            array_push($errors, "Username is required");
        if(empty($password))
            array_push($errors, "Password is required");
        if($password != $confirmpassword)
            array_push($errors, "Passwords do not match");

        //Checking database for duplicate entry of username
        $check_query = "SELECT * FROM user WHERE username='$username' LIMIT 1";
        $result = mysqli_query($database, $check_query);  //Performs query
        $user = mysqli_fetch_assoc($result);  //Fetches an array of the result

        if($user){   //Check if user exists
            if($user['username'] === $username)
                array_push($errors, "Username already exists");
        }

        //Registering a user if there are no errors
        if(count($errors) == 0){
            $encrypted_password = md5($password); //encrypting password
            
            //query to insert values in database
            $query = "INSERT INTO user (firstname, lastname, username, password) VALUES ('$firstname', '$lastname', '$username', '$encrypted_password')";
            mysqli_query($database, $query);

            $_SESSION['username'] = $username;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['success'] = "Registration complete";
            header('location: home.php');
        }
   }
   
   if(isset($_POST['login'])){
       $username = mysqli_real_escape_string($database, $_POST['username']);
       $password = mysqli_real_escape_string($database, $_POST['password']);
       
       if(empty($username))
            array_push($errors,"Username is required");
        if(empty($password))
            array_push($errors, "Password is required");

        if(count($errors) == 0){
            $encrypted_password = md5($password);

            $query = "SELECT * FROM user WHERE username = '$username' and password = '$encrypted_password'";
            $results = mysqli_query($database, $query);
            
            $firstname = mysqli_fetch_assoc($results)['firstname'];  //Getting firstname from database
            $id = mysqli_fetch_assoc($results)['id'];

            if(mysqli_num_rows($results) == 1){
                $_SESSION['username'] = $username;
                $_SESSION['firstname'] = $firstname;
                $_SESSION['success'] = "You are now logged in";
                header('location: home.php');
            }
            else{
                array_push($errors, "Wrong username/password");
            }
        }
   }

   if(isset($_POST['submitdiary'])){
       $personaldiary = mysqli_real_escape_string($database, $_POST['personaldiary']);
       $workdiary = mysqli_real_escape_string($database, $_POST['workdiary']);
       $username =  $_SESSION['username'];

       //retrieving user id from user table
        $result = mysqli_query($database, "SELECT * FROM user WHERE username='$username'");
        $userid = mysqli_fetch_assoc($result)['id'];

       //Checking if input entered correctly
       if(empty($personaldiary))
            array_push($errors, "You personal diary is empty");
       if(empty($workdiary))
            array_push($errors, "Your work diary is empty");

        if(count($errors) == 0){
            $query = "INSERT INTO diary (personaldiary, workdiary, submitdate,userid) 
            VALUES ('$personaldiary', '$workdiary', curdate(), '$userid')";
            mysqli_query($database, $query);


        }
   }

   if(isset($_POST['editdiary'])){
        $personaldiary = mysqli_real_escape_string($database, $_POST['personaldiary']);
        $workdiary = mysqli_real_escape_string($database, $_POST['workdiary']);
        $username = $_SESSION['username'];

        //retrieving user id from user table
        $result = mysqli_query($database, "SELECT * FROM user WHERE username='$username'");
        $userid = mysqli_fetch_assoc($result)['id'];

        $query = "UPDATE diary SET personaldiary='$personaldiary', workdiary='$workdiary' WHERE userid='$userid'";
        mysqli_query($database, $query);
   }

   function displaydata($uname, $value){
        global $database; 
        if(!submitcheck($uname))
            echo "";
        else{
            $uid_result = mysqli_query($database, "SELECT * FROM user WHERE username='$uname'");
            $uid = mysqli_fetch_assoc($uid_result)['id'];
            $display_result = mysqli_query($database, "SELECT * FROM diary WHERE userid='$uid' AND submitdate=curdate()");
            echo mysqli_fetch_assoc($display_result)[$value];
        }
   }

   function submitcheck($uname){
        global $database;
        $uid_result = mysqli_query($database, "SELECT * FROM user WHERE username='$uname'");
        $uid = mysqli_fetch_assoc($uid_result)['id']; 
        $submit_check = mysqli_query($database, "SELECT * FROM diary WHERE userid='$uid' AND submitdate=curdate()");
        if(mysqli_num_rows($submit_check) != 0)
            return true;
        else
            return false;
   }    

?>
