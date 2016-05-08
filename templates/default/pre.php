<?php
namespace Nixhatter\ICMS;
?>
<header>
    <div class="banner">Intelligent <strong>CMS</strong><br/><small>0.5</small></div>
</header>
<nav>
    <ul class="main-nav">
        <div class="boxed">
        <?php
        $pages    = new model\PagesModel($this->container);
        $navigation = $pages->list_nav();
        foreach ($navigation as $showNav) {
                echo "<li><a href=\"".$showNav['nav_link']."\">".$showNav['nav_name']."</a></li>";
            }
        ?>
        </div>
    </ul>
</nav>
<div class="container">

