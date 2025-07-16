<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xlab\DocumentLibrary\Model;

use Xlab\DocumentLibrary\Api\Data\FileInterface;
use Xlab\DocumentLibrary\Api\Data\FileInterfaceFactory;
use Xlab\DocumentLibrary\Api\Data\FileSearchResultsInterfaceFactory;
use Xlab\DocumentLibrary\Api\FileRepositoryInterface;
use Xlab\DocumentLibrary\Model\ResourceModel\File as ResourceFile;
use Xlab\DocumentLibrary\Model\ResourceModel\File\CollectionFactory as FileCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class FileRepository implements FileRepositoryInterface
{

    /**
     * @var ResourceFile
     */
    protected $resource;

    /**
     * @var FileInterfaceFactory
     */
    protected $fileFactory;

    /**
     * @var FileCollectionFactory
     */
    protected $fileCollectionFactory;

    /**
     * @var File
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;


    /**
     * @param ResourceFile $resource
     * @param FileInterfaceFactory $fileFactory
     * @param FileCollectionFactory $fileCollectionFactory
     * @param FileSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceFile $resource,
        FileInterfaceFactory $fileFactory,
        FileCollectionFactory $fileCollectionFactory,
        FileSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->fileFactory = $fileFactory;
        $this->fileCollectionFactory = $fileCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(FileInterface $file)
    {
        try {
            $this->resource->save($file);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the file: %1',
                $exception->getMessage()
            ));
        }
        return $file;
    }

    /**
     * @inheritDoc
     */
    public function get($fileId)
    {
        $file = $this->fileFactory->create();
        $this->resource->load($file, $fileId);
        if (!$file->getId()) {
            throw new NoSuchEntityException(__('File with id "%1" does not exist.', $fileId));
        }
        return $file;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->fileCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(FileInterface $file)
    {
        try {
            $fileModel = $this->fileFactory->create();
            $this->resource->load($fileModel, $file->getFileId());
            $this->resource->delete($fileModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the File: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($fileId)
    {
        return $this->delete($this->get($fileId));
    }
}

