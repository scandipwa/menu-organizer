<?php
/**
 * @category   ScandiPWA
 * @package    ScandiPWA_MenuOrganizer
 * @author     Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com>
 * @copyright  Copyright (c) 2015 Scandiweb, Ltd (https://scandiweb.com)
 */

namespace ScandiPWA\MenuOrganizer\Api\Data;

/**
 * Interface ItemInterface
 * @package ScandiPWA\MenuOrganizer
 */
interface ItemInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ITEM_ID = 'item_id';
    const MENU_ID = 'menu_id';
    const PARENT_ID = 'parent_id';
    const TITLE = 'title';
    const ITEM_CLASS = 'item_class';
    const IDENTIFIER = 'identifier';
    const URL = 'url';
    const OPEN_TYPE = 'open_type';
    const URL_TYPE = 'url_type';
    const CMS_PAGE_ID = 'cms_page_id';
    const CATEGORY_ID = 'category_id';
    const POSITION = 'position';
    const IS_ACTIVE = 'is_active';
    const URL_ATTRIBUTES = 'url_attributes';
    const ICON = 'icon';
    const ICON_ALT = 'icon_alt';

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getMenuId();

    /**
     * @return mixed
     */
    public function getParentId();

    /**
     * @return mixed
     */
    public function getTitle();

    /**
     * @return mixed
     */
    public function getItemClass();

    /**
     * @return mixed
     */
    public function getIdentifier();

    /**
     * @return mixed
     */
    public function getUrl();

    /**
     * @return mixed
     */
    public function getOpenType();

    /**
     * @return mixed
     */
    public function getUrlType();

    /**
     * @return mixed
     */
    public function getCmsPageIdentifier();

    /**
     * @return mixed
     */
    public function getCategoryId();

    /**
     * @return mixed
     */
    public function getPosition();

    /**
     * @return mixed
     */
    public function getIsActive();

    /**
     * @return mixed
     */
    public function getUrlAttributes();

    /**
     * @return mixed
     */
    public function getIcon();

    /**
     * @return string
     */
    public function getIconAlt();

    /**
     * @param $id
     *
     * @return mixed
     */
    public function setId($id);

    /**
     * @param $menuId
     *
     * @return mixed
     */
    public function setMenuId($menuId);

    /**
     * @param $parentId
     *
     * @return mixed
     */
    public function setParentId($parentId);

    /**
     * @param $title
     *
     * @return mixed
     */
    public function setTitle($title);

    /**
     * @param $itemClass
     *
     * @return mixed
     */
    public function setItemClass($itemClass);

    /**
     * @param $identifier
     *
     * @return mixed
     */
    public function setIdentifier($identifier);

    /**
     * @param $url
     *
     * @return mixed
     */
    public function setUrl($url);

    /**
     * @param $openType
     *
     * @return mixed
     */
    public function setOpenType($openType);

    /**
     * @param $urlType
     *
     * @return mixed
     */
    public function setUrlType($urlType);

    /**
     * @param $identifier
     *
     * @return mixed
     */
    public function setCmsPageIdentifier($identifier);

    /**
     * @param $categoryId
     *
     * @return mixed
     */
    public function setCategoryId($categoryId);

    /**
     * @param $position
     *
     * @return mixed
     */
    public function setPosition($position);

    /**
     * @param $isActive
     *
     * @return mixed
     */
    public function setIsActive($isActive);

    /**
     * @param $urlAttributes
     *
     * @return mixed
     */
    public function setUrlAttributes($urlAttributes);

    /**
     * @param $icon
     *
     * @return mixed
     */
    public function setIcon($icon);

    /**
     * @param $iconAlt
     *
     * @return mixed
     */
    public function setIconAlt($iconAlt);
}
