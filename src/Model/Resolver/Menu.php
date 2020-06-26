<?php
/**
 * ScandiPWA_MenuOrganizerGraphQl
 *
 * @category    ScandiPWA
 * @package     ScandiPWA_MenuOrganizer
 * @author      Raivis Dejus <info@scandiweb.com>
 * @copyright   Copyright (c) 2018 Scandiweb, Ltd (https://scandiweb.com)
 */
declare(strict_types=1);

namespace ScandiPWA\MenuOrganizer\Model\Resolver;

use Magento\Catalog\Model\Category;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use ScandiPWA\MenuOrganizer\Api\Data\ItemInterface;
use ScandiPWA\MenuOrganizer\Model\MenuFactory;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CatCollectionFactory;
use ScandiPWA\MenuOrganizer\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;
use ScandiPWA\MenuOrganizer\Model\ResourceModel\Menu as MenuResourceModel;

/**
 * Class Menu
 *
 * @package ScandiPWA\MenumanagerGraphQl\Model\Resolver
 */
class Menu implements ResolverInterface
{
    public const CATEGORY_ID_KEY = 'category_id';

    public const CMS_PAGE_IDENTIFIER = 'cms_page_identifier';

    /** @var MenuFactory */
    protected $menuFactory;

    /** @var MenuResourceModel */
    protected $menuResourceModel;

    /** @var ItemCollectionFactory */
    protected $itemCollectionFactory;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var CatCollectionFactory */
    protected $catCollectionFactory;

    /** @var PageCollectionFactory */
    protected $pageCollectionFactory;

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * Menu constructor.
     * @param StoreManagerInterface $storeManager
     * @param MenuFactory $menuFactory
     * @param MenuResourceModel $menuResourceModel
     * @param ItemCollectionFactory $itemCollectionFactory
     * @param CatCollectionFactory $catCollectionFactory
     * @param PageCollectionFactory $pageCollectionFactory
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        MenuFactory $menuFactory,
        MenuResourceModel $menuResourceModel,
        ItemCollectionFactory $itemCollectionFactory,
        CatCollectionFactory $catCollectionFactory,
        PageCollectionFactory $pageCollectionFactory,
        UrlInterface $urlBuilder
    ) {
        $this->storeManager = $storeManager;
        $this->menuFactory = $menuFactory;
        $this->menuResourceModel = $menuResourceModel;
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->catCollectionFactory = $catCollectionFactory;
        $this->pageCollectionFactory = $pageCollectionFactory;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Menu organizer resolver (lines concerning menu id are changed from core scandipwa menumanager)
     *
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     *
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $identifier = $args['identifier'];

        $menu = $this->menuFactory->create()->setStoreId(
            $this->storeManager->getStore()->getId()
        );

        $this->menuResourceModel->load($menu, $identifier);

        if ($menu->getId() === null) {
            throw new \InvalidArgumentException(sprintf("Could not find menu with identifier '%s'", $identifier));
        }

        return array_merge(
            $menu->getData(),
            [
                'items' => $this->getMenuItems($menu['menu_id'])
            ]
        );
    }

    /**
     * @param string $menuId
     * @return array
     * @throws LocalizedException
     */
    private function getMenuItems(string $menuId): array
    {
        $menuItems = $this->itemCollectionFactory
            ->create()
            ->addMenuFilter($menuId)
            ->addStatusFilter()
            ->setParentIdOrder()
            ->setPositionOrder()
            ->getData();

        $categoryIds = [];
        $pageIdentifiers = [];
        $itemsMap = [];

        foreach ($menuItems as $item) {
            $itemId = $item[ItemInterface::ITEM_ID];

            if (isset($item[self::CATEGORY_ID_KEY])) {
                $catId = $item[self::CATEGORY_ID_KEY];

                if (isset($categoryIds[$catId])) {
                    $categoryIds[$catId][] = $itemId;
                } else {
                    $categoryIds[$catId] = [$itemId];
                }
            } else if (isset($item[self::CMS_PAGE_IDENTIFIER])) {
                $pageIdentifier = $item[self::CMS_PAGE_IDENTIFIER];

                if (isset($pageIdentifiers[$pageIdentifier])) {
                    $pageIdentifiers[$pageIdentifier][] = $itemId;
                } else {
                    $pageIdentifiers[$pageIdentifier] = [$itemId];
                }
            }

            $itemsMap[$itemId] = $item;
        }

        $catCollection = $this->catCollectionFactory->create();
        $categories = $catCollection
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', ['in' => array_keys($categoryIds)])
            ->addFieldToFilter('is_active', 1)
            ->getItems();

        foreach ($categories as $category) {
            $catId = $category->getId();
            $itemIds = $categoryIds[$catId];

            foreach ($itemIds as $itemId) {
                /** @var $category Category */
                $itemsMap[$itemId]['url'] = parse_url($category->getUrl(), PHP_URL_PATH);
            }
        }

        $pageCollection = $this->pageCollectionFactory->create();
        $pages = $pageCollection
            ->addFieldToFilter('identifier', ['in' => array_keys($pageIdentifiers)])
            ->addFieldToFilter('is_active', 1)
            ->addStoreFilter($this->storeManager->getStore()->getId())
            ->getItems();

        foreach ($pages as $page) {
            $pageIdentifier = $page->getIdentifier();
            $itemIds = $pageIdentifiers[$pageIdentifier];

            foreach ($itemIds as $itemId) {
                $url = $this->urlBuilder->getUrl(null, ['_direct' => $page->getIdentifier()]);
                $itemsMap[$itemId]['url'] = parse_url($url, PHP_URL_PATH);
            }
        }

        foreach ($itemsMap as $itemId => $item) {
            // do not include items which URL can not be handled URL
            if (!$item['url']) {
                unset($itemsMap[$itemId]);
            }
        }

        return array_values($itemsMap);
    }
}
