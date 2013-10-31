<?php
/*------------------------------------------------------------------------
# shortcodes/pongstagram.php - Inceptive Pongstagram Content Plugin
# ------------------------------------------------------------------------
# version   1.0
# author    Inceptive Design Labs
# copyright Copyright (C) 2013 Inceptive Design Labs. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   http://extend.inceptive.gr
-------------------------------------------------------------------------*/

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

Helix::addJS(array('bootstrap.min.js', 'pongstagr.am.js'));
Helix::addCSS(array('pongstagr.am.css', 'bootstrap.min.css', 'bootstrap-responsive.min.css'));

jimport('joomla.registry.registry');

//[pongstagram]
if(!function_exists('pongstagram_sc')){
    function pongstagram_sc($atts, $content=''){
	extract(shortcode_atts(array(), $atts));
	
    $plugin = JPluginHelper::getPlugin('content', 'incptvpongstagram');    
    $pluginParams = new JRegistry();
    $pluginParams->loadString($plugin->params, 'JSON');
    
    $userid = ($atts['userid'] != '' ? $atts['userid'] : $pluginParams->get('userid', '39666111'));
    $access_token = ($atts['access_token'] != '' ? $atts['access_token'] : $pluginParams->get('access_token', '39666111.1fb234f.c3901000b4944a549fd5fd2310c63780'));
    $show = ($atts['show'] != '' ? $atts['show'] : $pluginParams->get('show', 'recent'));
    
    $divid = $show.'_'.rand(0, 9999);
    //show is 'tags'
    $show = ($show == 'tags' ? $pluginParams->get('tags', 'i_promote_greece') : $show);
    
    $count = ($atts['count'] != '' ? $atts['count'] : $pluginParams->get('count', 12));
    $pager = ($atts['pager'] != '' ? $atts['pager'] : $pluginParams->get('pager', true));
    
    $data = '<div id="pongstagram_'.$divid.'" class="well well-large"></div>';
    
    $data .= '	<script>
		    var $incptv = jQuery.noConflict();
		    $incptv(document).ready(function(){

			$incptv(\'div#pongstagram_'.$divid.'\').pongstgrm({
			    accessId    : \''.$userid.'\',
			    accessToken : \''.$access_token.'\',
			    show : \''.$show.'\',
			    count: '.$count.', 
			    pager: '.$pager.'
			});

		    });  
	      </script>';

     return $data;
    }
    add_shortcode('pongstagram','pongstagram_sc');
}

