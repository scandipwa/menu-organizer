<?php
/**
 * @category    Macron
 * @author      Vladislavs Zimnikovs <vladislavs.zimnikovs@scandiweb.com | info@scandiweb.com>
 * @copyright   Copyright (c) 2022 Scandiweb, Inc (http://scandiweb.com)
 * @license     http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Macron\MenuOrganizer\Api;

/**
 * Interface MenuManagerInterface
 */
interface MenuManagerInterface
{
    /**
     * @param int $menuId
     * @return int
     */
    public function deleteItems(int $menuId): int;
}
