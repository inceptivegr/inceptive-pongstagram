<?php
/*------------------------------------------------------------------------
# Inceptive Pongstagram Content Plugin
# ------------------------------------------------------------------------
# author    Inceptive Design Labs
# copyright Copyright (C) 2013 Inceptive Design Labs. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   http://extend.inceptive.gr
-------------------------------------------------------------------------*/

// No Direct Access
defined( '_JEXEC' ) or die;

// Script
class plgContentIncptvPongstagramInstallerScript
{
	/**
	 * Called on installation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function install( $parent )
	{
	}
	
	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 */
	function uninstall( $parent )
	{
		$db = JFactory::getDBO();
        $status = new stdClass;
        $status->plugins = array();
        $manifest = $parent->getParent()->manifest;
        $plugins = $manifest->xpath('plugins/plugin');
        foreach ($plugins as $plugin)
        {
            $name = (string)$plugin->attributes()->plugin;
            $group = (string)$plugin->attributes()->group;
            $query = "SELECT `extension_id` FROM #__extensions WHERE `type`='plugin' AND element = ".$db->Quote($name)." AND folder = ".$db->Quote($group);
            $db->setQuery($query);
            $extensions = $db->loadColumn();
            if (count($extensions))
            {
                foreach ($extensions as $id)
                {
                    $installer = new JInstaller;
                    $result = $installer->uninstall('plugin', $id);
                }
                $status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
            }
            
        }
        $this->uninstallationResults($status);

	}
	
	/**
	 * Called on update
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function update( $parent )
	{		
	}
	
	/**
	 * Called before any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function preflight( $type, $parent )
	{
	}
	
	/**
	 * Called after any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function postflight( $type, $parent )
	{
	    $app	=	JFactory::getApplication();
	    $db		=	JFactory::getDBO();

	    $db->setQuery( 'UPDATE #__extensions SET enabled = 1 WHERE folder="content" AND element = "incptvpongstagram"' );
	    $db->execute();

	    $status = new stdClass;
	    $status->plugins = array();
	    $src = $parent->getParent()->getPath('source');
	    $manifest = $parent->getParent()->manifest;
	    $plugins = $manifest->xpath('plugins/plugin');
	    foreach ($plugins as $plugin)
	    {
			$name = (string)$plugin->attributes()->plugin;
			$group = (string)$plugin->attributes()->group;
			$path = $src.'/plugins/'.$group;
			if (JFolder::exists($src.'/plugins/'.$group.'/'.$name))
			{
				$path = $src.'/plugins/'.$group.'/'.$name;
			}
			$installer = new JInstaller;
			$result = $installer->install($path);
			
			$query = "UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND element=".$db->Quote($name)." AND folder=".$db->Quote($group);
			$db->setQuery($query);
			$db->query();
			$status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
	    }
	    //echo "<p>Installed</p>";
	    $this->installationResults($status);
	}
	
	private function installationResults($status)
	{
	    $language = JFactory::getLanguage();
	    $languagePath = JPATH_PLUGINS.DS.'content'.DS.'incptvpongstagram';
	    $rows = 0; ?>
	    <img src="<?php echo JURI::root(true); ?>/plugins/content/incptvpongstagram/img/incptvpongstagram173x48.jpg" alt="Inceptive Pongstagram" align="right" />
	    <h2>Installation status</h2>
		<!-- <div class="desc-container">
		    <p>Thank you for installing <a href="http://extend.inceptive.gr/shop/joomla-extensions/inceptive-pongstagram/" title="Inceptive Pongstagram">Inceptive Pongstagram</a>.</p>
		    <p><a href="http://extend.inceptive.gr/shop/joomla-extensions/inceptive-pongstagram/" title="Inceptive Pongstagram">Inceptive Pongstagram</a> is a beautiful and easy way to display Instagram &copy; images in your Joomla! site.</p>
		    <p>The plugin displays the images using a shortcode. You can use the pongstagram button in yout editor to see the formating. The plugin also does major utilization of the <a href="https://github.com/pongstr/pongstagr.am" title="pongstagr.am">pongstagr.am jQuery Plugin</a>. Pongstagr.am is a jQuery plugin that lets display your instagram media to your website using Bootstrap Front-end styles and modal-plugin.</p>
		    <p>To use this plugin you need an Instagram User ID. If you have zero idea what your user id is, you may head to this <a href="http://jelled.com/instagram/lookup-user-id">link.</a>.</p>
		    <p>You also need an Access Token. To get your access token, you may head to this <a href="http://jelled.com/instagram/access-token">link.</a>, make sure you follow the instructions on the "How do I get my client id?" link.</p>
		</div> -->
	    <table class="adminlist table table-striped">
		<thead>
		    <tr>
			<th class="title" colspan="2">Extension</th>
			<th width="30%">Status</th>
		    </tr>
		</thead>
		<tfoot>
		    <tr>
			<td colspan="3"></td>
		    </tr>
		</tfoot>
		<tbody>
				    <tr>
			<th>Plugin</th>
			<th>Group</th>
			<th></th>
		    </tr>
				    <tr class="row0">
			<td class="key">Inceptive Pongstagram</td>
					    <td class="key">Content</td>
			<td><strong>Installed</strong></td>
		    </tr>
		    <?php if (count($status->plugins)): ?>
		    <?php foreach ($status->plugins as $plugin): ?>
		    <tr class="row<?php echo(++$rows % 2); ?>">
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><?php echo ($plugin['result'])?"Installed":"Not Installed"; ?></strong></td>
		    </tr>
		    <?php endforeach; ?>
		    <?php endif; ?>
		</tbody>
	    </table>
	<?php
	    }
	
	private function uninstallationResults($status)
	{
	    $rows = 0;
	    ?>
		<h2>Removal status</h2>
		<div class="desc-container">
		    <p>Thank you for installing <a href="#" title="Inceptive Pongstagram">Inceptive Pongstagram</a>.</p>
		    <p><a href="#" title="Inceptive Pongstagram">Inceptive Pongstagram</a> is a beautiful and easy way to display Instagram &copy; images in your Joomla! site.</p>
		    <p>The plugin displays the images using a shortcode. You can use the pongstagram button in yout editor to see the formating. The plugin also does major utilization of the <a href="https://github.com/pongstr/pongstagr.am" title="pongstagr.am">pongstagr.am jQuery Plugin</a>. Pongstagr.am is a jQuery plugin that lets display your instagram media to your website using Bootstrap Front-end styles and modal-plugin.</p>
		    <p>To use this plugin you need an Instagram User ID. If you have zero idea what your user id is, you may head to this <a href="http://jelled.com/instagram/lookup-user-id">link.</a>.</p>
		    <p>You also need an Access Token. To get your access token, you may head to this <a href="http://jelled.com/instagram/access-token">link.</a>, make sure you follow the instructions on the "How do I get my client id?" link.</p>
		</div>
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th class="title" colspan="2">Extension</th>
					<th width="30%">Status</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3"></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<th>Plugin</th>
					<th>Group</th>
					<th></th>
				</tr>
				<tr class="row0">
					<td class="key">Inceptive Pongstagram</td>
					<td class="key">Content</td>
					<td><strong>Removed</strong></td>
				</tr>
				<?php if (count($status->plugins)): ?>
				<?php foreach ($status->plugins as $plugin): ?>
				<tr class="row<?php echo(++$rows % 2); ?>">
					<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
					<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
					<td><strong><?php echo ($plugin['result'])?"Removed":"Not removed"; ?></strong></td>
				</tr>
				<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	<?php
	}	
}
?>