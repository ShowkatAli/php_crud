<?php 
    session_start();
    require_once('includes/pdo.php');


    if(!isset($_GET['profile_id'])){
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
    }

    $stm = $pdo->prepare('SELECT * FROM profile WHERE profile_id = :pid');
    $stm->execute(array('pid' => $_GET['profile_id']));
    $res = $stm->fetch(PdO::FETCH_ASSOC);
    if(!$res)
    {
        $_SESSION['error'] = "Could not load profile";
        header('Location: index.php');
        return;
    }
    require_once('includes/header.php');
    
    echo '<h1>Profile information</h1>';
    echo '<p>First Name: '.$res['first_name'].'</p>';
    echo '<p>Last Name: '.$res['last_name'].'</p>';
    echo '<p>Email: '.$res['email'].'</p>';
    echo '<p>Headline:</br>'.$res['headline'].'</p>';
    echo '<p>Summary:</br>'.$res['summary'].'</p>';
    
    echo '<a href="index.php">Done</a>';
    

    require_once('includes/footer.php');
    

?>