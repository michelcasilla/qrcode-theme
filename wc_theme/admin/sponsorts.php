<?php

require_once(TEMPLATEPATH."/admin/models/VTW_Event_Sponsors_CRUD.php");

function getSponsorsOptionGroup($selected = ''){
	$translation = __('--Seleccione--', 'sp');
	$sponsorDropDown = "<option value=''>{$translation}</option>";
	$sponsors = VTW_Event_Sponsors_CRUD::get_active_sponsors();
	$grouped = [];

	foreach ($sponsors as $sponsor) {
		$grouped[$sponsor->level][] = $sponsor;
	}

	foreach($grouped as $key => $sponsorGroup){
		$isSelected = '';
		$sponsorDropDown .= "<optgroup label='{$key}'>";
			foreach($sponsorGroup as $sponsor){
				 if($sponsor->name == $selected){
					$isSelected = 'selected="selected"';
				 }
				$sponsorDropDown .= "<option value='{$sponsor->name}' {$isSelected}>{$sponsor->name}</option>";
			}
		$sponsorDropDown .= "</optgroup>";
	}
	return $sponsorDropDown;
}