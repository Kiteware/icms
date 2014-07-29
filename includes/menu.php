<ul>
<?php 
    $navigation = $pages->list_nav();
    foreach ($navigation as $showNav){
        echo "<li><a href=\"".$showNav['nav_link']."\">".$showNav['nav_name']."</a></li>";
    }
?>
</ul>