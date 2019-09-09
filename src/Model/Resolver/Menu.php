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
 *
 * @package ScandiPWA\MenumanagerGraphQl\Model\Resolver
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
     * Menu organizer resolver (lines concerning menu id are changed from core scandipwa menumanager)
     *
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return Value
     * @throws \Magento\Framework\Exception\NoSuchEntityException
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

        if (isset($args['identifier'])) {
            $menu = $this->menuCollectionFactory->create();

            /** Updated with identifier filtering */
            $menu->addFieldToFilter('identifier', $args['identifier'])->load();

            $menuData = $menu->getFirstItem()->getData();

            $items = $this->itemCollectionFactory->create();

            /** Updated with menu id taken from menu data */
            $items->addMenuFilter($menuData['menu_id'])
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