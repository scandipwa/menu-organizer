<?php
/**
 * @category   ScandiPWA
 * @package    ScandiPWA_MenuOrganizer
 * @author     Vladislavs Zimnikovs <info@scandiweb.com>
 * @copyright  Copyright (c) 2022 Scandiweb, Ltd (https://scandiweb.com)
 */

declare(strict_types=1);

namespace ScandiPWA\MenuOrganizer\Api;

/**
 * Interface ItemInterface
 * @package ScandiPWA\MenuOrganizer
 */
interface MenuManagerInterface
{
    /**
     * @param int $menuId
     * @return int
     */
    public function deleteItems(int $menuId): int;
}
