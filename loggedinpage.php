<?php 

    session_start();

    if (array_key_exists ("id", $_COOKIE)) {
        
        $_SESSION['id'] = $_COOKIE['id'];
        
    }

    if (array_key_exists ("id", $_SESSION)) {
        
        
        echo "Logged in! <a href='index.php?logout=1'>Log Me Out!</a>";
        
    } else {
        
        header("Location: index.php");
        
    }

?>


<? include "header.php" ?>


<div class = "container-fluid">


    <textarea class = "form-control" id = "diary"></textarea>



</div>
    

    
    
    
    
    
<? include "footer.php" ?>