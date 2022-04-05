<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/public
 * @author     Hilay Trivedi <hilay.trivedi@multidos.com>
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) die("No Hacking!");

if( !class_exists('Custom_User_Insertion_Public') ){

	class Custom_User_Insertion_Public {

		public function __construct(){
			add_shortcode( 'custom_user_search_tool_form', array($this, 'custom_user_search_tool_form_handler') );
			add_shortcode( 'custom_user_search_tool_list', array($this, 'custom_user_search_tool_list_handler') );
			add_shortcode( 'custom_user_login--form', array($this, 'custom_user_login_form_handler') );
		}
		
		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
	
			wp_enqueue_style( "Custom_User_Insertion_public_css", plugin_dir_url( __FILE__ ) . 'css/main.css', array(), "1.0.0", 'all' );
			wp_enqueue_style( "Custom_User_Insertion_multiselect_dropdown_css", 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), "1.0.0", 'all' );
		}
	
		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
	
			wp_enqueue_script( "Custom_User_recaptcha", 'https://www.google.com/recaptcha/api.js', array( 'jquery' ), "1.0.0", false );
			wp_enqueue_script( "multistepper_validator_js", plugin_dir_url( __FILE__ ) . 'js/jquery_validator.js', array( 'jquery' ), "1.0.0", false );
			wp_enqueue_script( "Custom_User_Insertion_multiselect_dropdown_js", 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), "1.0.0", false );
			wp_enqueue_script( "multistepper_js", plugin_dir_url( __FILE__ ) . 'js/jquery.steps.min.js', array( 'jquery' ), "1.0.0", false );
			wp_enqueue_script( "Custom_User_Insertion_public_js", plugin_dir_url( __FILE__ ) . 'js/custom-user-insertion-public.js', array( 'jquery' ), "1.0.0", false );
			wp_localize_script('Custom_User_Insertion_public_js', 'Custom_User_params', array('ajaxurl' => admin_url( 'admin-ajax.php' ),'nonce' => wp_create_nonce('ajax-nonce')));
		}

		public function wp_dropdown_cats_multiple( $output, $r ) {

			if( isset( $r['multiple'] ) && $r['multiple'] ) {
		
				 $output = preg_replace( '/^<select/i', '<select multiple', $output );
		
				$output = str_replace( "name='{$r['name']}'", "name='{$r['name']}[]'", $output );
		
				foreach ( array_map( 'trim', explode( ",", $r['selected'] ) ) as $value )
					$output = str_replace( "value=\"{$value}\"", "value=\"{$value}\" selected", $output );
		
			}
		
			return $output;
		}

		public function Custom_user_script_loader_tag($tag, $handle) {
	
			if ($handle === 'Custom_User_recaptcha') {
				
				if (false === stripos($tag, 'async')) {
					
					$tag = str_replace(' src', ' async="async" src', $tag);
					
				}
				
				if (false === stripos($tag, 'defer')) {
					
					$tag = str_replace('<script ', '<script defer ', $tag);
					
				}
				
			}
			
			return $tag;
			
		}

		public function custom_user_search_tool_form_handler(){
			ob_start(); ?>
			<div class="custom-user-registration-form-container" id="custom-user-registration-form-container">
				<form id="contact" action="#" method="post">
					<div>
						<h3>Account</h3>
						<section>
							<label for="userName">User name *</label>
							<input id="userName" name="userName" type="text" class="required user_input">
							<label for="name">First name *</label>
							<input id="name" name="name" type="text" class="required user_input">
							<label for="surname">Last name *</label>
							<input id="surname" name="surname" type="text" class="required user_input">
							<label for="email">Email *</label>
							<input id="email" name="email" type="text" class="required">
							<p>(*) Mandatory</p>
						</section>
						<h3>Profile Photo</h3>
						<section>
							<label for="profile_photo">Please Upload Your Profile Photo</label>
							<input id="profile_photo" name="profile_photo" class="required" type="file"  accept="image/*">
							<img src="#" id="profile_photo_preview"  alt="User Avatar">
							<p>(*) Mandatory</p>
						</section>
						<h3>More Details</h3> 
						<section>
							<label for="address">Primary Address *</label>
							<span> Note: Please don't include -,.,@ or any special characters in the Adress</span>
							<input id="address" name="address" type="text" class="required user_input">
							<label for="secondary_address">Secondary Address</label>
							<input id="secondary_address" name="secondary_address" type="text" class="user_input">
							<label for="user_postal">Please Enter Your Postal Code *</label>
							<input id="user_postal" name="user_postal" type="number" class="required user_input">
							<label for="date_of_birth">Date Of Birth *</label>
							<input id="date_of_birth" name="date_of_birth" type="date" class="required">
							<label for="user_hobby">What are your hoibbies ?* ( write your hobbies seperated by " " )</label>
							<input id="user_hobby" name="user_hobby" type="text" class="required user_input">
							<label for="custom_user_skill">What are your skills ? *</label>
							<?php 
								$skills = get_option( 'custom-user-admin-page__skill--list' );
								$skills_new_array = explode("\n",$skills);
							?>
							<select name="custom_user_skill" id="custom_user_skill" class="custom_user_skill required" name="skills[]" multiple="multiple">
								<?php 
									foreach( $skills_new_array as $ops ){ ?>
										<option value="<?php echo esc_attr($ops)?>"><?php echo esc_html($ops)?></option>
								<?php	}
								?>
							</select>
							<label for="custom_user_skill">Select the User category? *</label>
							<?php 
							$args = array(
									'show_option_all'	=> "",
									'orderby'           => 'id',
									'order'             => 'ASC',
									'show_count'        => 0,
									'hide_empty'        => 0,
									'exclude'			=> 0,
									'child_of'          => 0,
									'echo'              => 1,
									'selected'          => 0,
									'hierarchical'      => 0,
									'name'              => 'custom_user_cat',
									'id'                => 'custom_user_cat',
									'class'             => 'custom_user_cat required',
									'depth'             => 0,
									'tab_index'         => 0,
									'taxonomy'          => array('user_category'),
									'hide_if_empty'     => false,
									'option_none_value' => -1,
									'value_field'       => 'term_id',
									'required'          => false,
									'multiple'          => true
								);
								
								wp_dropdown_categories( $args );					
							?>
							<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( MYCAPTCHAKEY )?>"></div><br/>
							<div class="captcha-error-message" id="captcha-error-message"></div>
							<p>(*) Mandatory</p>
						</section>
					</div>
				</form>
			</div>

		<?php
			$html = ob_get_clean();
			return $html;
		}

		public function custom_user_search_tool_list_handler( $attr ){
			$shortcode_args = shortcode_atts( array(
				'category' => ""
			), $attr );

			if(isset( $_GET['nonce'] ) && !empty( $_GET['nonce'] )){
				if (!wp_verify_nonce($_GET['nonce'], 'ajax-nonce') ) {
					die();
				}
			}

			$registered_user_id=( isset( $_GET['registered_user_id'] ) && !empty( $_GET['registered_user_id'] ) ) ?  sanitize_text_field($_GET['registered_user_id']) :"";
			$custom_user_password=( isset( $_GET['custom_user_password'] ) && !empty( $_GET['custom_user_password'] ) ) ?  sanitize_text_field($_GET['custom_user_password']) :"";
			$custom_user_email = ( isset( $_GET['email'] ) && !empty( $_GET['email'] ) ) ?  sanitize_email( $_GET['email'] ) :"";
			if ($registered_user_id !== "") {
                wp_update_post(array(
                'ID'    =>  $registered_user_id,
                'post_status'   =>  'publish'
                ));
				wp_mail($custom_user_email, "Registration Approved", 'Admin approved you registration Your password is '.$custom_user_password.' Please do not share with anyone');
            }
			ob_start(); ?>
			<div class="custom-user-tool__container">
				<div id="custom-user-tool__search--form" class="custom-user-tool__search--wrapper">
					<div class="custom-user-tool__search--field">
						<label for="custom-user-tool__search--keyword"> Search User by Keyword</label>
						<input type="text" id="custom-user-tool__search--keyword" class="custom-user-tool__search--keyword user_input">
					</div>
					<div class="custom-user-tool__search--field">
						<label for="custom-user-tool__search--dob"> Search User by Date Of Birth</label>
						<div class="custom-user-tool__search--dob-wrapper">
							<span>From: </span>
							<input type="date" id="custom-user-tool__search--dobfrom" class="custom-user-tool__search--dobfrom">
							<span>To: </span>
							<input type="date" id="custom-user-tool__search--dobto" class="custom-user-tool__search--dobto">
						</div>
					</div>
					<div class="custom-user-tool__search--field">
						<label for="custom-user-tool__search--skill"> Search User by Skill</label>
						<?php 
							$skills = get_option( '	custom-user-admin-page__skill--list' );
							$skills_new_array = explode("\n",$skills);
						?>
						<select name="custom-user-tool__search--skill" id="custom-user-tool__search--skill">
							<option value="">ALL</option>
							<?php 
								foreach( $skills_new_array as $ops ){ ?>
									<option value="<?php echo esc_attr($ops)?>"><?php echo esc_html($ops)?></option>
							<?php	}
							?>	
						</select>
					</div>
					<div class="custom-user-tool__search--field">
						<label for="custom-user-tool__search--dob"> Search User by Category</label>
						<?php 
						
						$args = array(
							'show_option_all'	=> "None",
							'orderby'           => 'id',
							'order'             => 'ASC',
							'show_count'        => 0,
							'hide_empty'        => 0,
							'child_of'          => 0,
							'echo'              => 1,
							'selected'          => $shortcode_args['category'],
							'hierarchical'      => 0,
							'name'              => 'custom_user_cat_public',
							'id'                => 'custom_user_cat_public',
							'class'             => 'custom_user_cat_public required',
							'depth'             => 0,
							'tab_index'         => 0,
							'taxonomy'          => array('user_category'),
							'hide_if_empty'     => false,
							'option_none_value' => -1,
							'value_field'       => 'term_id',
						);
						
						wp_dropdown_categories( $args );				
						?>
					</div>
					<div class="custom-user-tool__search--field">
						<label for="custom-user-tool__search--ratings"> Search User by Ratings:</label>
						<span id="custom-user-tool__search--ratingsvalue">1</span>/5
						<input type="range" id="custom-user-tool__search--ratings" min="1" max="5" class="custom-user-tool__search--ratings" value="1">
					</div>
					<button id="custom-user-tool__search--submit" class="custom-user-tool__search--submit">
						Search User
					</button>
				</div>
				<div class="custom-user-tool__list">
					<div class="custom-user-tool__list--wrapper">
						<?php 
							$args = array(
								'post_type' 		=> "custom_user",
								'post_status'		=> 'publish',
								'orderby'           => 'title',
								'order'             => 'ASC',
								'paged'       		=> 1,
							);

							if(isset($shortcode_args['category']) && !empty($shortcode_args['category'])){
								$args['tax_query']=array(
										array (
											'taxonomy' => 'user_category',
											'field' => 'ID',
											'terms' => $shortcode_args['category'],
										)
									);
							}
	
							$query = new WP_Query($args);

							if( $query->have_posts(  ) ):
								while( $query->have_posts(  ) ):
									$query->the_post(); 
									$current_post_id = esc_html(get_the_ID(  ));
									$name = get_the_title( $current_post_id );
									$dob = get_post_meta( $current_post_id,  'custom_user_dob', true );
									$email = get_post_meta( $current_post_id,  'custom_user_email', true );
									$skills = get_post_meta( $current_post_id,  'custom_user_skills', true );
									$skills_array = explode( ",", $skills );
									$ratings = get_post_meta( $current_post_id, 'custom_user_ratings', true );
									?>
									
									<a href="<?php echo esc_url(get_the_permalink($current_post_id)) ?>" class="custom-user-tool__list--link" data-dob="<?php echo esc_attr($dob)?>">
										<div class="custom-user-tool__list--item">
											<?php
												if (has_post_thumbnail( $current_post_id ) ){
												$image = wp_get_attachment_image_src( get_post_thumbnail_id( $current_post_id ), 'single-post-thumbnail' );
													?>
													<img src="<?php echo esc_url($image[0]); ?>" class="custom-user__avatar" alt="User Avatar">
													<?php
											}?>
											<h6 class="custom-user__name"><span>Name : </span><?php echo esc_html($name)?></h6>
											<p class="custom-user__dob"><span>DOB : </span><?php echo esc_html($dob)?></p>
											<p class="custom-user__email"><span>Email : </span><?php echo esc_html($email)?></p>
											<div class="custom-user__ratings">
												<?php 
													for($i = 0; $i< $ratings ;$i++){ ?>
														<span>★</span>
													<?php }
												?>
											</div>
											<div class="custom-user__skills">
												<span>Skills : </span>
												<ul>
													<?php foreach($skills_array as $skill_name ){ ?>
														<li><?php echo esc_html( $skill_name ) ?></li>
													<?php } ?>
												</ul>
											</div>
										</div>
									</a>

							<?php	endwhile;
							endif;
						?>
					</div>
					<?php
						$total_pages = $query->max_num_pages;
						if ( $total_pages > 1 ) {
						?>
							<div class="custom-user-pagination-section">
								<div class="custom-user-pagination-leftarrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="16.084" height="26.635" class="home-testimonial__left-arrow" viewBox="0 0 16.084 26.635">
										<path id="Path_156" data-name="Path 156" d="M707.492,845.393l12-12,12,12" transform="matrix(0.035, -0.999, 0.999, 0.035, -855.42, 703.096)" fill="none" stroke-width="3"/>
									</svg>
								</div>
								<div class="custom-pagination" id="custom-pagination" >
									<?php for ( $i = 1; $i <= $total_pages; $i++ ) { ?>
										<span class='page-numbers page-number<?php echo $i; ?>' page-no=<?php echo esc_attr( $i ); ?> ><?php echo esc_html( $i ); ?></span>
									<?php } ?>
								</div>
								<div class="custom-user-pagination-rightarrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="15.182" height="26.121" class="home-testimonial__right-arrow" viewBox="0 0 15.182 26.121">
										<path id="Path_156" data-name="Path 156" d="M707.492,845.393l12-12,12,12" transform="translate(846.454 -706.432) rotate(90)" fill="none" stroke-width="3"/>
									</svg>
								</div>
							</div>
						<?php } ?>
				</div>
			</div>
		<?php
			$html = ob_get_clean();
			return $html;
		}

		public function custom_user_login_form_handler(){

			if(isset( $_GET['nonce'] ) && !empty( $_GET['nonce'] )){
				if (!wp_verify_nonce($_GET['nonce'], 'ajax-nonce') ) {
					die();
				}
			}

            $user_name=( isset( $_GET['customuser_name'] ) && !empty( $_GET['customuser_name'] ) ) ? sanitize_text_field($_GET['customuser_name']) :"";
            $name=( isset( $_GET['customname'] ) && !empty( $_GET['customname'] ) ) ? sanitize_text_field($_GET['customname']) :""; 
            $surname=( isset( $_GET['surname'] ) && !empty( $_GET['surname'] ) ) ? sanitize_text_field($_GET['surname']) :"";
            $email=( isset( $_GET['email'] ) && !empty( $_GET['email'] ) ) ? sanitize_text_field($_GET['email']) :"";
            $address=( isset( $_GET['address'] ) && !empty( $_GET['address'] ) ) ? sanitize_text_field($_GET['address']) :"";
            $date_of_birth=( isset( $_GET['date_of_birth'] ) && !empty( $_GET['date_of_birth'] ) ) ? sanitize_text_field($_GET['date_of_birth']) :"";
            $user_postal = ( isset( $_GET['user_postal'] ) && !empty( $_GET['user_postal'] ) ) ? sanitize_text_field($_GET['user_postal']) :"";
            $user_hobbies = ( isset( $_GET['user_hobby'] ) && !empty( $_GET['user_hobby'] ) ) ? sanitize_text_field($_GET['user_hobby']) :"" ;
            $user_skills = ( isset( $_GET['user_skill'] ) && !empty( $_GET['user_skill'] ) ) ? sanitize_text_field($_GET['user_skill']) :"" ;
            $custom_user_cat = ( isset( $_GET['custom_user_cat'] ) && !empty( $_GET['custom_user_cat'] ) ) ? $_GET['custom_user_cat'] :"" ;
			// $user_avatar=( isset( $_GET['user_avatar'] ) && !empty( $_GET['user_avatar'] ) ) ? sanitize_text_field($_GET['user_avatar']) :"";
			$custom_user_password = ( isset( $_GET['custom_user_password'] ) && !empty( $_GET['custom_user_password'] ) ) ? $_GET['custom_user_password'] :"" ;

			$args = array(
				'post_type' => 'custom_user',
				'post_status'   => 'draft',
				'meta_query' => array(
						'relation' => 'AND',
						array(
								'key' => 'custom_user_email',
								'value' => $email,
								'compare' => '='),
						)
			);

			$query = new WP_Query($args);

			if( !$query->have_posts(  ) ):
				$my_cptpost_args = array(

					'post_title'    => $user_name,
					'post_status'   => 'draft',
					'post_type'     => 'custom_user',
					'tax_input'     => array( 'user_category' => $custom_user_cat),
	
					'meta_input'    => array(
						'custom_user_first_name'        => $name,
						'custom_user_lastname_name'     => $surname,
						'custom_user_email'             => $email,
						'custom_user_dob'               => $date_of_birth,
						'custom_user_address'           => $address,
						'custom_user_postal'            => $user_postal,
						'custom_user_skills'            => $user_skills,
						'custom_user_hobby'             => $user_hobbies,
						'custom_user_password'			=> $custom_user_password,
					)   
				);
				$cpt_id = wp_insert_post( $my_cptpost_args );
	
				if ($cpt_id !== 0) {
					$custom_admin_mail = get_option( "custom-user-admin-page__email" );
					wp_mail( $custom_admin_mail, 'New User Inquiry', 'New user has been registered! click the link below to verify the user. <a href='.site_url("/").'?post_type=user_category&email='.$email.'&custom_user_password='.$custom_user_password.'&registered_user_id='.$cpt_id.'&nonce='.$_GET['nonce'].'>verify user here</a>' ); 
				}
			endif;

          
            ob_start(); ?>
            <div class="container">
                <form id="custom-user-login-form" class="custom-user-login-form" action="#" method="post" autocomplete="off">
					<div class="custom-user-login-form__title">
						Welcome !!
					</div>
					<div class="custom-user-login-form-error" id="custom-user-login-form-error"></div>
					<div class="custom-user-login-field-wrapper">
                        <label for="loginformEmail">Email</label>
                        <input id="loginformEmail" name="loginformEmail" type="text" class="required">
                    </div>
                    <div class="custom-user-login-field-wrapper">  
                        <label for="loginformPassword">Password</label>
                        <input id="loginformPassword" name="loginformPassword" type="text" class="required user_input">
                    </div>
                    <div class="custom-user-login-button-wrapper">
                        <input id="custom-user-login-submitBtn" class="custom-user-login-submitBtn" value="Login" name="custom_user_login" type="submit"/>
						<a href="<?php echo esc_url( site_url( "/register" ) )?>" id="custom-user-register-submitBtn" class="custom-user-register-submitBtn" name="custom_user_register">Register</a>
                    </div>
                </form>
            </div>
            <?php 
            $html = ob_get_clean();
            return $html;
        }

		public function custom_user_login_verification_callback(){
			check_ajax_referer( 'ajax-nonce', 'nonce' );
			$custom_user_login_email = filter_input( INPUT_POST, 'loginformEmail', FILTER_SANITIZE_STRING );
			$custom_user_login_pass = filter_input( INPUT_POST, 'loginformPassword', FILTER_SANITIZE_STRING );

			$args = array(
				'post_type' => 'custom_user',
				'meta_query' => array(
						'relation' => 'AND',
						array(
								'key' => 'custom_user_email',
								'value' => $custom_user_login_email,
								'compare' => '='),
						array(
								'key' => 'custom_user_password',
								'value' => $custom_user_login_pass,
								'compare' => '=')
						)
			);

			$query = new WP_Query($args);

			if($query->have_posts(  )):
				while($query->have_posts(  )):
					$query->the_post(  );
					$user_post_id = get_the_ID(  );
					$custom_user_status = get_post_status($user_post_id);
					if($custom_user_status === "publish"){
						echo json_encode(array('success' => 1));
					} else {
						echo json_encode(array('success' => 0));
					}
				endwhile;
			else:
				echo json_encode(array('success' => 2));
			endif;
			wp_die();
		}

		public function custom_search_listing_data_callback(){

			check_ajax_referer( 'ajax-nonce', 'nonce' );
			
			$page_no     = filter_input( INPUT_GET, 'page_no', FILTER_SANITIZE_STRING );

			$meta_query = array('relation' => 'AND');
			$args = array(
				'post_type' 		=> "custom_user",
				'post_status'		=> 'publish',
				'orderby'           => 'title',
				'order'             => 'ASC',
				'paged'       		=> $page_no,
			);

			if (isset( $_GET['keyWord'] ) && !empty( $_GET['keyWord'] )) {
				$custom_user_keyword =  sanitize_text_field($_GET['keyWord']);
				$args['s'] = $custom_user_keyword;
			}

			if (isset( $_GET['category'] ) && !empty( $_GET['category'] )) {
				$custom_category =  $_GET['category'];
				$args['tax_query'] = array(
					array (
						'taxonomy' => 'user_category',
						'field' => 'ID',
						'terms' => $custom_category,
					)
				);
			}

			if (isset( $_GET['skills'] ) && !empty( $_GET['skills'] )) {
				$custom_skills =  sanitize_text_field($_GET['skills']);
				$args['meta_query'][] = array(
					'key' => 'custom_user_skills',
					'compare' => '=',
					'value' => $custom_skills,
				);
			}

			if (isset( $_GET['ratings'] ) && !empty( $_GET['ratings'] )) {
				$custom_ratings =  sanitize_text_field($_GET['ratings']);
				$args['meta_query'][] = array(
					'key' => 'custom_user_ratings',
					'compare' => '=',
					'value' => $custom_ratings,
				);
			}

			if (isset( $_GET['dobfrom'] ) && !empty( $_GET['dobfrom'] ) && isset( $_GET['dobto'] ) && !empty( $_GET['dobto'] )) {
				$dob_from =  sanitize_text_field($_GET['dobfrom']);
				$dob_to =  sanitize_text_field($_GET['dobto']);
				$args['meta_query'][] = array(
					'key' 	=> 'custom_user_dob', 
					'value' => array($dob_from, $dob_to),
					'compare' => 'BETWEEN', 
					'type' => 'DATE',
				);
			}

			$query = new WP_Query($args);

			if( $query->have_posts(  ) ):
				?>
				<div class="custom-user-tool__list" current_page='<?php echo esc_attr( $page_no ); ?>'>
					<div class="custom-user-tool__list--wrapper">
						<?php
							while( $query->have_posts(  ) ):
								$query->the_post(); 
								$current_post_id = esc_html(get_the_ID(  ));
								$name = get_the_title( $current_post_id );
								$dob = get_post_meta( $current_post_id,  'custom_user_dob', true );
								$email = get_post_meta( $current_post_id,  'custom_user_email', true );
								$skills = get_post_meta( $current_post_id,  'custom_user_skills', true );
								$skills_array = explode( ",", $skills );
								$ratings = get_post_meta( $current_post_id, 'custom_user_ratings', true );
								?>
								
								<a href="<?php echo esc_url(get_the_permalink($current_post_id)) ?>" class="custom-user-tool__list--link">
									<div class="custom-user-tool__list--item">
										<h6 class="custom-user__name"><span>Name : </span><?php echo esc_html($name)?></h6>
										<p class="custom-user__dob"><span>DOB : </span><?php echo esc_html($dob)?></p>
										<p class="custom-user__email"><span>Email : </span><?php echo esc_html($email)?></p>
										<div class="custom-user__ratings">
											<?php 
												for($i = 0; $i< $ratings ;$i++){ ?>
													<span>★</span>
												<?php }
											?>
										</div>
										<div class="custom-user__skills">
											<span>Skills : </span>
											<ul>
												<?php foreach($skills_array as $skill_name ){ ?>
													<li><?php echo esc_html( $skill_name ) ?></li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</a>

							<?php	endwhile;
						?>
					</div>
					<?php
						$total_pages = $query->max_num_pages;
						if ( $total_pages > 1 ) {
						?>
							<div class="custom-user-pagination-section">
								<div class="custom-user-pagination-leftarrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="16.084" height="26.635" class="home-testimonial__left-arrow" viewBox="0 0 16.084 26.635">
										<path id="Path_156" data-name="Path 156" d="M707.492,845.393l12-12,12,12" transform="matrix(0.035, -0.999, 0.999, 0.035, -855.42, 703.096)" fill="none" stroke-width="3"/>
									</svg>
								</div>
								<div class="custom-pagination" id="custom-pagination" >
									<?php for ( $i = 1; $i <= $total_pages; $i++ ) { ?>
										<span class='page-numbers page-number<?php echo $i; ?>' page-no=<?php echo esc_attr( $i ); ?> ><?php echo esc_html( $i ); ?></span>
									<?php } ?>
								</div>
								<div class="custom-user-pagination-rightarrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="15.182" height="26.121" class="home-testimonial__right-arrow" viewBox="0 0 15.182 26.121">
										<path id="Path_156" data-name="Path 156" d="M707.492,845.393l12-12,12,12" transform="translate(846.454 -706.432) rotate(90)" fill="none" stroke-width="3"/>
									</svg>
								</div>
							</div>
						<?php } ?>
				</div>
			<?php
			else: ?>
				<div class="custom-user-tool__list">
					<div class="custom-user-tool__list--nodata">Sorry No Data Avaliable !!</div>
				</div>
				<?php
			endif;
			wp_die();
		}

		public function custom_user_insertion_form_callback(){

			check_ajax_referer( 'ajax-nonce', 'nonce' );

			$user_nonce = ( isset( $_POST['nonce'] ) && !empty( $_POST['nonce'] ) ) ? sanitize_text_field($_POST['nonce']) :"";
			$user_name=( isset( $_POST['userName'] ) && !empty( $_POST['userName'] ) ) ? sanitize_text_field($_POST['userName']) :"";
			$name=( isset( $_POST['name'] ) && !empty( $_POST['name'] ) ) ? sanitize_text_field($_POST['name']) :""; 
			$surname=( isset( $_POST['surname'] ) && !empty( $_POST['surname'] ) ) ? sanitize_text_field($_POST['surname']) :"";
			$email=( isset( $_POST['email'] ) && !empty( $_POST['email'] ) ) ? sanitize_text_field($_POST['email']) :"";
			// $user_avatar=( isset( $_POST['userAvatar'] ) && !empty( $_POST['userAvatar'] ) ) ? $_POST['userAvatar'] :"";
			$address=( isset( $_POST['address'] ) && !empty( $_POST['address'] ) ) ? sanitize_text_field($_POST['address']) :"";
			$secondary_address=( isset( $_POST['secondary_address'] ) && !empty( $_POST['secondary_address'] ) ) ? sanitize_text_field($_POST['secondary_address']) :"";
			$date_of_birth=( isset( $_POST['date_of_birth'] ) && !empty( $_POST['date_of_birth'] ) ) ? sanitize_text_field($_POST['date_of_birth']) :"";
			$user_postal = ( isset( $_POST['user_postal'] ) && !empty( $_POST['user_postal'] ) ) ? sanitize_text_field($_POST['user_postal']) :"";
			$user_hobbies = ( isset( $_POST['user_hobby'] ) && !empty( $_POST['user_hobby'] ) ) ? sanitize_text_field($_POST['user_hobby']):"" ;
			$user_skills = ( isset( $_POST['custom_user_skill'] ) && !empty( $_POST['custom_user_skill'] ) ) ? sanitize_text_field($_POST['custom_user_skill']) :"" ;
			$custom_user_cat = ( isset( $_POST['custom_user_cat'] ) && !empty( $_POST['custom_user_cat'] ) ) ? $_POST['custom_user_cat'] :"" ;
			$custom_user_password=bin2hex(random_bytes(8));

			global $wpdb;
			$query = "
			SELECT * FROM wp_postmeta where meta_value LIKE '$email' ";
			
			$results = $wpdb->get_results($query);
			$count_of_Results = sizeof($results);

			$custom_user_cat_length = sizeof($custom_user_cat);
			$final_custom_user_cat = $custom_user_cat[$custom_user_cat_length-1];

			$secerate_key = MYCAPTCHASECRETKEY;
			$response_key = $_POST['g-recaptcha-response'];
			$user_IP = $_SERVER['REMOTE_ADDR'];

			$ch = curl_init();

			curl_setopt_array($ch, [
				CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => [
					'secret' => $secerate_key,
					'response' => $response_key,
					'remoteip' => $user_IP
				],
				CURLOPT_RETURNTRANSFER => true
			]);

			$output = curl_exec($ch);
			curl_close($ch);

			$response = json_decode($output);

			if($response->success && $count_of_Results == 0){
				wp_mail( $email, 'Please verify your account', 'Thanks for registration! click the link below to verify. <a href='.site_url("/").'login/?email='.$email.'&custom_user_password='.$custom_user_password.'&customuser_name='.rawurlencode($user_name).'&customname='.rawurlencode($name).'&surname='.rawurlencode($surname).'&date_of_birth='.$date_of_birth.'&address='.rawurlencode($address).'&user_postal='.$user_postal.'&user_skill='.rawurlencode($user_skills).'&user_hobby='.rawurlencode($user_hobbies).'&custom_user_cat='.$final_custom_user_cat.'&nonce='.$user_nonce.'>verify email here</a>' );
				echo json_encode(array('success' => 1)); 
			} else {
				echo json_encode(array('success' => 0));
			}
			wp_die();
		}
	}

	$Custom_User_Insertion_Public = new Custom_User_Insertion_Public;

}