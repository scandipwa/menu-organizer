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
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Store\Model\StoreManagerInterface;
use ScandiPWA\MenuOrganizer\Model\MenuFactory;
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

    /**
     * @var MenuFactory
     */
    protected $menuFactory;

    /**
     * @var MenuResourceModel
     */
    protected $menuResourceModel;

    /**
     * @var ItemCollectionFactory
     */
    protected $itemCollectionFactory;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Menu constructor.
     * @param StoreManagerInterface $storeManager
     * @param MenuFactory $menuFactory
     * @param MenuResourceModel $menuResourceModel
     * @param ItemCollectionFactory $itemCollectionFactory
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        MenuFactory $menuFactory,
        MenuResourceModel $menuResourceModel,
        ItemCollectionFactory $itemCollectionFactory,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->storeManager = $storeManager;
        $this->menuFactory = $menuFactory;
        $this->menuResourceModel = $menuResourceModel;
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->categoryRepository = $categoryRepository;
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

        return array_map(function ($item) {
            if (isset($item[self::CATEGORY_ID_KEY])) {
                $categoryUrlPath = $this->categoryRepository
                    ->get($item[self::CATEGORY_ID_KEY])
                    ->getUrlPath();

                $item['url'] = DIRECTORY_SEPARATOR . $categoryUrlPath;
            }

            return $item;
        }, $menuItems);
    }
}
