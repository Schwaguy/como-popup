<?php
	$pageID = get_queried_object_id();
	$args = array('post_type'=>'comopopup','post_status'=>'publish','meta_query'=> [['key'=>'comopopup-automodal','value'=>'true']],'posts_per_page'=>1);
	$query = new WP_Query($args);
	if ($query->have_posts()) { 
		unset($comopopup_array);
		while ($query->have_posts()) {
			$query->the_post(); 
			unset($pu);
			$pu['id'] = get_the_id();
			$popupMeta = get_post_meta($pu['id']);
			$popupButtons = get_post_meta($pu['id'],'comopopup_data',true);
			$pu['title'] = get_the_title();
			$pu['content'] = get_the_content();
			$pu['template'] = (($popup_template) ? $popup_template : (($popupMeta['comopopup-template'][0]) ? $popupMeta['comopopup-template'][0] : 'default.php'));
			$pu['buttons'] = $popupButtons; 
			$pu['modal-size'] = (($popupMeta['comopopup-modal-size'][0]) ? $popupMeta['comopopup-modal-size'][0] : 'default');
			$pu['modal-class'] = (($popupMeta['comopopup-modal-class'][0]) ? $popupMeta['comopopup-modal-class'][0] : '');
			$pu['button-text'] = $popupButtonText;
			$pu['button-class'] = $popupButtonClass;
			$pu['autopop'] = (($popupMeta['comopopup-automodal'][0]) ? $popupMeta['comopopup-automodal'][0] : 'false');
			$pu['set-cookie'] = (($popupMeta['comopopup-automodal-cookie'][0]) ? $popupMeta['comopopup-automodal-cookie'][0] : 'false');
			$pu['cookie-exp'] = (($popupMeta['comopopup-automodal-cookie-exp'][0]) ? $popupMeta['comopopup-automodal-cookie-exp'][0] : '1');
			$pu['show-on-pages'] = (($popupMeta['comopopup-modal-pages'][0]) ? $popupMeta['comopopup-modal-pages'][0] : 'all');
			$pu['show-on-pages'] = (($pu['show-on-pages'] == 'all') ? $pu['show-on-pages'] : explode(',', $pu['show-on-pages']));
			$comopopup_array = $pu;
		}
		$showPopup = false;
		if (is_array($comopopup_array['show-on-pages'])) {
			$showPopup = ((in_array($pageID, $comopopup_array['show-on-pages'])) ? true : false);	
		} elseif ($comopopup_array['show-on-pages'] === 'all')  {
			$showPopup = true;
		}
		if ($showPopup) {
			if ($comopopup_array['template']) {
				$temp = (is_child_theme() ? get_stylesheet_directory() : get_template_directory() ) . '/como-popup/'. $comopopup_array['template'];
				if (file_exists($temp)) {
					include_once($temp);
				} else {
					include_once(plugin_dir_path( __DIR__ ) .'templates/default.php');
				}
			} else {
				include_once(plugin_dir_path( __DIR__ ) .'templates/default.php');
			}
			if ($comopopupModal) { 
				echo $comopopupModal;
				if ($comopopup_array['autopop'] === 'true') {
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
				}
			} 
		}
	}