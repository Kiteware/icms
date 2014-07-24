<?php
require '../core/init.php';
include("../templates/admin/head.php"); 
?>

<body>
<div id="topbar">
    <div class="left">
        ICMS Administrator Panel
    </div>
	<div class="right">
    <div class="b-divider b-userbar i-user-select_none">
      
      <div class="b-divider__side b-userbar__icons">
        
        
        <a href="#" class="b-userbar__icons-item i-font-size_none">
          <img src="http://commondatastorage.googleapis.com/johnius/anchor.png" width="25" height="25" alt="Userbar icon" class="b-userbar__icons-item-ico" />
          <span class="b-userbar__icons-item-notify i-font_normal">5</span>
        </a>
        
        <a href="#" class="b-userbar__icons-item i-font-size_none">
          <img src="http://commondatastorage.googleapis.com/johnius/activity.png" width="25" height="25" alt="Userbar icon" class="b-userbar__icons-item-ico" />
        </a>
        
        <a href="#" class="b-userbar__icons-item i-font-size_none">
          <img src="http://commondatastorage.googleapis.com/johnius/badge.png" width="25" height="25" alt="Userbar icon" class="b-userbar__icons-item-ico" />
          <span class="b-userbar__icons-item-notify i-font_normal">1</span>
        </a>
        
        
      </div>
      </div>
    </div>
 </div>
  <div id="sidebar">
        <?php include '../includes/admin_menu.php'; ?>
  </div>
  <div id="content">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
    <?php 
		if(empty($errors) === false){
			echo '<p>' . implode('</p><p>', $errors) . '</p>';	
		}

		?>
    <p>Content Paragraph 1 Content Paragraph 1 Content Paragraph 1 Content Paragraph 1 Content Paragraph 1 Content Paragraph 1</p>
    <p>Content Paragraph 2</p>
    <p>Content Paragraph 3</p>
    </div>
    </div>
    </div>
  <div id="content_left">
 <div class="box">
    <div class="box-header">Panel 2</div>
    <div class="box-body">
    <p>Content Paragraph 1</p>
    <p>Content Paragraph 2</p>
    <p>Content Paragraph 3</p>
    </div>
    </div>
    
  </div>
    <div id="content_right">
 <div class="box">
    <div class="box-header">Panel 2,5</div>
    <div class="box-body">
    <p>Content Paragraph 1 Content Paragraph 1 Content Paragraph 1 Content Paragraph 1 Content Paragraph 1 Content Paragraph 1</p>
    <p>Content Paragraph 2</p>
    <p>Content Paragraph 3</p>
    </div>
    </div>
    
  </div>
    
    
    <div id="content_left">
 <div class="box">
    <div class="box-header">Panel 3</div>
    <div class="box-body">
    <p>Content Paragraph 1 Content Paragraph 1 Content Paragraph 1 Content Paragraph 1 Content Paragraph 1 Content Paragraph 1</p>
    <p>Content Paragraph 2</p>
    <p>Content Paragraph 3</p>
    </div>
    </div>
  </div>
      <div id="content_right">
 <div class="box">
    <div class="box-header">Panel 3.5</div>
    <div class="box-body">
    <p>Content Paragraph 1 Content Paragraph 1 </p>
    <p>Content Paragraph 2</p>
    <p>Content Paragraph 3</p>
    </div>
    </div>
  </div>
<script type="text/javascript" src="../templates/admin/js/main.js"></script> 

<?php include("../templates/default/footer.php"); ?>
</body>