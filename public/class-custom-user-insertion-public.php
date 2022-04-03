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

		public function custom_user_search_tool_form_handler(){
			ob_start(); ?>
			<div class="container">
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
								$skills = get_option( '	custom-user-admin-page__skill--list' );
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
									'child_of'          => 0,
									'exclude'           => '',
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
							'exclude'           => '',
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
								'posts_per_page' 	=> -1,
								'tax_query'			=> array(
									array (
										'taxonomy' => 'user_category',
										'field' => 'ID',
										'terms' => $shortcode_args['category'],
									)
								)
							);
	
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
									$image = wp_get_attachment_image_src( get_post_thumbnail_id( $current_post_id ), 'single-post-thumbnail' );
									?>
									
									<a href="<?php echo esc_url(get_the_permalink($current_post_id)) ?>" class="custom-user-tool__list--link" data-dob="<?php echo esc_attr($dob)?>">
										<div class="custom-user-tool__list--item">
											<img src="<?php echo esc_url($image[0]); ?>" class="custom-user__avatar" alt="User Avatar">
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
														<li><?php echo $skill_name ?></li>
													<?php } ?>
												</ul>
											</div>
										</div>
									</a>

							<?php	endwhile;
							endif;
						?>
					</div>
				</div>
			</div>
		<?php
			$html = ob_get_clean();
			return $html;
		}
		public function custom_search_listing_data_callback(){

			check_ajax_referer( 'ajax-nonce', 'nonce' );
			
			$meta_query = array('relation' => 'AND');
			$args = array(
				'post_type' 		=> "custom_user",
				'post_status'		=> 'publish',
				'orderby'           => 'title',
				'order'             => 'ASC',
			);

			if (isset( $_GET['keyWord'] ) && !empty( $_GET['keyWord'] )) {
				$custom_user_keyword =  sanitize_text_field($_GET['keyWord']);
				$args['s'] = $custom_user_keyword;
			}

			if (isset( $_GET['category'] ) && !empty( $_GET['category'] )) {
				$custom_category =  sanitize_text_field($_GET['category']);
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
				<div class="custom-user-tool__list">
					<div class="custom-user-tool__list--wrapper">
						<?php 
							$args = array(
								'post_type' 		=> "custom_user",
								'post_status'		=> 'publish',
								'orderby'           => 'title',
								'order'             => 'ASC',
								'posts_per_page' 	=> -1,
							);

							$query = new WP_Query($args);

							if( $query->have_posts(  ) ):
								while( $query->have_posts(  ) ):
									$query->the_post(); 
									$current_post_id = esc_html(get_the_ID(  ));
									$name = get_the_title( $current_post_id );
									$dob = get_post_meta( $current_post_id,  'custom_user_dob', true );
									$email = get_post_meta( $current_post_id,  'custom_user_email', true );
									$skills = get_post_meta( $current_post_id,  'custom_user_skills', true );
									$skills_array = explode( " ", $skills );
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
														<li><?php echo $skill_name ?></li>
													<?php } ?>
												</ul>
											</div>
										</div>
									</a>

							<?php	endwhile;
							endif;
						?>
					</div>
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

			$user_name=( isset( $_POST['userName'] ) && !empty( $_POST['userName'] ) ) ? $_POST['userName'] :"";
			$name=( isset( $_POST['name'] ) && !empty( $_POST['name'] ) ) ? $_POST['name'] :""; 
			$surname=( isset( $_POST['surname'] ) && !empty( $_POST['surname'] ) ) ? $_POST['surname'] :"";
			$email=( isset( $_POST['email'] ) && !empty( $_POST['email'] ) ) ? $_POST['email'] :"";
			$user_avatar=( isset( $_POST['userAvatar'] ) && !empty( $_POST['userAvatar'] ) ) ? $_POST['userAvatar'] :"";
			$address=( isset( $_POST['address'] ) && !empty( $_POST['address'] ) ) ? $_POST['address'] :"";
			$secondary_address=( isset( $_POST['secondary_address'] ) && !empty( $_POST['secondary_address'] ) ) ? $_POST['secondary_address'] :"";
			$date_of_birth=( isset( $_POST['date_of_birth'] ) && !empty( $_POST['date_of_birth'] ) ) ? $_POST['date_of_birth'] :"";
			$user_postal = ( isset( $_POST['user_postal'] ) && !empty( $_POST['user_postal'] ) ) ? $_POST['user_postal'] :"";
			$user_hobbies = ( isset( $_POST['user_hobby'] ) && !empty( $_POST['user_hobby'] ) ) ? $_POST['user_hobby'] :"" ;
			$user_skills = ( isset( $_POST['custom_user_skill'] ) && !empty( $_POST['custom_user_skill'] ) ) ? $_POST['custom_user_skill'] :"" ;
			$custom_user_cat = ( isset( $_POST['custom_user_cat'] ) && !empty( $_POST['custom_user_cat'] ) ) ? $_POST['custom_user_cat'] :"" ;
			$multi_select_compone = ( isset( $_POST['states'] ) && !empty( $_POST['states'] ) ) ? $_POST['states'] :"" ;
			

			$final_user_avatar = $user_avatar['image']['name'];

			$my_cptpost_args = array(

				'post_title'    => $user_name,
				'post_status'   => 'draft',
				'post_type' 	=> 'custom_user',
				'tax_input' 	=> array( 'user_category' => $custom_user_cat),

				'meta_input' 	=> array(
					'custom_user_first_name'		=> $name,
					'custom_user_lastname_name'		=> $surname,
					'custom_user_email'				=> $email,
					'custom_user_dob' 				=> $date_of_birth,
					'custom_user_address' 			=> $address,
					'custom_user_postal' 			=> $user_postal,
					'custom_user_skills' 			=> $user_skills,
					'custom_user_hobby'				=> $user_hobbies,
					'custom_multi_field'            => $multi_select_compone,
				)	
			);
			$cpt_id = wp_insert_post( $my_cptpost_args );
			if ($_FILES) {
                foreach ($_FILES as $file => $array) {
                    if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
                        return "upload error : " . $_FILES[$file]['error'];
                    }
                    $attach_id = media_handle_upload($file, $cpt_id);
                }
            }
            if ($attach_id > 0) {
                //and if you want to set that image as Post then use:
                update_post_meta($cpt_id, '_thumbnail_id', $attach_id);
            }

            $my_post1 = get_post($attach_id);
            $my_post2 = get_post($cpt_id);
            $my_post = array_merge($my_post1, $my_post2);
	
		}
	}

	$Custom_User_Insertion_Public = new Custom_User_Insertion_Public;

}