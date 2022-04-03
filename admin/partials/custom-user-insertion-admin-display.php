<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<form method="POST" action="options.php" enctype="multipart/form-data">

    <?php 

        settings_fields('custom_user_skills');
        do_settings_sections('custom_user_skills');

        submit_button(); 

    ?>

</form>