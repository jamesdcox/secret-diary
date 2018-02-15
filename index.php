<?php

    ob_start();

    if(!isset($_SESSION)){
        session_start();
    }

    $error = "";

    $userGreeting = "";

    if (array_key_exists("logout", $_GET)) {
        
        
        session_unset();
        setcookie("id","", time() - 60*60);
        $_COOKIE["id"] = "";
        
    } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])){
        
        header("Location: loggedinpage.php");
        $userGreeting = "Hello, User";
        
        
    }

    if (array_key_exists("submit", $_POST)) {
        
        include "connection.php";
        
        
        if ($_POST['email'] == '') {
            
            $error .= "Email Address required!<br>";
            
        }
            
        if ($_POST['password'] == '') {
            
            $error .= "Password required!<br>";
            
        }
        
        if ($error != "") {
            
            $error = "<p>You have the following error(s) in your form:</p>".$error;
            
        } else {
            
            if (isset($_POST['signUp'])) {
            
            
                $query = "SELECT id FROM mydiary WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'LIMIT 1";

                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0) {

                    $error = "Email Address taken.";

                } else {


                    $query = "INSERT INTO mydiary (email, password) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";

                    if (!mysqli_query($link, $query)) {

                        $error = "<p>Your sing up has NOT been successful, please try again later.</p>";

                        } else {

                            $query = "UPDATE mydiary SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."'WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

                            mysqli_query($link, $query);

                            $_SESSION['id'] = mysqli_insert_id($link);

                            if (isset($_POST['stayLoggedIn'])) {

                                setcookie("id", mysqli_insert_id($link), time + 60*60*24*365);

                            }

                            header ("Location: loggedinpage.php");

                        }


                }
                
            } else {
                
                
                $query = "SELECT * FROM mydiary WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
                $result = mysqli_query($link, $query);
                
                $row = mysqli_fetch_array($result);
                
                if (isset($row)) {
                    
                    $hashedPassword = md5(md5($row['id']).$_POST['password']);
                    
                    if ($hashedPassword == $row['password']) {
                        
                        $_SESSION['id'] = $row['id'];
                        
                        if (isset($_POST['stayLoggedIn'])) {

                                setcookie("id", $row['id'], time + 60*60*24*365);
                        
                        
                    }
                        
                        header ("Location: loggedinpage.php");
                        
                    } else {
                        
                        $error = "Sorry, that email/password combination could not be found.";
                        
                    }
                    
                } else {
                    
                    $error = "Sorry, that email/password combination could not be found.";
                    
                }
                
            }
            
            
        }
        
    }

        

?>

  <? include "header.php"?>

  <body>
      
    <div class="container" id="body">
        
        <h1>Secret Diary</h1>
    
        
    <div id="error"><?php echo $error; ?></div>


        <form method="post" id="signUpForm">
    
    
            <div class="form-group">

    
                <input class="form-control" type="email" name="email" placeholder="Your Email">
                
            </div>
    
            <div class="form-group">
    
                <input class="form-control" type="password" name="password" placeholder="Password">
                
            </div>
            
            <div class="form-check">
    
                <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="stayLoggedIn" value=1>
                Stay Logged In
                </label>
                
            </div>
    
    
            <input type="hidden" name="signUp" value="1">
    
    
            <input class="btn btn-primary" type="submit" name = "submit" value="Sign Up!">

            <p class="link"><a href="#" class="badge badge-light toggleForms">Sign In</a></p>

        </form>
        
        


        <form method="post" id="signInForm">
    
    
    

    
            <div class="form-group">

    
                <input class="form-control" type="email" name="email" placeholder="Your Email">
                
            </div>
    
            <div class="form-group">
    
                <input class="form-control" type="password" name="password" placeholder="Password">
                
            </div>
            
            <div class="form-check">
    
                <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="stayLoggedIn" value=1>
                Stay Logged In
                </label>
                
            </div>
    
    
            <input type="hidden" name="logIn" value="0">
    
    
            <input class="btn btn-primary" type="submit" name = "submit" value="Log In!">

            <p class="link"><a href="#" class="badge badge-light toggleForms">Sign Up</a></p>

        </form>
        
        
          
    </div>
      
      

    <? include"footer.php"; ?>

