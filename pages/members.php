<?php
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
$members        = $users->get_users();
$member_count   = count($members);
?>
	<div id="container">
		<h1>Our members</h1>
		<p>We have a total of <strong><?php echo $member_count; ?></strong> registered users. </p>

		<?php
        foreach ($members as $member) {
            $username = htmlentities($member['username']);
            ?>
			<p><a href="pages/profile.php?username=<?php echo $username; ?>"><?php echo $username?></a> joined: <?php echo date('F j, Y', $member['time']) ?></p>
			<?php } ?>
	</div>
