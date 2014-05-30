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

jimport('joomla.application.component.modeladmin');

class GamificationModelLevel extends JModelAdmin
{
    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param   string $type    The table type to instantiate
     * @param   string $prefix A prefix for the table class name. Optional.
     * @param   array  $config Configuration array for model. Optional.
     *
     * @return  JTable  A database object
     * @since   1.6
     */
    public function getTable($type = 'Level', $prefix = 'GamificationTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array   $data     An optional array of data for the form to interogate.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm   A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm($this->option . '.level', 'level', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed   The data for the form.
     * @since   1.6
     */
    protected function loadFormData()
    {
        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        // Check the session for previously entered form data.
        $data = $app->getUserState($this->option . '.edit.level.data', array());

        if (empty($data)) {
            $data = $this->getItem();

            // Prime some default values.
            if ($this->getState($this->getName() . '.id') == 0) {
                $data->set('group_id', $app->input->getInt('group_id', $app->getUserState($this->option . '.levels.filter.group_id')));
                $data->set('rank_id', $app->input->getInt('rank_id', $app->getUserState($this->option . '.levels.filter.rank_id')));
            }
        }

        return $data;
    }

    /**
     * Save data into the DB
     *
     * @param array $data   The data about item
     *
     * @return   int
     */
    public function save($data)
    {
        $id        = JArrayHelper::getValue($data, "id");
        $title     = JArrayHelper::getValue($data, "title");
        $points    = JArrayHelper::getValue($data, "points");
        $pointsId  = JArrayHelper::getValue($data, "points_id");
        $value     = JArrayHelper::getValue($data, "value");
        $rankId    = JArrayHelper::getValue($data, "rank_id");
        $groupId   = JArrayHelper::getValue($data, "group_id");
        $published = JArrayHelper::getValue($data, "published");

        // Load a record from the database
        $row = $this->getTable();
        $row->load($id);

        $row->set("title", $title);
        $row->set("points", $points);
        $row->set("points_id", $pointsId);
        $row->set("value", $value);
        $row->set("rank_id", $rankId);
        $row->set("group_id", $groupId);
        $row->set("published", $published);

        $row->store();

        return $row->get("id");
    }
}
