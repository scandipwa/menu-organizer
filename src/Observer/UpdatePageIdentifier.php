<?php

namespace ScandiPWA\MenuOrganizer\Observer;

use Magento\Framework\Event\ObserverInterface;
use ScandiPWA\MenuOrganizer\Model\ResourceModel\Item\CollectionFactory;
use ScandiPWA\MenuOrganizer\Model\ResourceModel\Item;

/**
 * Class UpdatePageIdentifier
 * @package ScandiPWA\MenuOrganizer\Observer
 */
class UpdatePageIdentifier implements ObserverInterface
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
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $identifier = $observer->getData('object')->getData('identifier');
        $originData = $observer->getData('object')->getOrigData('identifier');
        $items = $this->collection->create()
            ->addFieldToFilter('cms_page_identifier', ['eq' => $originData])->getItems();

        foreach ($items as $item) {
            $item->setData('cms_page_identifier', $identifier);
            $this->data->save($item);
        }
    }
}
