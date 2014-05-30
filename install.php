<?php
/**
 * @package      Gamification Platform
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Script file of the component
 */
class pkg_gamificationInstallerScript
{
    /**
     * Method to install the component.
     *
     * @param object $parent
     *
     * @return void
     */
    public function install($parent)
    {
    }

    /**
     * Method to uninstall the component.
     *
     * @param $parent
     *
     * @return void
     */
    public function uninstall($parent)
    {
    }

    /**
     * Method to update the component.
     *
     * @param $parent
     *
     * @return void
     */
    public function update($parent)
    {
    }

    /**
     * Method to run before an install/update/uninstall method.
     *
     * @param $type
     * @param $parent
     *
     * @return void
     */
    public function preflight($type, $parent)
    {
    }

    /**
     * Method to run after an install/update/uninstall method.
     *
     * @param string $type
     * @param string $parent
     *
     * @return void
     */
    public function postflight($type, $parent)
    {
        if (!defined("COM_GAMIFICATION_PATH_COMPONENT_ADMINISTRATOR")) {
            define("COM_GAMIFICATION_PATH_COMPONENT_ADMINISTRATOR", JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "com_gamification");
        }

        // Register Component helpers
        JLoader::register("GamificationInstallHelper", COM_GAMIFICATION_PATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . "helpers" . DIRECTORY_SEPARATOR . "install.php");

        jimport('joomla.filesystem.path');
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        $params       = JComponentHelper::getParams("com_gamification");
        $imagesFolder = JFolder::makeSafe($params->get("images_directory", "images/gamification"));
        $imagesPath   = JPath::clean(JPATH_SITE . DIRECTORY_SEPARATOR . $imagesFolder);

        // Create images folder
        if (!JFolder::exists($imagesPath)) {
            GamificationInstallHelper::createFolder($imagesPath);
        }

        // Start table with the information
        GamificationInstallHelper::startTable();

        // Requirements
        GamificationInstallHelper::addRowHeading(JText::_("COM_GAMIFICATION_MINIMUM_REQUIREMENTS"));

        // Display result about verification for existing folder
        $title = JText::_("COM_GAMIFICATION_IMAGE_FOLDER");
        $info  = $imagesFolder;
        if (!is_dir($imagesPath)) {
            $result = array("type" => "important", "text" => JText::_("JNO"));
        } else {
            $result = array("type" => "success", "text" => JText::_("JYES"));
        }
        GamificationInstallHelper::addRow($title, $result, $info);

        // Display result about verification for writable folder
        $title = JText::_("COM_GAMIFICATION_WRITABLE_FOLDER");
        $info  = $imagesFolder;
        if (!is_writable($imagesPath)) {
            $result = array("type" => "important", "text" => JText::_("JNO"));
        } else {
            $result = array("type" => "success", "text" => JText::_("JYES"));
        }
        GamificationInstallHelper::addRow($title, $result, $info);

        // Display result about verification for GD library
        $title = JText::_("COM_GAMIFICATION_GD_LIBRARY");
        $info  = "";
        if (!extension_loaded('gd') and function_exists('gd_info')) {
            $result = array("type" => "important", "text" => JText::_("COM_GAMIFICATION_WARNING"));
        } else {
            $result = array("type" => "success", "text" => JText::_("JON"));
        }
        GamificationInstallHelper::addRow($title, $result, $info);

        // Display result about verification for cURL library
        $title = JText::_("COM_GAMIFICATION_CURL_LIBRARY");
        $info  = "";
        if (!extension_loaded('curl')) {
            $info   = JText::_("COM_GAMIFICATION_CURL_INFO");
            $result = array("type" => "important", "text" => JText::_("JOFF"));
        } else {
            $result = array("type" => "success", "text" => JText::_("JON"));
        }
        GamificationInstallHelper::addRow($title, $result, $info);

        // Display result about verification Magic Quotes
        $title = JText::_("COM_GAMIFICATION_MAGIC_QUOTES");
        $info  = "";
        if (get_magic_quotes_gpc()) {
            $info   = JText::_("COM_GAMIFICATION_MAGIC_QUOTES_INFO");
            $result = array("type" => "important", "text" => JText::_("JON"));
        } else {
            $result = array("type" => "success", "text" => JText::_("JOFF"));
        }
        GamificationInstallHelper::addRow($title, $result, $info);

        // Display result about verification FileInfo
        $title = JText::_("COM_GAMIFICATION_FILEINFO");
        $info  = "";
        if (!function_exists('finfo_open')) {
            $info   = JText::_("COM_GAMIFICATION_FILEINFO_INFO");
            $result = array("type" => "important", "text" => JText::_("JOFF"));
        } else {
            $result = array("type" => "success", "text" => JText::_("JON"));
        }
        GamificationInstallHelper::addRow($title, $result, $info);

        // Display result about PHP version
        $title = JText::_("COM_GAMIFICATION_PHP_VERSION");
        $info  = "";
        if (version_compare(PHP_VERSION, '5.3.0') < 0) {
            $result = array("type" => "important", "text" => JText::_("COM_GAMIFICATION_WARNING"));
        } else {
            $result = array("type" => "success", "text" => JText::_("JYES"));
        }
        GamificationInstallHelper::addRow($title, $result, $info);

        // Display result about verification of installed ITPrism Library
        jimport("itprism.version");
        $title = JText::_("COM_GAMIFICATION_ITPRISM_LIBRARY");
        $info  = "";
        if (!class_exists("ITPrismVersion")) {
            $info   = JText::_("COM_GAMIFICATION_ITPRISM_LIBRARY_DOWNLOAD");
            $result = array("type" => "important", "text" => JText::_("JNO"));
        } else {
            $result = array("type" => "success", "text" => JText::_("JYES"));
        }
        GamificationInstallHelper::addRow($title, $result, $info);

        // Installed extensions

        GamificationInstallHelper::addRowHeading(JText::_("COM_GAMIFICATION_INSTALLED_EXTENSIONS"));

        // Gamification Library
        $result = array("type" => "success", "text" => JText::_("COM_GAMIFICATION_INSTALLED"));
        GamificationInstallHelper::addRow(JText::_("COM_GAMIFICATION_GAMIFICATION_LIBRARY"), $result, JText::_("COM_GAMIFICATION_LIBRARY"));

        // Gamification User Gamification
        $result = array("type" => "success", "text" => JText::_("COM_GAMIFICATION_INSTALLED"));
        GamificationInstallHelper::addRow(JText::_("COM_GAMIFICATION_PLUGIN_USER_GAMIFICATION"), $result, JText::_("COM_GAMIFICATION_PLUGIN"));

        // Gamification System Gamification
        $result = array("type" => "success", "text" => JText::_("COM_GAMIFICATION_INSTALLED"));
        GamificationInstallHelper::addRow(JText::_("COM_GAMIFICATION_PLUGIN_SYSTEM_GAMIFICATION"), $result, JText::_("COM_GAMIFICATION_PLUGIN"));

        // End table
        GamificationInstallHelper::endTable();

        echo JText::sprintf("COM_GAMIFICATION_MESSAGE_REVIEW_SAVE_SETTINGS", JRoute::_("index.php?option=com_gamification"));

        jimport("itprism.version");
        if (!class_exists("ITPrismVersion")) {
            echo JText::_("COM_GAMIFICATION_MESSAGE_INSTALL_ITPRISM_LIBRARY");
        }
    }
}
