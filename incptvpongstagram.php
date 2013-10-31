<?php
/*------------------------------------------------------------------------
# incptvpongstagram.php - Inceptive Pongstagram Content Plugin
# ------------------------------------------------------------------------
# author    Inceptive Design Labs
# copyright Copyright (C) 2013 Inceptive Design Labs. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   http://extend.inceptive.gr
-------------------------------------------------------------------------*/


defined('_JEXEC') or die;

class plgContentIncptvpongstagram extends JPlugin {

    var $plg_copyrights_start		= "\n\n<!-- Inceptive \"Pongstagram\" Content  Plugin (v1.0) starts here -->\n";
    var $plg_copyrights_end		= "\n<!-- Inceptive \"Pongstagram\" Content  Plugin (v1.0) ends here -->\n\n";

    function plgContentIncptvpongstagram( &$subject )
    {
	parent::__construct( $subject );
    }
  
    public function onContentPrepare($context, &$article, &$params, $limitstart)
    {
		if(  !JFactory::getApplication()->isAdmin() ){

			$document = JFactory::getDocument();
			$type = $document->getType();

			if($type=='html') {

				$oldhead = $document->getHeadData();  // old head

				$data =  $article->text;
				
				$path = strstr(realpath(dirname(__FILE__)), 'plugins');
				$path = str_replace("plugins", "", $path);
				$path = JPATH_PLUGINS . $path;
				$this->importShortCodeFiles($path);

				$data = shortcode_unautop($data);
				$data = do_shortcode($data); 
				$newhead = $document->getHeadData();  // new head
				$scripts =  (array)  array_diff_key($newhead['scripts'], $oldhead['scripts']);
				$styles  =  (array) array_diff_key($newhead['styleSheets'], $oldhead['styleSheets']);

				$new_head_data = '';

				foreach ($scripts as $key => $type)
					$new_head_data .= '<script type="' . $type['mime'] . '" src="' . $key . '"></script>';

				foreach ($styles as $key => $type)
					$new_head_data .=  '<link rel="stylesheet" href="' . $key . '" />';

				$data = str_replace('</head>', $new_head_data . "\n</head>", $data);

				$article->text = $data;
			}
		}
    }
	
	public function importShortCodeFiles($path)
		{
			
			$shortcodes = array();
			
			$pluginshortcodes = glob( $path.'/shortcodes/*.php');

			foreach((array) $pluginshortcodes as $value)  $shortcodes[] =   basename($value);

			$shortcodes = array_unique($shortcodes);

			require_once('core/wp_shortcodes.php');

			foreach($shortcodes as $shortcode  )
			{
				require_once('shortcodes/'.$shortcode);
			}
		}
}

?>