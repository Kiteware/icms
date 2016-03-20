<?php
namespace Nixhatter\ICMS;
?>
<nav>
    <ul class="main-nav">
        <div class="boxed">
        <?php
        $pages    = new model\pagesModel($this->container);
        $navigation = $pages->list_nav();
        foreach ($navigation as $showNav) {
                echo "<li><a href=\"".$showNav['nav_link']."\">".$showNav['nav_name']."</a></li>";
            }
        ?>
        </div>
    </ul>
</nav>
