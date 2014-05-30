<?php
/**
 * @package      Gamification Platform
 * @subpackage   Components Platform
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die;

jimport('itprism.controller.admin');

/**
 * Gamification Platform profile Controller
 *
 * @package     Gamification
 * @package     Components Platform
 */
class GamificationControllerProfiles extends ITPrismControllerAdmin
{
    /**
     * Proxy for getModel.
     *
     * @param string $name
     * @param string $prefix
     * @param array  $config
     *
     * @return GamificationModelProfile
     *
     * @todo fix this controller
     *
     * @since   1.6
     */
    public function getModel($name = 'Profile', $prefix = 'GamificationModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

    public function create()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Get form data
        $pks   = $this->input->post->get('cid', array(), 'array');
        $model = $this->getModel("Profile", "GamificationModel");
        /** @var $model GamificationModelProfile */

        JArrayHelper::toInteger($pks);

        // Check for validation errors.
        if (empty($pks)) {
            $this->defaultLink .= "&view=" . $this->view_list;

            $this->setMessage(JText::_("COM_GAMIFICATION_INVALID_ITEM"), "notice");
            $this->setRedirect(JRoute::_($this->defaultLink, false));

            return;
        }

        try {

            $pks = $model->filterProfiles($pks);

            if (!$pks) {
                $this->defaultLink .= "&view=" . $this->view_list;

                $this->setMessage(JText::_("COM_GAMIFICATION_INVALID_ITEM"), "notice");
                $this->setRedirect(JRoute::_($this->defaultLink, false));

                return;
            }

            $model->create($pks);

        } catch (Exception $e) {
            JLog::add($e->getMessage());
            throw new Exception(JText::_('COM_GAMIFICATION_ERROR_SYSTEM'));
        }

        $msg  = JText::plural('COM_GAMIFICATION_N_PROFILES_CREATED', count(pks));
        $link = $this->defaultLink . "&view=" . $this->view_list;

        $this->setRedirect(JRoute::_($link, false), $msg);

    }
}
