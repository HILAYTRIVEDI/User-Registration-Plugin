<?php

/**
* Add page templates.
*
* @param  array  $templates  The list of post
*
* @return array  $templates  The modified list of post list
*/

get_header();

if(get_queried_object(  )){
    $cust_category = get_queried_object(  );
    $cust_user_id = $cust_category->term_id ;
} else {
    $cust_user_id = "";
}

?>

<header class="entry-header alignwide">
    <?php the_title( '<h1 class="custom_user_template--title">', '</h1>' ); ?>
</header>
<div class="custom_user_template--content">
    <?php
       echo  do_shortcode('[custom_user_search_tool_list category='.$cust_user_id.']');
    ?>
</div>

<?php 

get_footer();

?>