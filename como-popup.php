<?php

/*
Plugin Name: Como Popup
Plugin URI: http://www.comocreative.com/
Version: 1.0.2
Author: Como Creative LLC
Description: Plugin designed for easy Popup Modal Messages.  If using shortcode: [comopopup popupid=ID template=TEMPLATE NAME autopop=TRUE/FALSE buttontext='' buttonclass='']
*/

defined('ABSPATH') or die('No Hackers!');

// Include plugin updater. 
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/updater.php' );

// Register Modal Scripts
//wp_enqueue_script('comopopup_automodal', plugins_url('js/comopopup-automodal.js', __FILE__), array('jquery'), '1.0', true);
//wp_register_script('comopopup_automodal', plugins_url('js/comopopup-automodal.js', __FILE__), array('jquery'), '1.0', true);
//wp_print_scripts('comopopup_automodal',900);


function comopopup_scripts_footer() {
	wp_enqueue_script('comopopup_automodal', plugins_url('js/comopopup-automodal.js', __FILE__), array('jquery'), '1.0', true);
}
add_action( 'wp_enqueue_scripts', 'comopopup_scripts_footer', 100 );



/* ##################### Define Modal Popup Post Type ##################### */
if ( ! function_exists('comopopup_post_type') ) {
	function comopopup_post_type() {
		$labels = array(
			'name'                  => _x('Modal Popups', 'Post Type General Type', 'text_domain' ),
			'singular_name'         => _x('Modal Popup', 'Post Type Singular Type', 'text_domain' ),
			'menu_name'             => __('Modal Popups', 'text_domain' ),
			'name_admin_bar'        => __('Modal Popup', 'text_domain' ),
			'archives'              => __('Popup Archives', 'text_domain' ),
			'parent_item_colon'     => __('Parent Popup:', 'text_domain' ),
			'all_items'             => __('All Popups', 'text_domain' ),
			'add_new_item'          => __('Add New Popup', 'text_domain' ),
			'add_new'               => __('Add New', 'text_domain' ),
			'new_item'              => __('New Popup', 'text_domain' ),
			'edit_item'             => __('Edit Popup', 'text_domain' ),
			'update_item'           => __('Update Popup', 'text_domain' ),
			'view_item'             => __('View Popup', 'text_domain' ),
			'search_items'          => __('Search Popups', 'text_domain' ),
			'not_found'             => __('Not found', 'text_domain' ),
			'not_found_in_trash'    => __('Not found in Trash', 'text_domain' ),
			'featured_image'        => __('Popup Image', 'text_domain' ),
			'set_featured_image'    => __('Set Popup image', 'text_domain' ),
			'remove_featured_image' => __('Remove Popup image', 'text_domain' ),
			'use_featured_image'    => __('Use as Popup image', 'text_domain' ),
			'insert_into_item'      => __('Insert into carousel', 'text_domain' ),
			'uploaded_to_this_item' => __('Uploaded to this Popup', 'text_domain' ),
			'items_list'            => __('Popup list', 'text_domain' ),
			'items_list_navigation' => __('Popup list navigation', 'text_domain' ),
			'filter_items_list'     => __('Filter Popups list', 'text_domain' ),
		);
		$rewrite = array(
			'slug'                => 'comopopup',
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$capabilities = array(
			'edit_post'           => 'edit_popup',
			'read_post'           => 'read_popup',
			'delete_post'         => 'delete_popup',
			'delete_posts'         => 'delete_popups',
			'edit_posts'          => 'edit_popups',
			'edit_others_posts'   => 'edit_others_popups',
			'publish_posts'       => 'publish_popups',
			'read_private_posts'  => 'read_private_popups',
		);
		$args = array(
			'label'                 => __('Modal Popup', 'text_domain' ),
			'description'           => __('Popups to be displayed in the Como Strap Modal', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array('title','editor','revisions'),
			'taxonomies'			=> array(),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-admin-page',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false, //Set to false to hide Archive Page		
			'exclude_from_search'   => true,
			'publicly_queryable'    => false, // Set to false to hide Single Pages
			'capability_type'       => 'post',
			'rewrite'               => $rewrite,
			'capabilities'          => $capabilities,
		);
		register_post_type( 'comopopup', $args );
	}
	add_action( 'init', 'comopopup_post_type', 0 );
}

// Give Admin & Editor Access to Popups
function add_comopopup_capability() {
	$roles = array('administrator','editor');
	foreach ($roles as $role) {
		$role = get_role($role);
		$role->add_cap('edit_popup'); 
		$role->add_cap('read_popup');
		$role->add_cap('delete_popup');
		$role->add_cap('delete_popups');
		$role->add_cap('edit_popups');
		$role->add_cap('edit_others_popups');
		$role->add_cap('publish_popups');
		$role->add_cap('read_private_popups');
	}
}
add_action( 'admin_init', 'add_comopopup_capability');

/*
// Modal Popup Taxonomy 
add_action( 'init', 'create_popup_tax', 0 );
function create_popup_tax() {
	$labels = array(
		'name'              => _x( 'Modal Types', 'taxonomy general name' ),
		'singular_name'     => _x( 'Modal Type', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Modals' ),
		'all_items'         => __( 'All Modals' ),
		'parent_item'       => __( 'Parent Modal' ),
		'parent_item_colon' => __( 'Parent Modal:' ),
		'edit_item'         => __( 'Edit Modal Type' ),
		'update_item'       => __( 'Update Modal Type' ),
		'add_new_item'      => __( 'Add New Modal Type' ),
		'new_item_name'     => __( 'New Modal Type' ),
		'menu_name'         => __( 'Modal Types' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'modal-type' ),
	);
	register_taxonomy('modal-type', array('comopopup'), $args );
}
*/

function comopopup_register_options() {
	add_option('autoPop-enabled', 'false');
}
add_action('admin_init', 'comopopup_register_options');

// Add Sorting Dropdown on Admin Screen
add_action( 'restrict_manage_posts', 'comopopup_restrict_manage_posts');
function comopopup_restrict_manage_posts() {
	global $typenow;
	$taxonomy = 'modal-type';
	if( $typenow == "comopopup" ){
		$filters = array($taxonomy);
		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			echo "<option value=''>Show All ". $tax_name ."</option>";
			foreach ($terms as $term) { echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; }
			echo "</select>";
		}
	}
}

function Print_popup_buttons($cnt, $p = null) {
	if ($p === null){
		$a = $b = $c = '';
	} else {
		$a = $p['button-text'];
		$b = $p['button-link'];
		$c = ''; 
		$c = $p['button-close-link'];
		
		$checked  = ''; 
		$checked = (($c) ? (($c=='true') ? 'checked' : '') : '');
		$hideLink = (($checked == 'checked') ? 'style="display: none"' : '');
	}
return  <<<HTML
<li class="comopopup-button-config" style="border-bottom: 1px solid #ccc;"><p><label style="display: block; font-weight: bold">Button Text:</label><input type="text" name="comopopup_data[$cnt][button-text]" class="wide-fat" style="width: 100%" value="$a" /><br><input type="checkbox" class="close-checkbox" name="comopopup_data[$cnt][button-close-link]" value="true" $checked /> <strong>Close Modal Button</strong></p><p class="button-link-info" $hideLink><label style="display: block; font-weight: bold">Button Link:</label><input type="text" name="comopopup_data[$cnt][button-link]" class="button-link-url wide-fat" style="width: 100%" value="$b" /></p><p style="text-align: right"><a class="remove-button" href="#">Remove</a></p></li>
HTML;
}

//add custom field - move
add_action('add_meta_boxes', 'object_init');
function object_init(){
	add_meta_box('comopopup-button-box', __('Modal Buttons','comopopuptextdomain'),'comopopup_button_meta_callback','comopopup','side','default');
}

function comopopup_button_meta_callback(){
 	global $post;

  	$data = get_post_meta($post->ID,"comopopup_data",true);
  	echo '<div>';
  	echo '<ul id="comopopup_buttons">';
  	$c = 0;
	if (is_array($data)) {
		if (count($data) > 0){
			foreach((array)$data as $p ){
				if (isset($p['button-text']) || isset($p['button-link'])) {
					echo Print_popup_buttons($c,$p);
					$c = $c +1;
				}
			}
		}
	}
    echo '</ul>';

    ?>
        <span id="here"></span>
		<p style="text-align: center"><a class="add-button" href="#"><?php echo __('Add More Buttons'); ?></a></p>
        <script>
            var $ =jQuery.noConflict();
                $(document).ready(function() {
                var count = <?php echo $c; ?>;
                $('.add-button').on('click', function(e) {
					e.preventDefault();
                    count = count + 1;
                   $('#comopopup_buttons').append('<? echo implode('',explode("\n",Print_popup_buttons('count'))); ?>'.replace(/count/g, count));
                    return false;
                });
                $('.comopopup-button-config .remove-button').on('click', function(r) {
					r.preventDefault();
					$(this).closest('.comopopup-button-config').remove();
					return false;
                });
				$('.comopopup-button-config .close-checkbox').change(function() {
					let $linkInfo = $(this).closest('.comopopup-button-config');
					if ($(this).is(':checked')) {
						$linkInfo.find('.button-link-url').val('');
						$linkInfo.find('.button-link-info').css('display', 'none');
					} else {
						$linkInfo.find('.button-link-info').css('display', 'block');
					}
				});
				$('.comopopup-automodal-radio').on('change', function() {
					if ($(this).val() == 'true') {
						$('#automodal-settings').css('display','block');
					} else {
						$('#automodal-settings').css('display','none');
					}
				});
				$('.comopopup-automodal-cookie-radio').on('change', function() {
					if ($(this).val() == 'true') {
						$('#automodal-cookie-settings').css('display','block');
					} else {
						$('#automodal-cookie-settings').css('display','none');
					}
				});
            });
        </script>
        <style>#comopopup_buttons {list-style: none;}</style>
    <?php
    echo '</div>';
}

//Save Popup Buttons
add_action('save_post', 'save_popup_buttons');

function save_popup_buttons($post_id){ 
	global $post;

    // to prevent metadata or custom fields from disappearing... 
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
    return $post_id; 
    // OK, we're authenticated: we need to find and save the data
    if (isset($_POST['comopopup_data'])) {
        $data = $_POST['comopopup_data'];
        update_post_meta($post_id,'comopopup_data',$data);
    } else {
        delete_post_meta($post_id,'comopopup_data');
    }
} 

// Get available Templates
function getComoPopupTemplates($comopopupTemp, $fieldName) {
	$comoDir = trailingslashit(plugin_dir_path( __FILE__ ) .'templates');
	$themeDir = trailingslashit(get_stylesheet_directory()) .'como-popup/';
	$comopopupTemplates = array();
	if ($dir = opendir($comoDir)) {
		while (false !== ($file = readdir($dir))) {
			if ($file != "." && $file != ".." && $file != "_notes") {
				// Get Template Name
				$filedata = get_file_data($comoDir .'/'. $file, array('name'=>'Template Name'));
				$comopopupTemplates[] = array('file'=>$file,'name'=>$filedata['name']);
			}
		}
		closedir($dir);
	}
	if (file_exists($themeDir)) {
		if ($dir = opendir($themeDir)) {
			while (false !== ($file = readdir($dir))) {
				if ($file != "." && $file != ".." && $file != "_notes") {
					// Get Template Name
					$filedata = get_file_data($themeDir .'/'. $file, array('name'=>'Template Name'));
					$comopopupTemplates[] = array('file'=>$file,'name'=>$filedata['name']);
				}
			}
			closedir($dir);
		}
	}
	$comopopupTemplates = array_map('unserialize', array_unique(array_map('serialize', $comopopupTemplates)));
	$selectOptions = array();
	foreach ($comopopupTemplates as $temp) {
		if (isset($temp['file'])) {
			$selectOptions[] = $temp;
		}
	}
	$defaltSel = (((empty($comopopupTemp)) || ($comopopupTemp == 'default')) ? 'selected="selected"' : '');
	?>
	<select name="<?=$fieldName?>" class="comopopup-template-select wide-fat" style="width: 100%">
		<?php
			foreach($selectOptions as $template) {
				?><option value="<?=$template['file'] .'" '. (($comopopupTemp == $template['file']) ? ' selected="selected"' : '')?>><?=$template['name']?></option><?php
			}
		?>
	</select>
	<?php
}

// Get Modal Sizes
function getModalSizes($comopopupSize, $fieldName) {
	$selectOptions = array();
	$modalSizes = array('Default'=>'default', 'Small'=>'modal-sm', 'Large'=>'modal-lg', 'Extra Large'=>'modal-xl', 'Full Screen'=>'modal-fullscreen', 'Full Screen Below SM'=>'modal-fullscreen-sm-down', 'Full Screen Below MD'=>'modal-fullscreen-md-down', 'Full Screen Below LG'=>'modal-fullscreen-lg-down', 'Full Screen Below XL'=>'modal-fullscreen-xl-down', 'Full Screen Below XXL'=>'modal-fullscreen-xxl-down');
	$defaltSel = (((empty($comopopupTemp)) || ($comopopupTemp == 'default')) ? 'selected="selected"' : '');
	?>
	<select name="<?=$fieldName?>" class="comopopup-template-select wide-fat" style="width: 100%">
		<?php
			foreach($modalSizes as $name=>$value) {
				?><option value="<?=$value?>" <?=(($comopopupSize == $value) ? ' selected="selected"' : '')?>><?=$name?></option><?php
			}
		?>
	</select>
	<?php
}

// Get Pages
function getModalPages($comopopupPages, $fieldName) {
	$selectOptions = array();
	$args = array('sort_order'=>'ASC', 'sort_column'=>'menu_order');
	$pages = get_pages($args);
	$defaltSel = (((empty($comopopupTemp)) || ($comopopupTemp == 'default')) ? 'selected="selected"' : '');
	$comopopupPages = ((!is_array($comopopupPages)) ? explode(',', $comopopupPages) : $comopopupPages);
	?>
	<select name="<?=$fieldName?>" class="comopopup-template-select wide-fat" style="width: 100%; height: 150px;" multiple="multiple">
		<option value="all" <?=((in_array('all',$comopopupPages)) ? ' selected="selected"' : '')?>>All Pages</option>
		<?php
			foreach($pages as $page) {
				$value = $page->ID;
				$name = $page->post_title;
				?><option value="<?=$value?>" <?=((in_array($value,$comopopupPages)) ? ' selected="selected"' : '')?>><?=$name?></option><?php
			}
		?>
	</select>
	<?php
}

// Popup Display Template
function comopopup_template_select( $post ) {
    wp_nonce_field( 'comopopup_template_select_submit', 'comopopup_template_select_nonce' );
    $comopopup_template_select_stored_meta = get_post_meta($post->ID); 
	global $post;
	$comopopup_template = ((isset($comopopup_template_select_stored_meta['comopopup-template'][0])) ? $comopopup_template_select_stored_meta['comopopup-template'][0] : '');
	$comopopupSize = ((isset($comopopup_template_select_stored_meta['comopopup-modal-size'][0])) ? $comopopup_template_select_stored_meta['comopopup-modal-size'][0] : 'default');
	$comopopupClass = ((isset($comopopup_template_select_stored_meta['comopopup-modal-class'][0])) ? $comopopup_template_select_stored_meta['comopopup-modal-class'][0] : '');
	$comopopupAutoModal = ((isset($comopopup_template_select_stored_meta['comopopup-automodal'][0])) ? $comopopup_template_select_stored_meta['comopopup-automodal'][0] : 'false');
	$comopopupPages = ((isset($comopopup_template_select_stored_meta['comopopup-modal-pages'][0])) ? $comopopup_template_select_stored_meta['comopopup-modal-pages'][0] : '');
	$comopopupAutoModalCookie = ((isset($comopopup_template_select_stored_meta['comopopup-automodal-cookie'][0])) ? $comopopup_template_select_stored_meta['comopopup-automodal-cookie'][0] : 'false');
	$comopopupAutoModalCookieExp = ((isset($comopopup_template_select_stored_meta['comopopup-automodal-cookie-exp'][0])) ? $comopopup_template_select_stored_meta['comopopup-automodal-cookie-exp'][0] : '1');
	
	?>
		<p><label for="comopopup-template" style="display: block; font-weight: bold;">Modal Template:</label><?=getComoPopupTemplates($comopopup_template, 'comopopup-template')?></p>
		<p><label for="comopopup-modal-size" style="display: block; font-weight: bold;">Modal Size:</label><?=getModalSizes($comopopupSize, 'comopopup-modal-size')?></p>
		<p><label for="comopopup-modal-class" style="display: block; font-weight: bold;">Modal Class:</label><input type="text" name="comopopup-modal-class" class="wide-fat" style="width: 100%;" value="<?=$comopopupClass?>" /></p>
		<p><label for="comopopup-automodal" style="display: block; font-weight: bold;">Auto Modal:</label><input type="radio" name="comopopup-automodal" class="comopopup-automodal-radio" value="false" <?=(($comopopupAutoModal=='false') ? 'checked' : '')?> /> False &nbsp; <input type="radio" name="comopopup-automodal" class="comopopup-automodal-radio" value="true" <?=(($comopopupAutoModal=='true') ? 'checked' : '')?> /> True</p>
		
		<div id="automodal-settings" style="display: <?=(($comopopupAutoModal == 'true') ? 'block' : 'none')?>;">
			<p><label for="comopopup-modal-pages" style="display: block; font-weight: bold;">Modal Pages:</label><?= getModalPages($comopopupPages, 'comopopup-modal-pages[]')?></p>
			
			<p><label for="comopopup-automodal-cookie" style="display: block; font-weight: bold;">Auto Modal Cookie:</label><input type="radio" name="comopopup-automodal-cookie" class="comopopup-automodal-cookie-radio" value="false" <?=(($comopopupAutoModalCookie=='false') ? 'checked' : '')?> /> False &nbsp; <input type="radio" name="comopopup-automodal-cookie" class="comopopup-automodal-cookie-radio" value="true" <?=(($comopopupAutoModalCookie=='true') ? 'checked' : '')?> /> True</p>
			
			<div id="automodal-cookie-settings" style="display: <?=(($comopopupAutoModalCookie == 'true') ? 'block' : 'none')?>;">
				<p><label for="comopopup-automodal-cookie-exp" style="display: block; font-weight: bold;">Auto Modal Cookie Expires:</label><input name="comopopup-automodal-cookie-exp" type="number" value="<?=$comopopupAutoModalCookieExp?>" min="1" step="1" /> Days</p>
			</div>
		</div>
		<input type="hidden" name="comoupdate_flag" value="true" />
	<?php    
}

// Add Custom Header background image metabox to the back end of Custom Header posts
function comopopup_template_select_metabox() {
	add_meta_box('comopopup-template-box', __('Modal Settings','comopopuptextdomain'),'comopopup_template_select','comopopup','side','default');
}
add_action( 'add_meta_boxes', 'comopopup_template_select_metabox', 1);

// Save background image metabox for Custom Header posts 
function comopopup_template_meta_save( $post_id ) {
	
	// Only do this if our custom flag is present
    if (isset($_POST['comoupdate_flag'])) {
	
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'comopopup_template_select_nonce' ] ) && wp_verify_nonce( $_POST[ 'comopopup_template_select_nonce' ], 'comopopup_template_select_submit' ) ) ? 'true' : 'false';
		
		// Exits script depending on save status
		if ($is_autosave || $is_revision || !$is_valid_nonce) {
			return;
		}
		
		// Specify Meta Variables to be Updated
		$metaVars = array('comopopup-template','comopopup-modal-size','comopopup-modal-class','comopopup-automodal','comopopup-modal-pages','comopopup-automodal-cookie','comopopup-automodal-cookie-exp');
		$arrayVars = array('comopopup-modal-pages');
		$checkboxVars = array();
		$externalVars = array();
		$ignoreVars = array();
		
		if ($_POST['comopopup-automodal'] == 'true') {
			update_option('autoPop-enabled', 'true');
		} else {
			update_option('autoPop-enabled', 'false');
		}
		
		// Update Meta Variables
		foreach ($metaVars as $var) {
			if (!in_array($var,$ignoreVars)) {
				if (in_array($var,$checkboxVars)) {
					if (isset($_POST[$var])) {
						update_post_meta($post_id, $var, 'yes');
					} else {
						update_post_meta($post_id, $var, '');
					}
				} else {
					$value = ((in_array($var,$arrayVars)) ? implode(',', $_POST[$var]) : $_POST[$var]);
					if(isset($_POST[$var])) {
						update_post_meta($post_id, $var, $value);
					} else {
						update_post_meta($post_id, $var, '');
					}
				}
			}
		}
	}
}
add_action( 'save_post', 'comopopup_template_meta_save' );

function getComopopupButtons($comopopup_array) {
	$modalButtons = array(); 
	if (isset($comopopup_array['buttons'])) {
		if (!empty($comopopup_array['buttons'])) {
			$btnCount = count($comopopup_array['buttons']);
			for ($b=0;$b<$btnCount;$b++) {
				$button = $comopopup_array['buttons'][$b];
				$modalButtons[] = '<a class="btn'. (($button['button-close-link'] == 'true') ? ' automodal-close " data-bs-dismiss="modal" aria-label="Close"' : '" href="'. $button['button-link'] .'"') .'>'. $button['button-text'] .'</a>'; 
			}
		}
	}
	return $modalButtons;
}

/* ##################### Shortcode to Show Team Members ##################### */
// Usage: [comopopup popupid=ID template=TEMPLATE NAME autopop=TRUE/FALSE setcookie=TRUE/FALSE cookieexp=1 buttontext='' buttonclass='']
class Como_Popup_Shortcode {
	static $add_script;
	static $add_style;
	static function init() {
		add_shortcode('comopopup', array(__CLASS__, 'handle_shortcode'));
		//add_action('init', array(__CLASS__, 'register_script'));
		//add_action('wp_footer', array(__CLASS__, 'print_script'));
	}
	
	static function handle_shortcode($atts) {
		self::$add_style = true;
		self::$add_script = true;
		
		$popup_template = (isset($atts['template']) ? $atts['template'] : '');
		$popupID = (isset($atts['popupid']) ? $atts['popupid'] : '');
		$autopop = strtolower((isset($atts['autopop']) ? $atts['autopop'] : ''));
		$setcookie = strtolower((isset($atts['setcookie']) ? $atts['setcookie'] : ''));
		$cookieexp = (isset($atts['cookieexp']) ? $atts['cookieexp'] : '');
		$popupButtonText = (isset($atts['buttontext']) ? $atts['buttontext'] : 'Click Here');
		$popupButtonClass = (isset($atts['buttonclass']) ? $atts['buttonclass'] : '');
		
		if ($popupID) {
			$args = array('p'=>$popupID,'post_type'=>'comopopup','post_status'=>'publish','posts_per_page'=>1);
			$query = new WP_Query( $args );
			if ($query->have_posts()) { 
				unset($comopopup_array);
				while ($query->have_posts()) {
					$query->the_post(); 
					unset($pu);
					$pu['id'] = $popupID;
					$popupMeta = get_post_meta($pu['id']);
					$popupButtons = get_post_meta($pu['id'],'comopopup_data',true);
					$pu['title'] = get_the_title();
					$pu['content'] = get_the_content();
					$pu['template'] = (($popup_template) ? $popup_template : (($popupMeta['comopopup-template'][0]) ? $popupMeta['comopopup-template'][0] : 'default.php'));
					$pu['buttons'] = $popupButtons; 
					$pu['modal-size'] = (($popupMeta['comopopup-modal-size'][0]) ? $popupMeta['comopopup-modal-size'][0] : 'default');
					$pu['modal-class'] = (($popupMeta['comopopup-modal-class'][0]) ? $popupMeta['comopopup-modal-class'][0] : '');
					$pu['autopop'] = (($autopop) ? $autopop : (($popupMeta['comopopup-automodal'][0]) ? $popupMeta['comopopup-automodal'][0] : 'false'));
					$pu['set-cookie'] = (($setcookie) ? $setcookie : (($popupMeta['comopopup-automodal-cookie'][0]) ? $popupMeta['comopopup-automodal-cookie'][0] : 'false'));
					$pu['cookie-exp'] = (($cookieexp) ? $cookieexp : (($popupMeta['comopopup-automodal-cookie-exp'][0]) ? $popupMeta['comopopup-automodal-cookie-exp'][0] : '1'));
					$pu['button-text'] = $popupButtonText;
					$pu['button-class'] = $popupButtonClass;
					$comopopup_array = $pu;
				}
				if ($pu['template']) {
					$temp = (is_child_theme() ? get_stylesheet_directory() : get_template_directory() ) . '/como-popup/'. $pu['template'] .'.php';
					if (file_exists($temp)) {
						include($temp);
					} else {
						include(plugin_dir_path( __FILE__ ) .'templates/default.php');
					}
				} else {
					include(plugin_dir_path( __FILE__ ) .'templates/default.php');
				}
			}
		}
		if (!is_admin()) {
			if ($comopopupModal) { 
				$GLOBALS['page-modals'] .= $comopopupModal;
				if ($comopopup_array['autopop'] == 'true') {
					$cookieScript = ''; 
					if ($comopopup_array['set-cookie'] === 'true') {
						$cookieExp =  $comopopup_array['cookie-exp'];
						$cookieScript = "$('.automodal-close').click(function(){
							$.cookie('autoModalClosed', 1, { expires : $cookieExp });
						});
						if (!($.cookie('autoModalClosed'))) {
							autoModal(autoModalID);
						}";
					} else {
						$cookieScript = "autoModal(autoModalID);";
					}
					$GLOBALS['footScript'] .= "<script>
						jQuery(document).ready(function($) {
							'use strict'; 
							if (('.comopopup-autopop').length > 0) {
								var autoModalID = '$comomodalID';
								$cookieScript							
							} 
						});
					</script>";
				} else {
					return $comopopupButton;	
				}
			}
		}
	}
	
	// Register & Print Scripts
	static function register_script() {
		//wp_register_script('comopopup_automodal', plugins_url('js/comopopup-automodal.js', __FILE__), array('jquery'), '1.0', true);
	}
	static function print_script() {
		if ( ! self::$add_script )
			return;
		//wp_print_scripts('comopopup_automodal',999);
	}
}
Como_Popup_Shortcode::init();

if (!is_admin()) { add_action('wp_footer', 'comopopup_php_to_footer', 1); }
function comopopup_php_to_footer() {
	$autoPopDefined = get_option('autoPop-enabled');
	if ($autoPopDefined !== 'false') {
		include_once(trailingslashit(plugin_dir_path( __FILE__ ) .'includes'). 'autoPop.php');
	}
};