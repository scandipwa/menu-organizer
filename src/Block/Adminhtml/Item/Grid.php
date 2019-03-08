<?php
namespace ScandiPWA\MenuOrganizer\Block\Adminhtml\Item;

/**
 * @category ScandiPWA
 * @package ScandiPWA\MenuOrganizer\Block\Adminhtml\Menu
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Adminhtml menu edit form
 *
 * Class Form
 */
use Magento\Backend\Block\Widget\Grid\Column;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $_menumanagerHelper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \ScandiPWA\MenuOrganizer\Model\ResourceModel\Item\Collection $itemCollection
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \ScandiPWA\MenuOrganizer\Model\ResourceModel\Item\Collection $itemCollection,
        \ScandiPWA\MenuOrganizer\Helper\Adminhtml\Data $menumanagerHelper,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_registry = $registry;
        $this->_menumanagerHelper = $menumanagerHelper;
        parent::__construct($context, $backendHelper, $data);

        $this->_applyItemCollectionFilters($itemCollection);

        $this->setCollection($itemCollection);
    }

    /**
     * Initialize grid, set defaults
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('scandipwa_menuorganizer_item_grid');
        $this->setDefaultSort('item_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    /**
     * Create grid columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'title',
            [
                'header' => __('Item Title'),
                'type' => 'text',
                'index' => 'title',
                'escape' => true,
                'filter_index' => 'main_table.title'
            ]
        );

        $this->addColumn(
            'item_class',
            [
                'header' => __('Item CSS Class'),
                'type' => 'text',
                'index' => 'item_class',
                'escape' => true
            ]
        );

        $this->addColumn(
            'parent_title',
            [
                'header' => __('Parent Item'),
                'type' => 'text',
                'index' => 'parent_title',
                'escape' => true,
                'renderer' => '\ScandiPWA\MenuOrganizer\Block\Adminhtml\Item\Renderer\Parentitem',
                'filter' => false,
            ]
        );

        $this->addColumn(
            'url',
            [
                'header' => __('URL'),
                'type' => 'text',
                'index' => 'url',
                'escape' => true,
                'filter' => false,
                'sortable' => false,
            ]
        );

        $this->addColumn(
            'position',
            [
                'header' => __('Position'),
                'type' => 'number',
                'index' => 'position',
            ]
        );

        $this->addColumn(
            'is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'type' => 'options',
                'options' => $this->_menumanagerHelper->getAvailableStatuses()
            ]
        );

        $this->addColumn(
            'edit',
            [
                'header' => __('#'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/item/edit',
                            'params' => ['menu_id' => $this->getRequest()->getParam('menu_id')]
                        ],
                        'field' => 'item_id',
                        'class' => 'edit-menu-item',
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'item_id',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );

        $this->addColumn(
            'delete',
            [
                'header' => __('#'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Delete'),
                        'url' => [
                            'base' => '*/item/delete',
                            'params' => ['menu_id' => $this->getRequest()->getParam('menu_id')]
                        ],
                        'field' => 'item_id',
                        'confirm' => __('Do you really want to delete this item?'),
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'item_id',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Ajax grid URL getter
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/item/itemGrid', ['_current' => true]);
    }

    /**
     * Get current menu model
     *
     * @return \ScandiPWA\MenuOrganizer\Model\Menu
     */
    protected function _getMenu()
    {
        return $this->_registry->registry('scandipwa_menuorganizer_menu');
    }

    /**
     * @param \ScandiPWA\MenuOrganizer\Model\ResourceModel\Item\Collection $itemCollection
     */
    protected function _applyItemCollectionFilters($itemCollection)
    {
        $itemCollection->joinParentNames();
        $itemCollection->addFieldToFilter('main_table.menu_id', $this->_getMenu()->getId());
    }
}
