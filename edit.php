<?php 
    session_start();
    require_once('includes/pdo.php');

    if (!isset($_SESSION['user_id'])){
        die('Not logged in');
        return;
    }

    if(!isset($_GET['profile_id'])){
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
    }
    if (isset($_POST['cancel'])){
        header('Location: index.php');
        return;
    }


    $stmt = $pdo->prepare('SELECT * FROM profile WHERE profile_id = :pid' );

    $stmt->execute(array(
        'pid' => $_GET['profile_id'],
    ));
    $res = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$res){
        $_SESSION['error'] = "Could not load profile";
        header("Location: index.php");
        return;
    }

    if($res['user_id'] !== $_SESSION['user_id']){
        die('You do not have right to edit this profile');
        return;
    }
    
    if (isset($_POST['first_name']) && isset($_POST['last_name']) &&
        isset($_POST['email']) && isset($_POST['headline']) &&
        isset($_POST['summary']) && isset($_POST['profile_id'])){
        if( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 ||
            strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 ||
            strlen($_POST['summary']) < 1 ){
                $_SESSION['error'] = "All fields are required";
                header('Location: add.php?profile_id='.$res['profile_id']);
                return;
            }
        if (strpos($_POST['email'], '@') === false ){
            $_SESSION['error'] = "Email address must contain @";
            header('Location: edit.php?profile_id='.$_POST['profile_id']);
            return;
        }
       
        $stmt = $pdo->prepare('UPDATE  profile 
            SET first_name = :fn,
                last_name = :ln,
                email = :em,
                headline = :he,
                summary = :su
            WHERE user_id = :uid AND
                    profile_id = :pid');
        $stmt->execute(array(
            'uid' => $_SESSION['user_id'],
            'pid' => $_POST['profile_id'],
            'fn' => htmlentities($_POST['first_name'], ENT_QUOTES),
            'ln' => htmlentities($_POST['last_name'], ENT_QUOTES),
            'em' => htmlentities($_POST['email'], ENT_QUOTES),
            'he' => htmlentities($_POST['headline'], ENT_QUOTES),
            'su' => htmlentities($_POST['summary'], ENT_QUOTES),
        ));
        $_SESSION['success'] = "Profile updated";
        header("Location: index.php");
        return;
    }
    require_once('includes/header.php');
    
    echo '<h1> Editing Profile for UMSI</h1>';
 
    include_once('includes/session_msg.php');

    echo '<form method="POST" enctype="multipart/form-data">';
        echo '<label for="first_name">First Name</label> ';
        echo '<input type="text" id="first_name" name="first_name" value="'.$res['first_name'].'" size="60%"/>';
        echo '<br/>';
        echo '<label for="last_name">Last Name</label> ';
        echo '<input type="text" id="last_name" name="last_name" value="'.$res['last_name'].'" size="60%"/>';
        echo '<br/>';
        echo '<label for="email">Email</label> ';
        echo '<input type="text" id="email" name="email" value="'.$res['email'].'" size="30%"/>';
        echo '<br/>';
        echo '<label for="headline">Headline</label><br/> ';
        echo '<input type="text" id="headline" name="headline" value="'.$res['headline'].'" size="80%"/>';
        echo '<br/>';
        echo '<label for="summary">Summary</label><br/> ';
        echo '<textarea id="summary" name="summary" rows="8" cols="80">'.$res['summary'].'</textarea>';
        echo '<br/>';
        echo '<input type="hidden" name="profile_id" value="'.$res['profile_id'].'"/>';
        echo '<input type="submit" name="submit" value="Save" /> ';
        echo '<input type="submit" name="cancel" value="Cancel" />';
    echo '</form>';
    




    require_once('includes/footer.php');
?>