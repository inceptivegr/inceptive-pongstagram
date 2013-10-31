
<?php
/*------------------------------------------------------------------------
# incptvpongstagrambutton.php - Inceptive Pongstagram Editor Button
# ------------------------------------------------------------------------
# author    Inceptive Design Labs
# copyright Copyright (C) 2013 Inceptive Design Labs. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   http://extend.inceptive.gr
-------------------------------------------------------------------------*/

defined('_JEXEC') or die;
class plgButtonIncptvPongstagram extends JPlugin
{
    function onDisplay($name)
    {
        $document = JFactory::getDocument();
	$document->addStyleSheet(JURI::base(true).'/../plugins/editors-xtd/incptvpongstagram/css/style.css');
        $jsCode = "
                function insertShortCode(nameOfEditor) {
                    jInsertEditorText('[pongstagram]This is an easily tweetable text![/pongstagram]', nameOfEditor);
                }
            ";
        $document->addScriptDeclaration($jsCode);
        $button = new JObject();
        $button->set('text','Pongstagram');
        $button->set('onclick', 'insertShortCode(\''.$name.'\')');
        $button->set('name', 'pongstagram');
        return $button;

    }

}
?>

