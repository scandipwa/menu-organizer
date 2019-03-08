<?php

namespace ScandiPWA\MenuOrganizer\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\TestFramework\ErrorLog\Logger;
use ScandiPWA\MenuOrganizer\Helper\Adminhtml\Icon;

/**
 * @category ScandiPWA
 * @package ScandiPWA\MenuOrganizer\Controller\Adminhtml\Menu
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Save
 */
class Save extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'ScandiPWA_MenuOrganizer::navigation_menu_item_save';

    /**
     * @var Icon
     */
    private $iconUploadHelper;

    /**
     * Initialize dependency
     *
     * @param Context $context
     * @param Icon $iconUploadHelper
     */
    public function __construct(
        Context $context,
        Icon $iconUploadHelper
    ) {
        parent::__construct($context);
        $this->iconUploadHelper = $iconUploadHelper;
    }


    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            /** @var \ScandiPWA\MenuOrganizer\Model\Item $model */
            $model = $this->_objectManager->create('ScandiPWA\MenuOrganizer\Model\Item');

            if ($id = $this->getRequest()->getParam('item_id', false)) {
                $model->load($id);
            }

            $model->addData($data);

            $params = [
                'menu_id' => $model->getMenuId(),
            ];

            $this->_eventManager->dispatch(
                'scandipwa_menuorganizer_item_prepare_save',
                ['menu' => $model, 'request' => $this->getRequest()]
            );

            try {
                if ($uploadedIcon = $this->iconUploadHelper->upload('icon')) {
                    $model->setIcon($uploadedIcon);
                } else {
                    $icon = $model->getIcon();
                    if (is_array($icon)) {
                        $model->setIcon(array_key_exists('delete', $icon) && $icon['delete'] ? '' : ($icon['value'] ?? null));
                    }
                }

                $model->save();
                $this->messageManager->addSuccess(__('Menu item has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

                if ($activeTab = $this->getRequest()->getParam('active_tab')) {
                    $params['active_tab'] = $activeTab;
                }

                return $resultRedirect->setPath('*/menu/edit', $params);

            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving menu item.'));
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath('*/menu/edit', $params);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
