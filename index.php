<?php 
    session_start();
    require_once("includes/pdo.php");
    require_once("includes/header.php");
    require_once('includes/paginator.php');
    
    echo '<h1>Chuck Severance\'s Resume Registry</h1>';
    
    include_once('includes/session_msg.php');

    if(isset($_SESSION['user_id']))
        echo '<a href="logout.php">Logout</a> </br><a href="add.php">Add New Entry</a>';    
    else
        echo '<a href="login.php">Please log in</a>';
    

    $stm = $pdo->prepare("SELECT * FROM profile");
    $stm->execute();
    $res = $stm->fetchAll(PDO::FETCH_ASSOC);
    $resOnCancel = $res;
    if(isset($_GET['cancel'])){
        header('Location: index.php');
    }
    if(isset($_GET['q'])){
        $get_val = preg_replace('!\s+!', ' ', $_GET['q']);
        if(strlen($get_val)> 0 && $get_val != "" && $get_val != " "){

            $get_val = str_replace(" ", "%", $get_val);
            $get_val = '%'.$get_val.'%';
            $qstm = $pdo->prepare('SELECT * FROM profile 
                    WHERE user_id LIKE :get_val OR 
                        profile_id LIKE :get_val OR
                        first_name LIKE :get_val OR
                        last_name LIKE :get_val OR
                        email LIKE :get_val OR
                        headline LIKE :get_val OR
                        summary LIKE :get_val');
            $qstm->execute(array(
                'get_val' => $get_val
            ));
            $res = $qstm->fetchAll(PDO::FETCH_ASSOC);
            
            if(!$res){
                echo '<p class="alert-danger">No result found with search term(s): <span class="font-weight-bold font-italic">'.$_GET['q'].'</span></p>';
            }
            else{
                echo '<p class="alert-success">Showing search result for <span class="font-weight-bold font-italic">'.$_GET['q'].'</span></p>';
            }
        }
        else{
            echo '<p class="alert-warning">You typed nothing in the search box. Showing all instead</p>';
            $res = $resOnCancel;
        }
    }
    else{
        $res = $resOnCancel;
    }

    echo '<form type="GET" enctype="multipart/form-data" class="container-fluid">';
        echo '<label for="search">Search </label> ';
        $get_val_q = isset($_GET['q'])?$_GET['q']:'';
        echo '<input type="text" id="search" name="q" value="'.$get_val_q.'" placeholder="Search item here" /> ';
        echo '<input type="submit" value="Search" /> ';
        echo '<input type="submit" name="cancel" value="Cancel" />';
    echo '</form>';

 
    $limit      = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 10;
    $page       = ( isset( $_GET['page'] ) ) ? (int)$_GET['page'] > 1 ? (int)$_GET['page'] : 1 : 1;
    $links      = ( isset( $_GET['links'] ) ) ? $_GET['links'] : 7;

    if(isset($_GET['q'])){
        $get_val = preg_replace('!\s+!', ' ', $_GET['q']);
        if(strlen($get_val)> 0 && $get_val != "" && $get_val != " "){
                    $get_val = str_replace(" ", "%", $get_val);
                    $get_val = '%'.$get_val.'%';
        
        $query = 'SELECT * FROM profile 
                WHERE user_id LIKE "'. $get_val .'" OR 
                     profile_id LIKE "'.$get_val.'" OR
                    first_name LIKE "'.$get_val.'" OR
                    last_name LIKE "'.$get_val.'" OR
                     email LIKE "'.$get_val.'" OR
                     headline LIKE "'.$get_val.'" OR
                   summary LIKE "'.$get_val.'"';
        }
        else{
            $query = 'SELECT * FROM profile';
        }
    }
    else {
        $query='SELECT * FROM profile';
    }

    $Paginator  = new Paginator( $pdo, $query );
    $results    = $Paginator->getData( $limit, $page );

?>

<div class="container">
                <div class="col-md-10 col-md-offset-1">
                <table class="table table-striped table-condensed table-bordered table-rounded container-fluid">
                        <thead>
                                <tr>
                                <th>Name</th>
                                <th>Headline</th>
                                <?php if(isset($_SESSION['user_id'])) echo '<th>Action</th>'; ?>                            

                        </tr>
                        </thead>
                        <tbody>
                        <?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>
                    <tr>
                        <td><?php echo '<a href=view.php?profile_id='.$results->data[$i]['profile_id'].'>'.$results->data[$i]['first_name'].' '.$results->data[$i]['last_name'].'</a>'; ?></td>
                        <td><?php echo $results->data[$i]['headline']; ?></td>
                        <?php if(isset($_SESSION['user_id'])) echo '<td>'; 
                            if(isset($_SESSION['user_id']) && $results->data[$i]['user_id'] == $_SESSION['user_id'])
                            echo '<a href="edit.php?profile_id='.$results->data[$i]['profile_id'].'">Edit</a> <a href="delete.php?profile_id='.$results->data[$i]['profile_id'].'">Delete</a>';
                            if(isset($_SESSION['user_id'])) echo '</td>';
                            ?>
                    </tr>
<?php endfor; ?>

                        </tbody>
                </table>
                </div>
        </div>



<?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?> 















<?php 
    require_once("includes/footer.php");
?>