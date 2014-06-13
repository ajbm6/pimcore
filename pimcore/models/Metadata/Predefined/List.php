<?php
/**
 * Pimcore
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.pimcore.org/license
 *
 * @category   Pimcore
 * @package    Metadata
 * @copyright  Copyright (c) 2009-2014 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     New BSD License
 */

class Metadata_Predefined_List extends Pimcore_Model_List_Abstract {

    /**
     * Contains the results of the list. They are all an instance of Property_Predefined
     *
     * @var array
     */
    public $properties = array();

    /**
     * Tests if the given key is an valid order key to sort the results
     *
     * @return boolean
     */
    public function isValidOrderKey($key) {
        return true;
    }

    /**
     * @return array
     */
    public function getDefinitions() {
        return $this->definitions;
    }

    /**
     * @param array $properties
     * @return void
     */
    public function setDefinitions($definitions) {
        $this->definitions = $definitions;
        return $this;
    }

    public static function getByTargetType($type, $subType) {
        if ($type != "asset") {
            throw new Exception("other types than assets are currently not supported");
        }
        $db = Pimcore_Resource::get();
        $list = new self();
        $list->setCondition("targetSubtype = " . $db->quote($subType));
        $list = $list->load();
        return $list;
    }

    /**
     * @param $key
     * @param $language
     * @return Metadata_Predefined
     */
    public static function getByKeyAndLanguage($key, $language) {

        $db = Pimcore_Resource::get();
        $list = new self();
        $condition = "name = " . $db->quote($key);
        if ($language) {
            $condition .= " AND language = " . $db->quote($language);
        } else {
            $condition .= " AND (language = '' OR LANGUAGE IS NULL)";
        }
        $list->setCondition($condition);
        $list = $list->load();
        if ($list) {
            return $list[0];
        }
        return null;
    }

}