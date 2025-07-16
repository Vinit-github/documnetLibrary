<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xlab\DocumentLibrary\Model\ResourceModel\File;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'file_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Xlab\DocumentLibrary\Model\File::class,
            \Xlab\DocumentLibrary\Model\ResourceModel\File::class
        );
    }
}

