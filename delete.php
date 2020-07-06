<?php

    session_start();
    require_once('includes/pdo.php');
    
    
    if(!isset($_SESSION['user_id'])){
        die('Not logged in');
        return;
    }

    if(!isset($_GET['profile_id'])){
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
    }


    if(isset($_POST['cancel'])){
        header('Location: index.php');
        return;
    }

    $stm = $pdo->prepare('SELECT user_id, profile_id, first_name, last_name 
    FROM profile 
    WHERE profile_id = :pid');
    $stm->execute(array(
        'pid' => $_GET['profile_id']
    ));
    $res = $stm->fetch(PDO::FETCH_ASSOC);

    if(!$res){
        $_SESSION['error'] = 'Could not load profile';
        header('Location: index.php');
        return;
    }

    if((int)$res['user_id'] !== (int)$_SESSION['user_id']){
        die('You do not have right to delete this profile');
        return;
    }
    
    if(isset($_POST['delete'])){
        $stm = $pdo->prepare('DELETE FROM profile WHERE 
                    user_id = :uid AND profile_id = :pid');
        $stm->execute(array(         
            'uid' => $_SESSION['user_id'],
            'pid' => $_POST['profile_id']
        )); 
        $_SESSION['success'] = 'Profile deleted';
        header('Location: index.php');
        return;
    }

    require_once('includes/header.php');
    
    echo '<h1>Deleting Profile</h1>';
    echo '<p>First Name: '.$res['first_name'].'</p>';
    echo '<p>Last Name: '.$res['last_name'].'</p>';
    
    echo '<form method="POST">';
        echo '<input type="hidden" name="profile_id" value="'.$res['profile_id'].'"/>';
        echo '<input type="submit" name="delete" value="Delete" /> ';
        echo '<input type="submit" name="cancel" value="Cancel" />';
    echo '</form>';



    require_once('includes/footer.php');

?>