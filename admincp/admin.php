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
            <a onClick='deleteNav(\"".$showNav['nav_link']."\");'> Delete </a><br />");
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
      <div class="box">
    <div class="box-header">Why Do We Use It?</div>
    <div class="box-body">
     <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
    when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
    </div>
    
  </div>
      </div>
 <div id="content_right">
 <div class="box">
    <div class="box-header">New Blog</div>
    <div class="box-body">
    <p><form action="index.php?new_blog.php" method="post" name="post">
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
 <div class="box">
    <div class="box-header">Why Do We Use It?</div>
    <div class="box-body">
    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. 
    The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
    
    </div>
    
  </div>
    
    </div>
  </div>
    </div>
