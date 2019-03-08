<?php
/**
 * @category    Scandiweb
 * @package     ScandiPWA\MenuOrganizer
 * @author      Peteris Skrebis <info@scandiweb.com>
 * @copyright   Copyright (c) 2018 Scandiweb, Inc (https://scandiweb.com)
 * @license     http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

namespace ScandiPWA\MenuOrganizer\Helper\Adminhtml;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\MediaStorage\Model\File\UploaderFactory;

class Icon extends AbstractHelper
{
    const ICON_DIR = 'scandipwa_menuorganizer_item_icons';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var WriteInterface
     */
    protected $mediaDirectory;

    /**
     * @var UploaderFactory
     */
    protected $uploadFactory;

    /**
     * Initialize dependencies
     *
     * @param Context $context
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory
    ) {
        $this->filesystem = $filesystem;
        $this->mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploadFactory = $uploaderFactory;
        parent::__construct($context);
    }

    /**
     * Upload icon
     *
     * @param string $fileId
     * @return null|string
     * @throws \Exception
     */
    public function upload($fileId)
    {
        $filePathInMedia = null;

        try {
            $result = $this->uploadFactory->create(['fileId' => $fileId])
                ->setAllowedExtensions(['png', 'jpg', 'jpeg', 'gif', 'svg'])
                ->setAllowRenameFiles(true)
                ->save($this->mediaDirectory->getAbsolutePath(static::ICON_DIR));

            $filePathInMedia = static::ICON_DIR . DIRECTORY_SEPARATOR . $result['file'];

        } catch (\Exception $e) {
            if ($e->getCode() != Uploader::TMP_NAME_EMPTY) {
                throw new \Exception($e->getMessage(), $e->getCode());
            }
        }

        return $filePathInMedia;
    }
}
