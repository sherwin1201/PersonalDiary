<?php 
    include('server.php');

    if(!isset($_SESSION['username'])){
        $_SESSION['msg'] = "You must log in first";
        header('location: registration.php');
    }

    if(isset($_GET['logout'])){
        session_destroy();
        unset($_SESSION['username']);
        unset($_SESSION['firstname']);
        unset($_SESSION['id']);
        header("location: registration.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Document</title>
</head>
<body>
    <?php if(isset($_SESSION['firstname'])) : ?>
        <h1>Welcome to you Daily Journal, <?php echo $_SESSION['firstname'] ?> !</h1>
    <?php endif ?>
    <div> <?php if(isset($_SESSION['username'])) {
                    if(submitcheck($_SESSION['username']))
                        echo "Diary has been submitted for the day";
            } ?> 
    </div>
    <div> <?php include('errors.php'); ?> </div>
    <form action="home.php" method="post">
        
        <label>Personal</label></br>
        <textarea required name="personaldiary"><?php if(isset($_SESSION['username'])) 
            displaydata($_SESSION['username'],'personaldiary') ?></textarea></br>
        
        <label>Work</label></br>
        <textarea required name="workdiary"><?php if(isset($_SESSION['username'])) displaydata($_SESSION['username'],'workdiary') ?></textarea></br>
        
        <button type="submit" name="submitdiary" id="submit-btn" 
        <?php 
            if(isset($_SESSION['username']))
                if(submitcheck($_SESSION['username'])) : ?>
                hidden <?php endif ?>>Submit</button>
        
        <?php 
            if(isset($_SESSION['username']))
                if(submitcheck($_SESSION['username'])) : ?>
                 <button type="submit" name="editdiary" id="edit-btn">Edit</button>
            <?php endif ?>
    </form>
    <div>
        <p> <a href="home.php?logout='1" id="logout">logout</a> </p>
    </div>
</body>
</html>

<script>
    $("#edit-btn").click(function(event){
        if(!confirm('Are you sure you want to confirm your changes?')){
            event.preventDefault();
        }
    });

    $("#submit-btn").click(function(event){
        if(!confirm('Are you sure you want to submit your diary?')){
            event.preventDefault();
        }
    });

    $("#logout").click(function(event){
        if(!confirm('You will be logged out of the portal.\nDo you want to continue?')){
            event.preventDefault();
        }
    });
</script>
