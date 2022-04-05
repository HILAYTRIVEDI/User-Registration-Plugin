<div class="custom-user-admin-page">
    <label for="" class="custom-user-admin-page__skill--label">Please add new skill in new line</label>
    <textarea name="custom-user-admin-page__skill--list" id="custom-user-admin-page__skill--text" cols="30" rows="10" class="custom-user-admin-page__skill--text"><?php echo esc_html(get_option( 'custom-user-admin-page__skill--list'))?></textarea>
    <label for="" class="custom-user-admin-page__email--label">Please add email on which you want to receive confrmation mail</label>
    <input name="custom-user-admin-page__email" id="custom-user-admin-page__email--text" class="custom-user-admin-page__email--text" value="<?php echo esc_attr(get_option( 'custom-user-admin-page__email'))?>">
</div>
