<?php
namespace ScandiPWA\MenuOrganizer\Controller\Adminhtml\Menu;

/**
 * @category ScandiPWA
 * @package ScandiPWA\MenuOrganizer\Controller\Adminhtml\Menu
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class AbstractMassAction
 */
abstract class AbstractMassAction extends \Magento\Backend\App\Action
{
    /**
     * Field id
     */
    const ID_FIELD = 'menu_id';

    /**
     * Redirect url
     */
    const REDIRECT_URL = '*/*/';

    /**
     * Resource collection
     *
     * @var string
     */
    protected $collection = 'ScandiPWA\MenuOrganizer\Model\ResourceModel\Menu\Collection';

    /**
     * Page model
     *
     * @var string
     */
    protected $model = 'ScandiPWA\MenuOrganizer\Model\Menu';

    /**
     * Set error messages
     *
     * @param int $count
     * @return void
     */
    protected function setSuccessMessage($count)
    {
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been updated.', $count));
    }
}
