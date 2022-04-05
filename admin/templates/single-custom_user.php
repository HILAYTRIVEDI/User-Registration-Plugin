<?php


get_header();
global $post;

$first_name = get_post_meta( $post->ID,  'custom_user_first_name', true );
$last_name = get_post_meta( $post->ID,  'custom_user_lastname_name', true );
$email = get_post_meta( $post->ID,  'custom_user_email', true );
$dob = get_post_meta( $post->ID,  'custom_user_dob', true );
$add = get_post_meta( $post->ID,  'custom_user_address', true );
$add2 = get_post_meta( $post->ID,  'custom_user_address_two', true );
$postal = get_post_meta( $post->ID,  'custom_user_postal', true );
$skills = get_post_meta( $post->ID,  'custom_user_skills', true );
$hobby = get_post_meta( $post->ID,  'custom_user_hobby', true );
$ratings = get_post_meta( $post->ID,  'custom_user_ratings', true );

if (has_post_thumbnail( $post->ID ) ):
    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
endif

?>

<main id="site-content">
    <div class="container">
       <div class="cust-user-single__wrapper">
           <div class="cust-user-single__banner" style="background-image : url(<?php echo esc_url($image[0])?>);">
               <h1 class="cust-user-single__heading">
                    <?php echo esc_html(get_the_title($post->ID))?>
               </h1>
           </div>
           <div class="cust-user-single__details">
               <ul class="cust-user-single__details--list">
                   <li><span>First Name: </span><?php echo esc_html($first_name)?></li>
                   <li><span>Last Name: </span><?php echo esc_html($last_name)?></li>
                   <li><span>Email: </span><?php echo esc_html($email)?></li>
                   <li><span>Date Of Birth: </span><?php echo esc_html($dob)?></li>
                   <li><span>Address 1: </span><?php echo esc_html($add)?></li>
                   <li><span>Address 2: </span><?php echo esc_html($add2)?></li>
                   <li><span>Postal Code: </span><?php echo esc_html($postal)?></li>
                   <li><span>Skills: </span><?php echo esc_html($skills)?></li>
                   <li><span>Hobby: </span><?php echo esc_html($hobby)?></li>
                   <li><span>Ratings: </span><?php echo esc_html($ratings)?>/5</li>
               </ul>
           </div>
       </div>
    </div>
</main>

<?php get_footer(); ?>
