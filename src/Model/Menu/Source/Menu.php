<?php
/**
 * @category ScandiPWA
 * @package ScandiPWA\Customization
 * @author Alfreds Genkins <info@scandiweb.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 */

namespace ScandiPWA\MenuOrganizer\Model\Menu\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use ScandiPWA\MenuOrganizer\Api\Data\MenuInterface;
use ScandiPWA\MenuOrganizer\Model\ResourceModel\Menu\CollectionFactory;

class Menu extends AbstractSource
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Menu constructor.
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $menus = $this->collectionFactory
                ->create()
                ->addFieldToFilter(MenuInterface::IS_ACTIVE, ['eq' => 1])
                ->load()
                ->getItems();

            /** @var MenuInterface $menu */
            foreach ($menus as $menu) {
                $this->_options[] = [
                    'value' => $menu->getIdentifier(),
                    'label' => $menu->getTitle()
                ];
            }

            if (is_array($this->_options)) {
                array_unshift($this->_options, ['value' => '', 'label' => __('Please select a menu.')]);
            } else {
                $this->_options = ['value' => '', 'label' => __('No menus to select.')];
            }
        }

        return $this->_options;
    }
}
