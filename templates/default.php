<?php
/*
Template Name: Default Modal Template
*/
$comomodalID = 'comopopupModal-'. $comopopup_array['id'];
$comopopupButton = '<a class="btn btn-primary '. $comopopup_array['button-class'] .'" data-bs-toggle="modal" data-bs-target="#'. $comomodalID .'">'. $comopopup_array['button-text'] .'</a>';

$modalButtons = getComopopupButtons($comopopup_array);
$modalBtnDisplay = ''; 
if (count($modalButtons) > 0) {
	foreach ($modalButtons as $btn) {
		$modalBtnDisplay .= $btn; 
	}
}
$comopopupModal = '<div id="'. $comomodalID .'" class="modal comopopup-modal fade '. $comopopup_array['modal-class'] .' '. (($comopopup_array['autopop']==='true') ? 'comopopup-autopop' : '') .'" role="dialog">
	<div class="modal-dialog '. (($comopopup_array['modal-size'] !== 'default') ? $comopopup_array['modal-size'] : '') .' modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">'. $comopopup_array['title'] .'</h5>
				<button type="button" class="close btn-close automodal-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">
					'. apply_filters('the_content', $comopopup_array['content']) .'
					'. $modalBtnDisplay .'
				</div>
			</div><!-- /.modal-body -->
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary automodal-close" data-bs-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->';