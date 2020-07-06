<?php 
session_start();
require_once('includes/pdo.php');


if(isset($_SESSION['user_id'])){
    header('Location: index.php');
    return;
}

if(isset($_POST['cancel'])){
    header('Location: index.php');
    return;
}

if(isset($_POST['email']) && isset($_POST['password'])){
    if(strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1){
        $_SESSION['error'] = "Both fields must be filled out";
        header('Location: login.php');
        return;
    }
    
    if(strpos($_POST['email'], '@') === false){
        $_SESSION['error'] = "Email address must contain @";
        header('Location: login.php');
        return;
    }

    $salt = 'XyZzy12*_';
    $salted_pw = hash('md5', $salt.$_POST['password']);

    $stm = $pdo->prepare('SELECT * FROM users WHERE email = :em AND password = :pw');
    $stm->execute(array('em' => $_POST['email'], 
                        'pw' => $salted_pw)); 
    $res = $stm->fetch(PDO::FETCH_ASSOC);

    if(!$res){
        $_SESSION['error'] = "Incorrect password";
        header('Location: login.php');
        return;
    }
    $_SESSION['user_id'] = $res['user_id'];
    $_SESSION['name'] = $res['name'];
    header('Location: index.php');
    return;
}
require_once('includes/header.php');
echo "<h1>Please Log In</h1>";
include_once('includes/session_msg.php');

echo '<form method="POST" enctype="multipart/form-data">';
    echo '<label for="email" class="font-weight-bold">Email: </label>';
    echo '<input type="text" id="email" name="email" />';
    echo '<br/>';
    echo '<label for="id_1723" class="font-weight-bold"> Password: </label>';
    echo '<input type="password" id="id_1723" name="password" />';
    echo '<br />';
    echo '<input type="submit" onclick="return doValidate();" value="Log In" />';
    echo '<input type="submit" name="cancel" value="Cancel" />';
echo '</form>';

require_once('includes/footer.php');

?>

<script type="text/javascript">
function doValidate() {
    console.log('Validating...');
    try {
        addr = document.getElementById('email').value;
        pw = document.getElementById('id_1723').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>