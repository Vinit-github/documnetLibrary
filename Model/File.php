<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xlab\DocumentLibrary\Model;

use Xlab\DocumentLibrary\Api\Data\FileInterface;
use Magento\Framework\Model\AbstractModel;

class File extends AbstractModel implements FileInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Xlab\DocumentLibrary\Model\ResourceModel\File::class);
    }

    /**
     * @inheritDoc
     */
    public function getFileId()
    {
        return $this->getData(self::FILE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setFileId($fileId)
    {
        return $this->setData(self::FILE_ID, $fileId);
    }

    /**
     * @inheritDoc
     */
    public function getFileName()
    {
        return $this->getData(self::FILE_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setFileName($fileName)
    {
        return $this->setData(self::FILE_NAME, $fileName);
    }
}

