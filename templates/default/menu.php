<nav>
    <button class="nav-trigger">
      <span class="entypo-menu"></span>
    </button>
    <button class="nav-close">
      <span class="entypo-cancel"></span>
    </button>
    <ul class="main-nav">
<?php 
    $navigation = $pages->list_nav();
    foreach ($navigation as $showNav){
        echo "<li><a href=\"".$showNav['nav_link']."\">".$showNav['nav_name']."</a></li>";
    }
?>
    </ul>
  </nav>