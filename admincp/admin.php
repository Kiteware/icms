<body>


  <div id="content">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
    <?php 
		if(empty($errors) === false){
			echo '<p>' . implode('</p><p>', $errors) . '</p>';	
		}

		?>
    <p>Welcome to the ICMS Administrator panel. </p>
    <p>Content Paragraph 2</p>
    <p>Content Paragraph 3</p>
    </div>
    </div>
    </div>
  <div id="content_left">
 <div class="box">
    <div class="box-header">Menu Manager</div>
    <div class="box-body">
    <p><?php 
    $navigation 		=$pages->list_nav();
    foreach ($navigation as $showNav){
			//displaying posts
			echo ($showNav['nav_name']."
			- ".$showNav['nav_link']."
			- ".$showNav['nav_position']."
  	         - ".$showNav['nav_permission']."
            <a onClick='editNav(\"".$showNav['nav_name']."\", \"".$showNav['nav_link']."\", \"".$showNav['nav_position']."\", \"".$showNav['nav_permission']."\");'>edit</a>
            <a onClick='deleteNav(\"".$showNav['nav_name']."\");'> Delete </a><br />");
		}
    ?></p>
    <p>  <button id= "edit_menu" class="edit_menu"> New Menu</button>
    <div style="display: none" class="hidden_menu">
        <form  id="menu_manager" action="edit_menu.php" method="post" name="post">
            Name <input id="nav_name" name="nav_name" type="text" size="15"/> <br />
            Link <input id="nav_link" name="nav_link" type="text" size="15"/> <br />
            Position <input id="nav_position" name="nav_position" type="text" size="5"/> <br />
            Permission <input id="nav_permission" name="nav_permission" type="text" size="5"/> <br />
            <input type="hidden" name="create" id="create" value="yes"/>
            <input name="submit" id="submit" type="submit" value="create"/>
		</form>
            
    </div>
    </p>
    </div>
    </div>
    
  </div>
    <div id="content_right">
 <div class="box">
    <div class="box-header">New Blog</div>
    <div class="box-body">
    <p><form action="new_blog.php" method="post" name="post">
        <p>Title:<br />
        <input name="postName" type="text" size="45" />
        </p>

        <p>Preview:<br />
        <textarea name="postPreview" cols="50" rows="3"></textarea>
        </p>

        <p>Content:<br />
        <textarea name="postContent" cols="50" rows="10"></textarea>
        </p>

        <input name="add_post" type="submit" value="Add Post"/>
    </form></p>
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

