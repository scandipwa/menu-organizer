<?php

namespace ScandiPWA\MenuOrganizer\Model;

use ScandiPWA\MenuOrganizer\Api\Data\ItemInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * @category ScandiPWA
 * @package ScandiPWA\MenuOrganizer\Model
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Menu
 */
class Item extends AbstractModel implements ItemInterface, IdentityInterface
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'scandipwa_menuorganizer_item';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\ScandiPWA\MenuOrganizer\Model\ResourceModel\Item::class);
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [Menu::CACHE_TAG . '_' . $this->getMenuId()];
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getData(self::ITEM_ID);
    }

    /**
     * @return int
     */
    public function getMenuId()
    {
        return $this->getData(self::MENU_ID);
    }

    /**
     * @param mixed $menuId
     *
     * @return $this
     */
    public function setMenuId($menuId)
    {
        return $this->setData(self::MENU_ID, $menuId);
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->getData(self::PARENT_ID);
    }

    /**
     * @param mixed $parentId
     *
     * @return $this
     */
    public function setParentId($parentId)
    {
        return $this->setData(self::PARENT_ID, $parentId);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @return string
     */
    public function getItemClass()
    {
        return $this->getData(self::ITEM_CLASS);
    }

    /**
     * @param string $itemClass
     *
     * @return $this
     */
    public function setItemClass($itemClass)
    {
        return $this->setData(self::ITEM_CLASS, $itemClass);
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }

    /**
     * @param $identifier
     *
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * @param $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @return string
     */
    public function getOpenType()
    {
        return $this->getData(self::OPEN_TYPE);
    }

    /**
     * @param string $openType
     *
     * @return $this
     */
    public function setOpenType($openType)
    {
        return $this->setData(self::OPEN_TYPE, $openType);
    }

    /**
     * @return string
     */
    public function getUrlType()
    {
        return $this->getData(self::URL_TYPE);
    }

    /**
     * @param string $urlType
     *
     * @return $this
     */
    public function setUrlType($urlType)
    {
        return $this->setData(self::URL_TYPE, $urlType);
    }

    /**
     * @return string
     */
    public function getCmsPageIdentifier()
    {
        return $this->getData(self::CMS_PAGE_ID);
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setCmsPageIdentifier($id)
    {
        return $this->setData(self::CMS_PAGE_ID, $id);
    }

    /**
     * @return string
     */
    public function getCategoryId()
    {
        return $this->getData(self::CATEGORY_ID);
    }

    /**
     * @param string $categoryId
     *
     * @return $this
     */
    public function setCategoryId($categoryId)
    {
        return $this->setData(self::CATEGORY_ID, $categoryId);
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

    /**
     * @param string $position
     *
     * @return $this
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * @return string
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @param $isActive
     *
     * @return $this
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @return string
     */
    public function getUrlAttributes()
    {
        return $this->getData(self::URL_ATTRIBUTES);
    }

    /**
     * @param string $urlAttributes
     */
    public function setUrlAttributes($urlAttributes)
    {
        return $this->setData(self::URL_ATTRIBUTES, $urlAttributes);
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->getData(self::ICON);
    }

    /**
     * @param $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        return $this->setData(self::ICON, $icon);
    }

    /**
     * @return mixed|string
     */
    public function getIconAlt()
    {
        return $this->getData(self::ICON_ALT);
    }

    /**
     * @param $iconAlt
     * @return $this
     */
    public function setIconAlt($iconAlt)
    {
        return $this->setData(self::ICON_ALT, $iconAlt);
    }
}
