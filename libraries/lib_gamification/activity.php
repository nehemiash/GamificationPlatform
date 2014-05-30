<?php
/**
 * @package         GamificationPlatform
 * @subpackage      GamificationLibrary
 * @author          Todor Iliev
 * @copyright       Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

/**
 * This class contains methods that are used for managing an activity.
 *
 * @package         GamificationPlatform
 * @subpackage      GamificationLibrary
 */
class GamificationActivity
{
    /**
     * Activity ID
     * @var integer
     */
    public $id;

    public $info;
    public $image;
    public $url;
    public $created;
    public $user_id;

    /**
     * Driver of the database.
     *
     * @var JDatabaseDriver
     */
    protected $db;

    /**
     * Initialize the object and load activity data.
     *
     * <code>
     *
     * $activityId = 1;
     * $activity   = new GamificationActivity($activityId);
     *
     * </code>
     *
     * @param int $id
     */
    public function __construct($id = 0)
    {
        $this->db = JFactory::getDbo();

        if (!empty($id)) {
            $this->load($id);
        } else {
            $this->init();
        }
    }

    /**
     * Load user activity data.
     *
     * <code>
     *
     * $activityId = 1;
     *
     * $activity   = new GamificationActivity();
     * $activity->load($activityId);
     *
     * </code>
     *
     * @param integer $id
     *
     */
    public function load($id)
    {
        // Create a new query object.
        $query = $this->db->getQuery(true);

        $query
            ->select("a.*")
            ->from($this->db->quoteName("#__gfy_activities", "a"))
            ->where("a.id = " . (int)$id);

        $this->db->setQuery($query);
        $result = $this->db->loadAssoc();

        if (!empty($result)) { // Set values to variables
            $this->bind($result);
        } else {
            $this->init();
        }
    }

    /**
     * Initialize the object data.
     */
    protected function init()
    {
        $date          = new JDate();
        $this->created = $date->format("Y-m-d H:i:s");
        $this->id      = null;
    }

    /**
     * Set the data to the object parameters.
     *
     * <code>
     *
     * $data = array(
     *        "info"        => "......",
     *        "image"    => "picture.png",
     *        "url"    => "http://itprism.com/",
     *        "user_id"   => 1
     * );
     *
     * $activity   = new GamificationActivity();
     * $activity->bind($data);
     *
     * </code>
     *
     * @param array $data
     */
    public function bind($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Save the data to the database.
     *
     * <code>
     *
     * $data = array(
     *        "info"        => "......",
     *        "image"    => "picture.png",
     *        "url"    => "http://itprism.com/",
     *        "user_id"   => 1
     * );
     *
     * $activity   = new GamificationActivity();
     * $activity->bind($data);
     * $activity->store();
     *
     * </code>
     *
     */
    public function store()
    {
        if (!$this->id) {
            $this->id = $this->insertObject();
        } else {
            $this->updateObject();
        }
    }

    protected function updateObject()
    {
        // Create a new query object.
        $query = $this->db->getQuery(true);

        $query
            ->update("#__gfy_activities")
            ->set($this->db->quoteName("info") . "  = " . $this->db->quote($this->info))
            ->set($this->db->quoteName("image") . "  = " . $this->db->quote($this->image))
            ->set($this->db->quoteName("url") . "  = " . $this->db->quote($this->url))
            ->set($this->db->quoteName("user_id") . "  = " . (int)$this->user_id)
            ->where($this->db->quoteName("id") . "  = " . (int)$this->id);

        $this->db->setQuery($query);
        $this->db->execute();
    }

    protected function insertObject()
    {
        if (!$this->user_id) {
            throw new Exception("Invalid user id", 500);
        }

        // Create a new query object.
        $query = $this->db->getQuery(true);

        $date          = new JDate($this->created);
        $unixTimestamp = $date->toSql();

        $query
            ->insert("#__gfy_activities")
            ->set($this->db->quoteName("info") . " = " . $this->db->quote($this->info))
            ->set($this->db->quoteName("created") . " = " . $this->db->quote($unixTimestamp))
            ->set($this->db->quoteName("user_id") . " = " . (int)$this->user_id);

        if (!empty($this->image)) {
            $query->set($this->db->quoteName("image") . " = " . $this->db->quote($this->image));
        }

        if (!empty($this->image)) {
            $query->set($this->db->quoteName("url") . " = " . $this->db->quote($this->url));
        }

        $this->db->setQuery($query);
        $this->db->execute();

        return $this->db->insertid();
    }
}
