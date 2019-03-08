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

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;

use ScandiPWA\MenuOrganizer\Model\ResourceModel\Menu\CollectionFactory as MenuCollectionFactory;
use ScandiPWA\MenuOrganizer\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;

/**
 * Class Menu
 * @package Scandiweb\MenumanagerGraphQl\Model\Resolver
 */
class Menu implements ResolverInterface
{
    /**
     * @var ValueFactory
     */
    private $valueFactory;

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
     * @param ValueFactory $valueFactory
     * @param MenuCollectionFactory $menuCollectionFactory
     * @param ItemCollectionFactory $itemCollectionFactory
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        ValueFactory $valueFactory,
        MenuCollectionFactory $menuCollectionFactory,
        ItemCollectionFactory $itemCollectionFactory,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->valueFactory = $valueFactory;
        $this->menuCollectionFactory = $menuCollectionFactory;
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return Value
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): Value {
        $result = function () {
            return null;
        };

        if (isset($args['id'])) {
            $menu = $this->menuCollectionFactory->create();
            $menu->addFieldToFilter('menu_id', $args['id'])->load();
            $menuData = $menu->getFirstItem()->getData();

            $items = $this->itemCollectionFactory->create();
            $items->addMenuFilter($args['id'])
                ->addStatusFilter()
                ->setParentIdOrder()
                ->setPositionOrder();

            $menuData['items'] = $items->getData();

            foreach ($menuData['items'] as &$item) {
                if (isset($item['category_id'])) {
                    $category = $this->categoryRepository->get($item['category_id']);
                    $item['url'] = sprintf('/%s', $category->getUrlPath());
                }
            }

            if ($menuData) {
                $result = function () use ($menuData) {
                    return $menuData;
                };
            }
        }

        return $this->valueFactory->create($result);
    }

}
