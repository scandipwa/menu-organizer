<?php
namespace ScandiPWA\MenuOrganizer\Block\Adminhtml\Item;

use Magento\Backend\Block\Widget\Form\Container;

/**
 * @category ScandiPWA
 * @package ScandiPWA\MenuOrganizer\Block\Adminhtml\Item
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Edit
 */
class Edit extends Container
{
    const TOOLBAR_REGION_BUTTONS = 'toolbar';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize menu edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'item_id';
        $this->_blockGroup = 'ScandiPWA_MenuOrganizer';
        $this->_controller = 'adminhtml_item';

        $saveUrl = $this->getUrl('*/item/save', ['_current' => true, 'active_tab' => 'item_section']);
        $this->setFormActionUrl($saveUrl);

        parent::_construct();

        $this->removeToolbarButtons();
    }

    /**
     * Retrieve text for header element depending on loaded menu
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $menu = $this->_coreRegistry->registry('scandipwa_menuorganizer_item');

        if ($menu->getId()) {
            return __("Edit Menu Item '%1'", $this->escapeHtml($menu->getTitle()));
        } else {
            return __('Add Menu Item');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('menu/*/save', ['_current' => true]);
    }

    /**
     * Remove unnecessary toolbar buttons
     */
    private function removeToolbarButtons()
    {
        foreach ($this->buttonList->getItems() as $buttonListItem) {
            foreach ($buttonListItem as $button) {
                if ($button->getRegion() === self::TOOLBAR_REGION_BUTTONS) {
                    $this->buttonList->remove($button->getId());
                }
            }
        }
    }
}
