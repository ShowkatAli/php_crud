<?php 
    session_start();
    require_once('includes/pdo.php');
    //require_once('includes/header.php');

    if (!isset($_SESSION['user_id'])){
        die('Not logged in');
        return;
    }
 
    if (isset($_POST['cancel'])){
        header('Location: index.php');
        return;
    }

    if (isset($_POST['first_name']) && isset($_POST['last_name']) &&
        isset($_POST['email']) && isset($_POST['headline']) &&
        isset($_POST['summary'])){
        if( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 ||
            strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 ||
            strlen($_POST['summary']) < 1 ){
                $_SESSION['error'] = "All fields are required";
                header('Location: add.php');
                return;
            }
        if (strpos($_POST['email'], '@') === false ){
            $_SESSION['error'] = "Email address must contain @";
            header('Location: add.php');
            return;
        }
       
        $stmt = $pdo->prepare('INSERT INTO profile 
            (user_id, first_name, last_name, email, headline, summary)
            VALUES( :uid, :fn, :ln, :em, :he, :su)');
        $stmt->execute(array(
            'uid' => $_SESSION['user_id'],
            'fn' => htmlentities($_POST['first_name'], ENT_QUOTES),
            'ln' => htmlentities($_POST['last_name'], ENT_QUOTES),
            'em' => htmlentities($_POST['email'], ENT_QUOTES),
            'he' => htmlentities($_POST['headline'], ENT_QUOTES),
            'su' => htmlentities($_POST['summary'], ENT_QUOTES),
        ));
        $_SESSION['success'] = "Profile added";
        header("Location: index.php");
        return;
    }
    require_once('includes/header.php');
    
    echo '<h1> Adding Profile for UMSI</h1>';
 
    include_once('includes/session_msg.php');

    echo '<form method="POST" enctype="multipart/form-data">';
        echo '<label for="first_name">First Name</label> ';
        echo '<input type="text" id="first_name" name="first_name" size="60%"/>';
        echo '<br/>';
        echo '<label for="last_name">Last Name</label> ';
        echo '<input type="text" id="last_name" name="last_name" size="60%"/>';
        echo '<br/>';
        echo '<label for="email">Email</label> ';
        echo '<input type="text" id="email" name="email" size="30%"/>';
        echo '<br/>';
        echo '<label for="headline">Headline</label><br/> ';
        echo '<input type="text" id="headline" name="headline" size="80%"/>';
        echo '<br/>';
        echo '<label for="summary">Summary</label><br/> ';
        echo '<textarea id="summary" name="summary" rows="8" cols="80"></textarea>';
        echo '<br/>';
        echo '<input type="submit" name="submit" value="Add" /> ';
        echo '<input type="submit" name="cancel" value="Cancel" />';
    echo '</form>';
    






    require_once('includes/footer.php');
?>