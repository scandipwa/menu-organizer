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

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use ScandiPWA\MenuOrganizer\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;
use ScandiPWA\MenuOrganizer\Model\ResourceModel\Menu\CollectionFactory as MenuCollectionFactory;

/**
 * Class Menu
 *
 * @package ScandiPWA\MenumanagerGraphQl\Model\Resolver
 */
class Menu implements ResolverInterface
{
    /**
     * @var MenuCollectionFactory
     */
    protected $menuCollectionFactory;

    /**
     * @var ItemCollectionFactory
     */
    protected $itemCollectionFactory;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * Menu constructor.
     * @param MenuCollectionFactory $menuCollectionFactory
     * @param ItemCollectionFactory $itemCollectionFactory
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        MenuCollectionFactory $menuCollectionFactory,
        ItemCollectionFactory $itemCollectionFactory,
        CategoryRepositoryInterface $categoryRepository
    )
    {
        $this->menuCollectionFactory = $menuCollectionFactory;
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Menu organizer resolver (lines concerning menu id are changed from core scandipwa menumanager)
     *
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        $identifier = $args['identifier'];

        $menuCollection = $this->menuCollectionFactory
            ->create()
            ->addFieldToFilter('identifier', $identifier)
            ->load();

        if ($menuCollection->count() < 1)
            throw new \InvalidArgumentException("Could not find menu with identifier '${identifier}'");

        $menu = $menuCollection
            ->getFirstItem()
            ->getData();

        return array_merge(
            $menu,
            [
                'items' => $this->getMenuItems($menu['menu_id'])
            ]);
    }

    /**
     * @param string $menuId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getMenuItems(string $menuId): array
    {
        $categoryIdKey = 'category_id';

        $menuItems = $this->itemCollectionFactory
            ->create()
            ->addMenuFilter($menuId)
            ->addStatusFilter()
            ->setParentIdOrder()
            ->setPositionOrder()
            ->getData();

        foreach ($menuItems as &$item) {
            if (!isset($item[$categoryIdKey])) continue;

            $categoryUrlPath = $this->categoryRepository
                ->get($item[$categoryIdKey])
                ->getUrlPath();

            $item['url'] = "/${categoryUrlPath}";
        }

        return $menuItems;
    }
}
