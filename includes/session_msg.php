<?php 
if (isset($_SESSION['error'])){
    echo'<p class="text-danger">'.$_SESSION['error'].'</p>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['info'])){
    echo'<p class="text-info">'.$_SESSION['info'].'</p>';
    unset($_SESSION['info']);
}

if (isset($_SESSION['success'])){
    echo'<p class="text-success">'.$_SESSION['success'].'</p>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['warning'])){
    echo'<p class="text-warning">'.$_SESSION['warning'].'</p>';
    unset($_SESSION['warning']);
}



?>