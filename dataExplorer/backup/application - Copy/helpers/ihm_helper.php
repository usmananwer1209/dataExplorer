<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('active_plugin')){
	function active_plugin($plugin,$page){

		$form_plugin = array("validation", "select");
                if($plugin == "isotope" && ( startsWith($page,'/profile/all') || ( startsWith($page,'/circle/all') )) )
			return false;
		if( in_array($plugin, $form_plugin) && (contains($page, '/edit') || contains($page, '/add')) ){
			return true;
			}
		if($plugin == "jasny" && ( startsWith($page,'/profile/add') ||  startsWith($page,'/profile/edit') ))
			return true;
		if($plugin == "formhelpers" && ( startsWith($page,'/profile/') ))
			return true;
		if($plugin == "mixitup" && ( startsWith($page,'/profile/all') || ( startsWith($page,'/circle/all') ) || ( startsWith($page,'/company/all') ) || ( startsWith($page,'/template/all') )|| ( startsWith($page,'/dataset/all') )) )
			return true;

		if($plugin == "isotope" && ( startsWith($page,'/card') || startsWith($page,'/storyboard') ||  startsWith($page,'/card/all') || startsWith($page,'/profile/all') || ( startsWith($page,'/circle/all'))) )
			return true;

		if($plugin == "rcarousel" &&  startsWith($page,'/circle/view'))
			return true;


		return false;
		}
	}
if (!function_exists('active_js')){
	function active_js($js,$page){
		if($js == "profile" && ( startsWith($page,'/profile/add') ||  startsWith($page,'/profile/edit') ))
			return true;
		if($js == "card" && ( startsWith($page,'/card/view') ||  startsWith($page,'/card/add') ||  startsWith($page,'/card/edit') ))
			return true;
		if($js == "template" && ( startsWith($page,'/template/all')))
			return true;
		if($js == "dataset" && ( startsWith($page,'/dataset/add') || startsWith($page,'/dataset/edit')))
			return true;
		if($js == "storyboard" && ( startsWith($page,'/storyboard/add') ||  startsWith($page,'/storyboard/edit')) )
			return true;
		if($js == "storyboard_view" && ( startsWith($page,'/storyboard/view') ) )
			return true;
		if($js == "home" && ( startsWith($page,'/home') ) )
			return true;
		if($js == "my_storyboard" && ( startsWith($page,'/storyboard/my_storyboards')) )
			return true;
		if($js == "my_card" && ( startsWith($page,'/card/my_cards')) )
			return true;
		if($js == "all_storyboard" && startsWith($page,'/storyboard/all'))
			return true;
		if($js == "all_card" && startsWith($page,'/card/all'))
			return true;
		if($js == "private_company" && (startsWith($page,'/company/add') || startsWith($page,'/company/edit')))
			return true;
		return false;
		}
	}	



if (!function_exists('startsWith')){
	function startsWith($haystack, $needle){
	    return $needle === "" || strpos($haystack, $needle) === 0;
		}
	}

if (!function_exists('endsWith')){
	function endsWith($haystack, $needle){
	    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
		}
	}
if (!function_exists('contains')){
	function contains($s, $w){	
		if (strpos($s,$w) !== false) 
		    return  true;
		return false;
	}
}