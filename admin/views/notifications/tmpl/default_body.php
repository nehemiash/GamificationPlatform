<?php
/**
 * @package      Gamification Platform
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;
?>
<?php foreach ($this->items as $i => $item) { ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class="center hidden-phone">
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td>
            <a href="<?php echo JRoute::_("index.php?option=com_gamification&view=notification&layout=edit&id=" . (int)$item->id); ?>">
                <?php echo JHtmlString::truncate(strip_tags($item->note), 64); ?>
            </a>
        </td>
        <td class="center hidden-phone">
            <?php echo $item->name; ?>
        </td>
        <td class="center hidden-phone">
            <?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC3')); ?>
        </td>
        <td class="center hidden-phone">
            <?php echo (!$item->url) ? "--" : '<a href="' . $item->url . '">' . JText::_("COM_GAMIFICATION_LINK") . '</a>'; ?>
        </td>
        <td class="center hidden-phone">
            <?php echo (!$item->image) ? "--" : '<img src="' . JUri::root().$item->image . '" />'; ?>
        </td>
        <td class="center hidden-phone">
            <?php
            if (!$item->status) {
                $tooltipTitle = JText::_("COM_GAMIFICATION_NOT_READ");
            } else {
                $tooltipTitle = JText::_("COM_GAMIFICATION_READ");
            }
            echo JHtml::_('gamification.boolean', $item->status, $tooltipTitle);
            ?>
        </td>
        <td class="center hidden-phone">
            <?php echo $item->id; ?>
        </td>
    </tr>
<?php } ?>
	  