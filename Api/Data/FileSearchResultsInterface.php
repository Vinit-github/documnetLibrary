<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xlab\DocumentLibrary\Api\Data;

interface FileSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get File list.
     * @return \Xlab\DocumentLibrary\Api\Data\FileInterface[]
     */
    public function getItems();

    /**
     * Set File_name list.
     * @param \Xlab\DocumentLibrary\Api\Data\FileInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

