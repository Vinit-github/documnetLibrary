<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xlab\DocumentLibrary\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface FileRepositoryInterface
{

    /**
     * Save File
     * @param \Xlab\DocumentLibrary\Api\Data\FileInterface $file
     * @return \Xlab\DocumentLibrary\Api\Data\FileInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Xlab\DocumentLibrary\Api\Data\FileInterface $file
    );

    /**
     * Retrieve File
     * @param string $fileId
     * @return \Xlab\DocumentLibrary\Api\Data\FileInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($fileId);

    /**
     * Retrieve File matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Xlab\DocumentLibrary\Api\Data\FileSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete File
     * @param \Xlab\DocumentLibrary\Api\Data\FileInterface $file
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Xlab\DocumentLibrary\Api\Data\FileInterface $file
    );

    /**
     * Delete File by ID
     * @param string $fileId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($fileId);
}

