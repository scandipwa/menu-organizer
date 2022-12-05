<?php
/**
 * @category    Macron
 * @author      Vladislavs Zimnikovs <vladislavs.zimnikovs@scandiweb.com | info@scandiweb.com>
 * @copyright   Copyright (c) 2022 Scandiweb, Inc (http://scandiweb.com)
 * @license     http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Macron\MenuOrganizer\Model;

use Macron\MenuOrganizer\Api\ItemManagerInterface;
use ScandiPWA\MenuOrganizer\Model\Item;
use ScandiPWA\MenuOrganizer\Model\ResourceModel\Item\Collection as ItemCollection;
use Magento\Framework\App\ResourceConnection;

/**
 * Class ItemManager
 */
class ItemManager implements ItemManagerInterface
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
     * ItemManagement constructor.
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
     * @param string|int $itemId
     * @return int
     */
    public function deleteChildren(string|int $itemId): int
    {
        $connection = $this->resourceConnection->getConnection();
        /**
         * @var Item[] $menuItems
         */
        $menuItems = $this->itemCollection
            ->getItems();
        $itemIdsToDelete = [];
        // Filling queue with children of passed item
        $queue = array_filter($menuItems, static function (Item $item) use ($itemId) {
            return (string)$item->getParentId() === (string)$itemId;
        });

        while (count($queue)) {
            /**
             * @var Item $nextItem
             */
            $nextItem = array_shift($queue);
            $itemIdsToDelete[] = $nextItem->getId();

            // Getting children of current item
            // Result maybe empty array if no children is present
            $children = array_filter($menuItems, static function (Item $item) use ($nextItem) {
                return (string)$item->getParentId() === (string)$nextItem->getId();
            });

            // Adding children to queue
            $queue = [...$queue, ...$children];
        }

        // Performing one direct query to delete all children
        // in one operation
        $connection->delete(
            $connection->getTableName('scandiweb_menumanager_item'),
            [
                'item_id IN (?)' => $itemIdsToDelete
            ]
        );

        return count($itemIdsToDelete);
    }
}
