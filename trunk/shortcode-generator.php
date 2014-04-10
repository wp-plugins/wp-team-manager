<?php

/********************* ADD SUB PAGES ***********************/
add_action('admin_menu', 'register_team_manager_submenu_page');

function register_team_manager_submenu_page() {
	
   $tm_pagemenu = add_submenu_page( 'edit.php?post_type=team_manager', 'Shortcode Generator', 'Shortcode Generator', 'manage_options', 'team-manager-shortcode-generator', 'team_manager_submenu_page_callback' ); 

   add_action($tm_pagemenu, 'team_manager_add_admin_script');

}

/********************* JS enqueue functions ***********************/

function team_manager_add_admin_script() {

            wp_register_script( 'team-manager-admin-js', plugins_url( '/js/short-code-builder.js' , __FILE__ ), array('jquery') );
            wp_enqueue_script( 'team-manager-admin-js' ); 

            wp_register_style( 'team-manager-admin-css', plugins_url( '/css/tm-admin.css' , __FILE__ ));
            wp_enqueue_style( 'team-manager-admin-css' );
    } 


function team_manager_submenu_page_callback() {

	?>
    
    <div class="wrap"><div id="icon-tools" class="icon32"></div>
        <h2>Shortcode Generator</h2>
        <div id="shortcode_options_wrapper">
          <form id="tm_short_code">
            <p><label for="cat">Select Team Group: </label>
              <select name="tm_cat" id="tm_cat">
                          
               <option value="0">All Group</option>
              <?php 
    
               $terms = get_terms("team_groups");
               $count = count($terms);
               if ( $count > 0 ){
                 
                 foreach ( $terms as $term ) {
                    echo "<option value='".$term->slug."'>".$term->name."</option>";
                   }
                 
                 }
            
            ?> 
            </select>              
            </p>
            <p>
                <label for="tm_orderby">Order By:</label>
                <select id="tm_orderby" name="tm_orderby">
                  <option value="menu_order">Default</option>
                  <option value="title">Name</option>
                  <option value="ID">ID</option>
                  <option value="date">Date</option>
                  <option value="modified">Modified</option>
                  <option value="rand">Random</option>
                </select>
              </p>
              <p><label for="tm_limit">Number of entries to display: </label><input id="tm_limit" type="text" value="0"></p>
               <p><label for="tm_remove_id">Remove ids from list (Example: 1,5,7): </label><input id="tm_remove_id" type="text" value=""></p>
             <p>
                <label for="tm_layout">Select template:</label>
                <select id="tm_layout" name="tm_layout">
                  <option value="grid">Grid</option>
                  <option value="list">List</option>
                </select>
              </p>
             <p>
                <label for="tm_image_layout">Select image style:</label>
                <select id="tm_image_layout" name="tm_layout">
                  <option value="rounded">Rounded</option>
                  <option value="circle">Circle</option>
                  <option value="boxed">Boxed</option>
                </select>
              </p>
             <p>
                <label for="tm_image_size">Select image size:</label>
                <?php global $_wp_additional_image_sizes; ?>
                <select id="tm_image_size" name="tm_image_size">
                  <option value="thumbnail">thumbnail</option>
                  <?php foreach ($_wp_additional_image_sizes as $size_name => $size_attrs): ?>
                    <option value="<?php echo esc_attr( $size_name ); ?>"><?php echo esc_html( $size_name ) ; ?></option>
                  <?php endforeach; ?>
                </select>
              </p>                           
            </div>
            <div id="shortcode_output_box">[team_manager category='0' orderby='menu_order' limit='0' exclude='' layout='grid' image_layout='rounded' ]</div>
        </form> 
    </div>

    <?php }

  // Add Shortcode
  function team_manager_fn ($atts, $content = null) {
	
	ob_start();
	
	global $_wp_additional_image_sizes;

    extract( shortcode_atts( array(
      'team_groups' => '',
      'orderby' => 'menu_order',
      'layout' => 'grid',
      'image_layout' => 'rounded'
    ), $atts ) );


    // get social settings
    $social_size = get_option('tm_social_size');

    $asc_desc = 'DESC';
    if ($atts['orderby'] == 'title' || $atts['orderby'] == 'menu_order') {
      $asc_desc = 'ASC';
    }

    $posts_per_page = -1;
    if($atts['limit'] >= 1) { 
    $posts_per_page = $atts['limit'];
    } 

    $layout = $atts['layout'];
    $image_layout = $atts['image_layout']; 
    $image_size = $atts['image_size'];   

    $args = array( 
             'post_type' => 'team_manager',
             'team_groups'=> $atts['category'] ,  
             'posts_per_page'=> $posts_per_page, 
             'orderby' => $atts['orderby'], 
             'order' => $asc_desc
             ); 

          if($atts['exclude'] != '0' && $atts['exclude'] != '') {

           $postnotarray = explode(',', $atts['exclude']);

           if($postnotarray[0]!='') {

            $args['post__not_in'] = $postnotarray;

            }

          }

    $tm_loop = new WP_Query( $args );      

    // The Loop
    if ( $tm_loop->have_posts() ) {     
            echo '<ul class="team-list">';
      while ( $tm_loop->have_posts() ) {
        $tm_loop->the_post();

        $post_id = get_the_ID();
        $title = the_title_attribute( 'echo=0' );
        if (is_array($_wp_additional_image_sizes) && array_key_exists($image_size, $_wp_additional_image_sizes)){
          $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $image_size );   
        }else{
          $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );   
        }          
        $width = $image[1];
        $job_title = get_post_meta($post_id,'tm_jtitle',true);
        $telephone = get_post_meta($post_id,'tm_telephone',true);
        $location = get_post_meta($post_id,'tm_location',true);
        $web_url = get_post_meta($post_id,'tm_web_url',true);
        $vcard = get_post_meta($post_id,'tm_vcard',true);
        $facebook = get_post_meta($post_id,'tm_flink',true);
        $twitter = get_post_meta($post_id,'tm_tlink',true);
        $linkedIn = get_post_meta($post_id,'tm_llink',true);
        $googleplus = get_post_meta($post_id,'tm_gplink',true);
        $dribbble = get_post_meta($post_id,'tm_dribbble',true);
        $youtube = get_post_meta($post_id,'tm_ylink',true);
        $vimeo = get_post_meta($post_id,'tm_vlink',true);
        $emailid = get_post_meta($post_id,'tm_emailid',true);
          

        echo '<li class="'.$layout.'">';

        if ($layout=='grid') {
          echo '<div class="team-member-info">';
          if (isset($image[0])) {
            echo "<img class='team-picture ".$image_layout."' src='".$image[0]."' width='".$width."' title='".$title."' />";
          }else{
            echo "<img class='team-picture ".$image_layout."' src='".plugins_url( 'img/demo.gif',__FILE__)."' width='150' title='".$title."' />";
          }
          
          echo '<ul class="team-member-socials size-'.$social_size.'">';
          if (!empty($facebook)) {
            echo '<li><a class="facebook-'.$social_size.'" href="' . $facebook. '" title="Facebook">Facebook</a></li>';
          }
          if (!empty($twitter)) {
            echo '<li><a class="twitter-'.$social_size.'" href="' . $twitter. '" title="Twitter">Twitter</a></li>';
          }
          if (!empty($linkedIn)) {
            echo '<li><a class="linkedIn-'.$social_size.'" href="' . $linkedIn. '" title="LinkedIn">LinkedIn</a></li>';
          }
          if (!empty($googleplus)) {
            echo '<li><a class="googleplus-'.$social_size.'" href="' . $googleplus. '" title="Google Plus">Google Plus</a></li>';
          }
          if (!empty($dribbble)) {
            echo '<li><a class="dribbble-'.$social_size.'" href="' . $dribbble. '" title="Dribbble">Dribbble</a></li>';
          }        
          if (!empty($youtube)) {
            echo '<li><a class="youtube-'.$social_size.'" href="' . $youtube. '" title="Youtube">Youtube</a></li>';
          }
          if (!empty($vimeo)) {
            echo '<li><a class="vimeo-'.$social_size.'" href="' . $vimeo. '" title="Vimeo">Vimeo</a></li>';
          }
          if (!empty($emailid)) {
            echo '<li><a class="emailid-'.$social_size.'" href="mailto:' . $emailid. '" title="Email">Email</a></li>';
          }                                                        
          echo '</ul></div>';
        }

        echo "<div class='team-member-des'>";
        echo '<h2 class="team-title">' . $title. '</h2>';
        echo '<h4 class="team-position">' . $job_title. '</h4>';

        if ($layout=='list') {
          echo '<div class="team-member-info">';
          if (isset($image[0])) {
            echo "<img class='team-picture ".$image_layout."' src='".$image[0]."' width='".$width."' title='".$title."' />";
          }else{
            echo "<img class='team-picture ".$image_layout."' src='".plugins_url( 'img/demo.gif',__FILE__)."' width='150' title='".$title."' />";
          }
          
          echo '<ul class="team-member-socials size-'.$social_size.'">';
          if (!empty($facebook)) {
            echo '<li><a class="facebook-'.$social_size.'" href="' . $facebook. '" title="Facebook">Facebook</a></li>';
          }
          if (!empty($twitter)) {
            echo '<li><a class="twitter-'.$social_size.'" href="' . $twitter. '" title="Twitter">Twitter</a></li>';
          }
          if (!empty($linkedIn)) {
            echo '<li><a class="linkedIn-'.$social_size.'" href="' . $linkedIn. '" title="LinkedIn">LinkedIn</a></li>';
          }
          if (!empty($googleplus)) {
            echo '<li><a class="googleplus-'.$social_size.'" href="' . $googleplus. '" title="Google Plus">Google Plus</a></li>';
          }
          if (!empty($dribbble)) {
            echo '<li><a class="dribbble-'.$social_size.'" href="' . $dribbble. '" title="Dribbble">Dribbble</a></li>';
          }        
          if (!empty($youtube)) {
            echo '<li><a class="youtube-'.$social_size.'" href="' . $youtube. '" title="Youtube">Youtube</a></li>';
          }
          if (!empty($vimeo)) {
            echo '<li><a class="vimeo-'.$social_size.'" href="' . $vimeo. '" title="Vimeo">Vimeo</a></li>';
          }
          if (!empty($emailid)) {
            echo '<li><a class="emailid-'.$social_size.'" href="mailto:' . $emailid. '" title="Email">Email</a></li>';
          }                                                        
          echo '</ul></div>';
        }    

        echo  the_content();

        echo '<ul class="team-member-other-info">';
        if (!empty($telephone)) {
          echo '<li><strong>Tel:</strong> '.$telephone.'</li>';
        }
        if (!empty($location)) {
          echo '<li><strong>Location:</strong> '.$location.'</li>';
        }        
        if (!empty($web_url)) {
          echo '<li><strong>Blog:</strong> <a href="'.$web_url.'">Link</a></li>';
        }  
        if (!empty($vcard)) {
          echo '<li><strong>Vcard:</strong> <a href="'.$vcard.'">Download</a></li>';
        }                                                                
        echo '</ul>';

        echo "</div>";

        echo '</li>';

      }
          echo '</ul>';

    } else {
      // no posts found
    }
    /* Restore original Post Data */
    wp_reset_postdata();
    
    return ob_get_clean();

  }
  add_shortcode( 'team_manager', 'team_manager_fn' );




     ?>
