<?php
        require_once('ssl.php');
        //session_start();
        if(!isset($_SESSION['username'])){
            header('location:index.php');
        }
        
        //header('location: new_user_register.php');
        require_once('config.php');
        require_once('safe.php');
        session_regenerate_id(true);
        
        if (isset($_POST['id']) && ($_POST['password'])) {
            $id = mysqli_real_escape_string($con, $_POST['id']); //escapes all special characters if some o ne wants to pass a JS string
            $name = mysqli_real_escape_string($con, $_POST['name']); // Prepared statements are a better option
            $un = mysqli_real_escape_string($con, $_POST['uname']);
            $pass = mysqli_real_escape_string($con, $_POST['password']);
            $eml = mysqli_real_escape_string($con, $_POST['email']);
            $role = mysqli_real_escape_string($con, $_POST['tasrole']);
            $password = mysqli_real_escape_string($con, $_POST['password']);
            $uppercase = preg_match('@[A-Z]@', $password); //Checking for Mandatory password checks in the next 4 lines of code
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);
            $spclChars = preg_match('@[^\w]@', $password);

            if (($uppercase==0) || ($lowercase==0) || ($number==0) || ($spclChars==0)||(strlen($password) < 8)) {
                echo("incorrect");
            //header("location: new_user_register.php");
            } else {
                $hashedpass = password_hash($pass, PASSWORD_DEFAULT); //BeCrypt encryption
                    
                $sql = "SELECT * FROM taslogin where username = '$un'";
                $result = mysqli_query($con, $sql);
                $num = mysqli_num_rows($result);
                if ($num == 1) {
                    // echo("Username Exists");
                    $message = "Username Exists";
                    echo "<script type='text/javascript'>alert('$message');</script>";
                } else {
                    if ((!empty($id)) && (!empty($password))) {
                        if (Token::validate($_POST['token'])) {
                            $reg = " INSERT INTO db_name(empid, name, username, password, email, role) VALUES('$id', '$name', '$un', 		'$hashedpass','$eml','$role') ";
                            mysqli_query($con, $reg);
                            $message1 = "Registration Complete";
                            //echo "<script>alert('$message1');window.location='new_user_register.php'</script>";
                        }
                    }
                }
                header('location: new_user_register.php');
            }
        }
?>
