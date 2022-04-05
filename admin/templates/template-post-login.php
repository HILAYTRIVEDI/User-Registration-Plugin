<?php

/**
* Add page templates.
*
* @param  array  $templates  The list of post
*
* @return array  $templates  The modified list of post list
*/

get_header();

?>

<header class="entry-header alignwide">
    <?php the_title( '<h1 class="custom_user_template--title">', '</h1>' ); ?>
</header>
<div class="custom_user_template--content">
    <?php
       echo  do_shortcode('[custom_user_login--form]', true);
    ?>
</div>

<?php 

get_footer();

?>