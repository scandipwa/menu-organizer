<?php
/**
 * @category   ScandiPWA
 * @package    ScandiPWA_MenuOrganizer
 * @author     Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com>
 * @copyright  Copyright (c) 2015 Scandiweb, Ltd (https://scandiweb.com)
 */

namespace ScandiPWA\MenuOrganizer\Api\Data;

/**
 * Interface MenuInterface
 * @package ScandiPWA\MenuOrganizer
 */
interface MenuInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const MENU_ID = 'menu_id';
    const IDENTIFIER = 'identifier';
    const TITLE = 'title';
    const CSS_CLASS = 'css_class';
    const IS_ACTIVE = 'is_active';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Identifier
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get menu css class
     *
     * @return mixed
     */
    public function getCssClass();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set ID
     *
     * @param int $id
     * @return \ScandiPWA\MenuOrganizer\Api\Data\MenuInterface
     */
    public function setId($id);

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \ScandiPWA\MenuOrganizer\Api\Data\MenuInterface
     */
    public function setIdentifier($identifier);

    /**
     * Set title
     *
     * @param string $title
     * @return \ScandiPWA\MenuOrganizer\Api\Data\MenuInterface
     */
    public function setTitle($title);

    /**
     * Set menu css class
     *
     * @param string $class
     * @return \ScandiPWA\MenuOrganizer\Api\Data\MenuInterface
     */
    public function setCssClass($class);

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \ScandiPWA\MenuOrganizer\Api\Data\MenuInterface
     */
    public function setIsActive($isActive);
}
