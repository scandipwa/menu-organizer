<?php

namespace ScandiPWA\MenuOrganizer\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use ScandiPWA\MenuOrganizer\Model\ResourceModel\Item\CollectionFactory;
use ScandiPWA\MenuOrganizer\Model\ResourceModel\Item;

/**
 * Class DeleteCategoryItem
 * @package ScandiPWA\MenuOrganizer\Observer
 */
class DeleteCategoryItem implements ObserverInterface
{
    /**
     * @var Item
     */
    private $data;

    /**
     * @var CollectionFactory
     */
    private $collection;

    /**
     * UpdatePageIdentifier constructor.
     * @param Item $data
     * @param CollectionFactory $collection
     */
    public function __construct(
        Item $data,
        CollectionFactory $collection
    )
    {
        $this->collection = $collection;
        $this->data = $data;
    }

    /**
     * @param Observer $observer
     * @throws AlreadyExistsException
     */
    public function execute(Observer $observer)
    {
        /** @var $category \Magento\Catalog\Model\Category */
        $category = $observer->getEvent()->getCategory();

        $items = $this->collection
            ->create()
            ->addFieldToFilter('category_id', ['eq' => $category->getId()])
            ->getItems();

        foreach ($items as $item) {
            // disable all menu items assigned to category
            $item->setData('is_active', 0);
            $this->data->save($item);
        }
    }
}
