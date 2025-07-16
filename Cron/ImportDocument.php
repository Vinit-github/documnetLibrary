<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);
namespace Xlab\DocumentLibrary\Cron;

use Amasty\ProductAttachment\Model\File\ResourceModel\CollectionFactory;
use Amasty\ProductAttachment\Model\File\FileScope\ResourceModel\FileStoreProduct;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\ProductFactory;
use Xlab\DocumentLibrary\Model\File;
use Xlab\DocumentLibrary\Model\ResourceModel\File as ResourceFile;
use Xlab\DocumentLibrary\Model\ResourceModel\File\CollectionFactory as FileCollectionFactory;
use Magento\Framework\App\ResourceConnection;

class ImportDocument
{
    /**
     * @var \Amasty\ProductAttachment\Model\File\ResourceModel\Collection
     */
    protected $collection;
    protected $fileStoreProduct;
    protected $productFactory;
    protected $categoryRepository;
    protected $file;
    protected $resource;

    /**
     * @var FileCollectionFactory
     */
    protected $fileCollectionFactory;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        FileStoreProduct $fileStoreProduct,
        ProductFactory $productFactory,
        FileCollectionFactory $fileCollectionFactory,
        CategoryRepository $categoryRepository,
        File $file,
        ResourceConnection $resource
    ) {
        $this->collection = $collectionFactory;
        $this->fileStoreProduct = $fileStoreProduct;
        $this->productFactory = $productFactory;
        $this->categoryRepository = $categoryRepository;
        $this->fileCollectionFactory = $fileCollectionFactory;
        $this->file = $file;
        $this->resource = $resource;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $connection = $this->resource->getConnection();
        $tableName = $this->resource->getTableName("xlab_documentlibrary_file");
        $connection->truncateTable($tableName);

        $docCollection = $this->collection->create();
        $docCollection->addFieldToFilter("extension", ["eq" => "pdf"]);
        $docCollection->addFileData();
        $newFileObject = $this->file;

        foreach ($docCollection as $doc) {
            $productIds = $this->fileStoreProduct->getStoreProductIdsByStoreId($doc->getFileId(),0);
            if (count($productIds) > 0) {
                foreach ($productIds as $id) {
                    $product = $this->productFactory->create()->load($id["product_id"]);
                    $productModel = "";
                    $equipmentType = "";
                    $brand = "";                  
                    if ($product->getId()) {
                        if ($product->getAttributeText("brand")) {
                            
                            $brand = $product->getAttributeText("brand");
                        }

                        if ($product->getAttributeText("model")) {
                            $productModel = $product->getAttributeText("model");
                        }
                        $equipmentType = $this->getParentCategory($product);                      
                    }
                    /* Create New File */                   
                    $newFileObject->setFilepath($doc->getFilepath()); 
                    $newFileObject->setUrlHash($doc->getUrlHash());
                    $newFileObject->setLabel($doc->getLabel());
                    $newFileObject->setFilename($doc->getFilename());
                   //$newFileObject->setFilepath($doc->getFilepath());
                   $newFileObject->setUrlHash($doc->getUrlHash());
                   $newFileObject->setLabel($doc->getLabel());
                   $newFileObject->setFilename($doc->getFilename());
                   $newFileObject->setData('filename',$doc->getFilename());
                   $newFileObject->setIsVisible($doc->getIsVisible());
                   $newFileObject->setAttachmentType($doc->getAttachmentType());
                  // $newFileObject->setExtension($doc->getExtension());
                   $newFileObject->setData('extension',$doc->getExtension());

                   $newFileObject->setModel($productModel);                   
                   $newFileObject->setSku($product->getSku());
                   $newFileObject->setProductId($product->getId());
                   $newFileObject->setManufacturer($brand);
                   $newFileObject->setData('equipment_type', $equipmentType);
                   $newFileObject->setFileId(null);
                   $newFileObject->setAmastyFileId($doc->getFileId());
                   $newFileObject->save();
                }
            } 
        }
    }

    protected function getParentCategory($product)
    {
        $equipmentType = "";
        $eArray = array();
        if ($product) {
            foreach ($product->getCategoryIds() as $categoryId) {
                $category = $this->categoryRepository->get($categoryId);
                $parentCategory = $category->getParentCategory();             
                if ($parentCategory->getLevel() == 3 ) {                  
                    array_push($eArray,$parentCategory->getName());
                }
                if ($category->getLevel() == 3 ) {                   
                    array_push($eArray,$category->getName());
                }
            }
            if(count($eArray) > 0){
                array_unique($eArray);
                $key = array_search('All Products',$eArray);
                unset($eArray[$key]);
                $equipmentType = implode(',',$eArray);
            }
        }
        return $equipmentType;
    }
}
