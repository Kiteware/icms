<?php 
//$general->logged_in_protect();

if (isset($_POST['submit'])) {
	if(empty($errors) === true){
		
		$url 	= htmlentities($_POST['url']);
		
		// configuration
		//$file = '../index.php';
		$file = '../'.$url.'.php';
		$text = file_get_contents($file);
		
		//exit();
	}
} else {
		$text = "";
	}
?>
<body>	
	<div id="content">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
		<h1>Edit Page</h1>
		
		<?php
		if (isset($_GET['success']) && empty($_GET['success'])) {
		  echo 'Page created.';
		}
		?>
		
		<?php
		
		$arrValues = $pages->get_pages();
       if (!empty($arrValues)) {
    		print "<table wdith=\"100%\">\n";
    		print "<tr>\n";
    		// add the table headers
    		foreach ($arrValues[0] as $key => $useless){
    			print "<th>$key</th>";
    		}
    		print "</tr>";
    		// display data
    		foreach ($arrValues as $row){
    			print "<tr>";
    			foreach ($row as $key => $val){
    				print "<td>$val</td>";
    			}
    			print "</tr>\n";
    		}
    		// close the table
    		print "</table>\n";
            }
		?>
		<!-- HTML form -->
		<form action="" method="post" name="post">
			<p>Name:<br />
			<input name="url" type="text" size="45" value="enter url"/>
			</p>
			<textarea name="text" id="editpage"><?php echo htmlspecialchars($text) ?></textarea>
			<input name="submit" type="submit" value="submit"/>
		</form>
		<?php 
		if(empty($errors) === false){
			echo '<p>' . implode('</p><p>', $errors) . '</p>';	
		}
		?>
	</div>
    </div>
    </div>
    <script type="text/javascript">
    	CKEDITOR.replace( 'editpage' );
    </script>
</body>
</html>