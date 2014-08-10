<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400); 
    exit('400: Bad Request'); 
    } ?>
<body>	
	<div id="container">
	<?php include 'includes/menu.php'; ?>
		<h1>Activate your account</h1>

    	<?php
        
        if (isset($_GET['success']) === true && empty ($_GET['success']) === true) {
	        ?>
	        <h3>Thank you, we've activated your account. You're free to log in!</h3>
	        <?php
	            
        } else if (isset ($_GET['email'], $_GET['email_code']) === true) {
            
            $email		=trim($_GET['email']);
            $email_code	=trim($_GET['email_code']);	
            
            if ($users->email_exists($email) === false) {
                $errors[] = 'Sorry, we couldn\'t find that email address';
            } else if ($users->activate($email, $email_code) === false) {
                $errors[] = 'Sorry, we have failed to activate your account';
            }
            
			if(empty($errors) === false){
			
				echo '<p>' . implode('</p><p>', $errors) . '</p>';	
		
			} else {
                header('Location: activate.php?success');
                exit();
            }
        
        } else {
            header('Location: index.php');
            exit();
        }
        ?>

	</div>
</body>