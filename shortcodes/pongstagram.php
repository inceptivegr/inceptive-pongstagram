<?php
/*------------------------------------------------------------------------
# Inceptive Pongstagram Content Plugin
# ------------------------------------------------------------------------
# author    Inceptive Design Labs
# copyright Copyright (C) 2013 Inceptive Design Labs. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   http://extend.inceptive.gr
-------------------------------------------------------------------------*/

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

jimport('joomla.registry.registry');

//[pongstagram]
if(!function_exists('pongstagram_sc')){
    function pongstagram_sc($atts, $content=''){
        
        $document = JFactory::getDocument();
        
        $path = strstr(realpath(dirname(__FILE__)), 'plugins');
        $path = str_replace("plugins", "", $path);
        $path = str_replace("shortcodes", "", $path);
        $path = JURI::root(true).'/plugins'.$path;

        $document->addStyleSheet($path.'css/pongstagr.am.css');
        $document->addStyleSheet($path.'css/bootstrap.min.css');
        $document->addStyleSheet($path.'css/bootstrap-responsive.min.css');

        $document->addScript($path.'js/bootstrap.min.js');
        $document->addScript($path.'js/pongstagr.am.js');
	extract(shortcode_atts(array(), $atts));
	
    $plugin = JPluginHelper::getPlugin('content', 'incptvpongstagram');    
    $pluginParams = new JRegistry();
    $pluginParams->loadString($plugin->params, 'JSON');
    
    $userid = (isset($atts['userid']) && $atts['userid'] != '' ? $atts['userid'] : $pluginParams->get('userid', '679256982'));
    $access_token = (isset($atts['access_token']) && $atts['access_token'] != '' ? $atts['access_token'] : $pluginParams->get('access_token', '679256982.401c5ac.0a183542db5f4ae1b51caae21acadc1e'));
    $show = (isset($atts['show']) && $atts['show'] != '' ? $atts['show'] : $pluginParams->get('show', 'recent'));
    
    $divid = $show.'_'.rand(0, 9999);
    //show is 'tags'
    $show = ($show == 'tags' ? $pluginParams->get('tags', 'i_promote_greece') : $show);
    
    $count = (isset($atts['count']) && $atts['count'] != '' ? $atts['count'] : $pluginParams->get('count', 12));
    $pager = (isset($atts['pager']) && $atts['pager'] != '' ? $atts['pager'] : $pluginParams->get('pager', true));
    
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

