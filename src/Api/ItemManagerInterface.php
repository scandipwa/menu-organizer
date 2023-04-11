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
 * Interface ItemManagerInterface
 * @package ScandiPWA\MenuOrganizer
 */
interface ItemManagerInterface
{
    /**
     * @param int $itemId
     * @return int
     */
    public function deleteChildren(int $itemId): int;
}
