<div id="topbar">
    <div class="left">
        <?php echo $settings->production->site->name." Administrator Panel" ?>
    </div>
	<div class="right">
    <div class="b-divider b-userbar i-user-select_none">
      <div class="b-divider__side b-userbar__icons">
        <a href="#" class="b-userbar__icons-item">
          <i class="fa fa-paper-plane-o fa-lg"></i>
          <span class="b-userbar__icons-item-notify i-font_normal">5</span>
        </a>
        <a href="#" class="b-userbar__icons-item ">
            <?php echo $user['username'];?>
        </a>
        <a href="../index.php?page=logout.php" class="b-userbar__icons-item ">
          <i class="fa fa-power-off fa-lg"></i>
        </a>
      </div>
      </div>
    </div>
 </div>
