<?php
    /**
    * @package Helix Framework
    * @author JoomShaper http://www.joomshaper.com
    * @copyright Copyright (c) 2010 - 2013 JoomShaper
    * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
    */
    //no direct accees
    defined ('_JEXEC') or die('resticted aceess');
	
	class Helix {

		private static $_instance;
		private $document;
		private $importedFiles=array();
		private $_less;

		//initialize 
		public function __construct(){

		}

		final public static function getInstance()
		{
			if( !self::$_instance ){
				self::$_instance = new self();
				self::getInstance()->getDocument();
				self::getInstance()->getDocument()->helix = self::getInstance();
			} 
			return self::$_instance;
		}
		
		public static function loadHelixOverwrite(){

			if (!JFactory::getApplication()->isAdmin()) {

				if( JVERSION >= 3 ){
					// override core joomla 3 class
					if (!class_exists('JViewLegacy', false))  self::getInstance()->Import('core/classes/joomla30/viewlegacy.php');
					if (!class_exists('JModuleHelper', false)) self::getInstance()->Import('core/classes/joomla25/helper.php'); 

				} else {
					// override core joomla 2.5 class
					if (!class_exists('JHtmlBehavior', false)) self::getInstance()->Import('core/classes/joomla25/behavior.php'); 
					if (!class_exists('JViewLegacy', false)) self::getInstance()->Import('core/classes/joomla25/view.php'); 
					if (!class_exists('JDocumentRendererMessage', false)) self::getInstance()->Import('core/classes/joomla25/message.php'); 
					if (!class_exists('JModuleHelper', false)) self::getInstance()->Import('core/classes/joomla25/helper.php');  
				}
				if (!class_exists('JHtmlBootstrap', false)) Helix::Import('core/classes/joomla30/bootstrap.php'); 
			} 

		}
		
		public static function getDocument($key=false)
		{
			self::getInstance()->document = JFactory::getDocument();
			$doc = self::getInstance()->document;
			if( is_string($key) ) return $doc->$key;

			return $doc;
		}

		public static function frameworkPath($base=false)
		{
			$path = strstr(realpath(dirname(__FILE__)), 'plugins');
			$path = str_replace("plugins", "", $path);
			$path = str_replace("core", "", $path);
			
			if( $base==true ) return JURI::root(true).'/plugins/'.$path;

			return JPATH_PLUGINS . $path;
		}
		
		public static function pluginPath($base=false){
			return self::getInstance()->frameworkPath($base);
		}
		
		public static function themePath($base=false)
		{
			if( $base==true ) return JURI::root(true).'/templates/'.self::getInstance()->themeName();

			return  JPATH_THEMES . '/' . self::getInstance()->themeName();
		}
		
		public static function themeName()
		{
			//return self::getInstance()->getDocument()->template;
			return JFactory::getApplication()->getTemplate();
		}
		
		public static function importShortCodeFiles()
		{
			$shortcodes = array();
			
			$pluginshortcodes = glob( self::getInstance()->pluginPath().'/shortcodes/*.php');

			foreach((array) $pluginshortcodes as $value)  $shortcodes[] =   basename($value);

			$shortcodes = array_unique($shortcodes);

			self::getInstance()->Import('core/wp_shortcodes.php', self::getInstance());

			foreach($shortcodes as $shortcode  ) self::getInstance()->Import('shortcodes/'.$shortcode);

			return self::getInstance();
		}
		
		public static function Import($paths, $helix=false)
		{
			if( is_array($paths) ) foreach((array) $paths as $file) self::_Import( $file );
			else self::_Import( $paths, $helix );
			return self::getInstance();
		}
		
		private static function _Import($path, $helix)
		{
			$inplugin = self::getInstance()->frameworkPath() . '/' . $path;

			if( file_exists( $inplugin ) && !is_dir( $inplugin ) ){
				self::getInstance()->importedFiles[] = $inplugin; 
				require_once $inplugin;
			}
			return self::getInstance();
		}
		
		public function isExternalURL($url)
		{
			$parseurl = parse_url($url);
			$urlHost = isset($parseurl['host'])?$parseurl['host']:false;
			$currentHost = $_SERVER['HTTP_HOST'];
			$currentRemoteAddr = $_SERVER['REMOTE_ADDR'];

			if(false==$urlHost) return false;

			if( $currentHost===$urlHost or $currentRemoteAddr===$urlHost ) return false;
			else return true;
		} 
		
		public static function addJS($sources, $seperator=',', $checkpath=true) {

			$srcs = array();
			if( is_string($sources) ) $sources = explode($seperator,$sources);
			if(!is_array($sources)) $sources = array($sources);

			foreach( (array) $sources as $source ) $srcs[] = trim($source);

			foreach ($srcs as $src) {

				if(self::getInstance()->isExternalURL($src)) self::getInstance()->document->addScript($src);

				if( $checkpath==false ){
					self::getInstance()->document->addScript($src);
					continue; 
				} 

				//cheack in template path
				if( file_exists( self::getInstance()->themePath() . '/js/'. $src)) { 
					self::getInstance()->document->addScript( self::getInstance()->themePath(true) . '/js/' . $src );
				} 
				//if not found, then check from helix path
				elseif( file_exists( self::getInstance()->frameworkPath() . '/js/' . $src ) ) { 
					self::getInstance()->document->addScript( self::getInstance()->frameworkPath(true) . '/js/' . $src);
				}        
			}
			return self::getInstance();
		}
		
		public static function addCSS($sources, $seperator=',',$checkpath=true) {

			$srcs = array();
			if( is_string($sources) ) $sources = explode($seperator,$sources);
			if(!is_array($sources)) $sources = array($sources);

			foreach( (array) $sources as $source ) $srcs[] = trim($source);

			foreach ($srcs as $src) {

				if(self::getInstance()->isExternalURL($src)) self::getInstance()->document->addStyleSheet($src);

				if( $checkpath==false ){
					self::getInstance()->document->addStyleSheet($src);
					continue; 
				} 

				//cheack in template path
				if( file_exists( self::getInstance()->themePath() . '/css/'. $src)) { 
					self::getInstance()->document->addStyleSheet( self::getInstance()->themePath(true) . '/css/' . $src );
				} 
				//if not found, then check from helix path
				elseif( file_exists( self::getInstance()->frameworkPath() . '/css/' . $src ) ) { 
					self::getInstance()->document->addStyleSheet( self::getInstance()->frameworkPath(true) . '/css/' . $src);
				}        
			}
			return self::getInstance();
		}  

	}
?>