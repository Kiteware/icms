<!DOCTYPE html>
<html>

<?php
	include_once "./includes/functions.php";
	printHead("Search Results");
?>
   
<body>
	<?php 
	include_once "./includes/header.php";	
	include_once "./includes/mainNavigation.php"; 
	?>
		
	<article class="noWrap"> <div class="columnContainer">
						
		<section>
			<h1>Search Results</h1>	
			
			<?php
			// Connect to the database
			$mysqli = new mysqli("localhost", "root", "", "vitamin_bbc");
			if ($mysqli->connect_error) {
			?>
				<p>We're sorry, but there was a problem connecting to the database: (<?php echo $mysqli->connect_errno; ?>) <?php echo $mysqli->connect_error; ?>.</p>
			<?php
			}
			else {				
				// Prepare the form data
				$searchDataArray = createPreparedFormData($mysqli);
				
				// Create the query string
				$searchQuery = getSearchQuery($searchDataArray);
				
				$mysqli_result = $mysqli->query($searchQuery);
				if ($mysqli_result) {
					$mysqli_row = $mysqli_result->fetch_row();
					if ($mysqli_row) {
					?>
					<table border="1">
						<tr>
							<?php printTableHeadings($searchDataArray[SEARCH_FORM_SHOW_TYPE]); ?>
						</tr>
					<?php
						do {
					?>
						<tr>
							<?php printTableRow($mysqli_row); ?>
						</tr>
					<?php
							$mysqli_row = $mysqli_result->fetch_row();
						} while ($mysqli_row);
					?>
					</table>
					<?php
					}
					else {
					?>
					<p>Sorry, but no results were found.</p>
					<?php
					}
					
					$mysqli_result->free();
				}
				else {
				?>
				<p>We're sorry, but there was a problem querying the database: (<?php echo $mysqli->errno; ?>) <?php echo $mysqli->error; ?>.</p>
							
				<?php
				}
				// Disconnect from the database
				$mysqli->close();
			}
			?>

		</section>
		
	</div> </article>
	
	<?php 
	include_once "./includes/sidebar.php";	
	include_once "./includes/footer.php"; 
	?>	
</body>

</html>
