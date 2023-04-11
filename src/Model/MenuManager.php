<?php
/**
 * @category   ScandiPWA
 * @package    ScandiPWA_MenuOrganizer
 * @author     Vladislavs Zimnikovs <info@scandiweb.com>
 * @copyright  Copyright (c) 2022 Scandiweb, Ltd (https://scandiweb.com)
 */

declare(strict_types=1);

namespace ScandiPWA\MenuOrganizer\Model;

use Magento\Framework\App\ResourceConnection;
use ScandiPWA\MenuOrganizer\Api\MenuManagerInterface;
use ScandiPWA\MenuOrganizer\Model\ResourceModel\Item\Collection as ItemCollection;
use ScandiPWA\MenuOrganizer\Model\Item;

/**
 * Class MenuManager
 * @package ScandiPWA\MenuOrganizer
 */
class MenuManager implements MenuManagerInterface
{
    /**
     * @var ItemCollection $itemCollection
     */
    protected ItemCollection $itemCollection;

    /**
     * @var ResourceConnection $resourceConnection
     */
    protected ResourceConnection $resourceConnection;

    /**
     * MenuManager constructor.
     * @param ItemCollection $itemCollection
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ItemCollection $itemCollection,
        ResourceConnection $resourceConnection
    ) {
        $this->itemCollection = $itemCollection;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param int $menuId
     * @return int
     */
    public function deleteItems(int $menuId): int
    {
        /**
         * @var Item[] $menuItems
         */
        $menuItems = $this->itemCollection
            ->addFieldToSelect('item_id')
            ->addMenuFilter($menuId)
            ->getItems();
        $connection = $this->resourceConnection->getConnection();

        $itemIdsToDelete = array_map(static function (Item $item) {
            return $item->getItemId();
        }, $menuItems);

        return $connection->delete(
            $this->resourceConnection->getTableName('scandiweb_menumanager_item'),
            ['item_id IN (?)' => $itemIdsToDelete]
        );
    }
}
