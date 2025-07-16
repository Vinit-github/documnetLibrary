<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xlab\DocumentLibrary\Block\List;

use Xlab\DocumentLibrary\Model\ResourceModel\File\CollectionFactory as FileCollectionFactory;
use Magento\Framework\Registry;
use Amasty\ProductAttachment\Model\Icon\GetIconForFile;

class Search extends \Magento\Framework\View\Element\Template
{   

    
    /**
     * @var FileCollectionFactory
     */
    protected $fileCollectionFactory;

    protected $storeManager;
    protected $getIconForFile;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        FileCollectionFactory $fileCollectionFactory,
        GetIconForFile $getIconForFile,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->fileCollectionFactory = $fileCollectionFactory;
        $this->storeManager = $storeManager;
        $this->getIconForFile = $getIconForFile;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }


    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Document Library'));
        if ($this->getDocuments()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'doc.search.pager'
            )->setAvailableLimit([20 => 20, 40 => 40, 60 => 60, 80 => 80])
                ->setShowPerPage(true)->setCollection(
                    $this->getDocuments()
                );
            $this->setChild('pager', $pager);
            $this->getDocuments()->load();
        }
        return $this;
    }

    public function getDocuments(){

        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 20;
		$data = $this->getRequest()->getParams();
        $storeId = $this->storeManager->getStore()->getId();
        $searchArray = array();
        if ($this->getRequest()->getParams()) {
            $searchArray = $this->getRequest()->getParams();
        }
        
        if ($storeId == 3) {
            $searchArray['manufacturer'] = 'Lancer'; // Sort by price descending
        }
		// echo "<pre>"; print_r($searchArray); echo "</pre>"; die();
        $fileCollection = $this->fileCollectionFactory->create();
        $fileCollection->addFieldToFilter('is_visible', ['eq' => 1]); 
        
        if(count($searchArray) > 0){
            foreach($searchArray as $key => $search){               
                // if($search != null && $search !='' && $key != 'isAjax'){
				    if (!in_array($key, ['p', 'limit', 'isAjax']) && !empty($search)) {
                    if($key == 'keyword'){ 
                            $fileCollection->addFieldToFilter(
                                array('filename','equipment_type','sku','model','manufacturer'),
                                array(
                                        array('like' => '%'.$search.'%'),
                                        array('like' => '%'.$search.'%'),
                                        array('like' => '%'.$search.'%'),
                                        array('like' => '%'.$search.'%'),
                                        array('like' => '%'.$search.'%'),
                                        array('like' => '%'.$search.'%')
                                    )             
                                );
                            
                    } elseif($key == 'equipment_type'){
                        $fileCollection->addFieldToFilter($key, ['like' => '%'.$search.'%']);
                    } else {
                        $fileCollection->addFieldToFilter($key, ['eq' => $search]); 
                    }                   
                 }
            }
        }        
        $fileCollection->setPageSize($pageSize);
        $fileCollection->setCurPage($page);
       
        if($fileCollection){
            /*$documents = [];
            foreach ($fileCollection as $file) {
                $documents[] = $file;
            }*/
            return $fileCollection;
        }
        return null;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }


    public function getManufactures(){
        $manufactures = $this->fileCollectionFactory->create();
        $manufactures->addFieldToSelect('manufacturer');
        $manufactures->distinct(true);
        $manufacturesArray = [];
        if($manufactures){
            foreach($manufactures as $manufacture){
                array_push($manufacturesArray,$manufacture['manufacturer']);
            }       
            return $manufacturesArray;
        }
        return null;
    }

    public function getEquipmentType(){
        $equipmentTypes = $this->fileCollectionFactory->create();
        $equipmentTypes->addFieldToSelect('equipment_type');
        $equipmentTypes->distinct(true);
        $equipmentTypessArray = [];
        if($equipmentTypes){
            foreach($equipmentTypes as $equipmentType){
                array_push($equipmentTypessArray,$equipmentType['equipment_type']);
            }       
            return $equipmentTypessArray;
        }
        return null;
    }

    public function getDocType(){
        $equipmentTypes = $this->fileCollectionFactory->create();
        $equipmentTypes->addFieldToSelect('attachment_type');
        $equipmentTypes->distinct(true);
        $equipmentTypessArray = [];
        if($equipmentTypes){
            foreach($equipmentTypes as $equipmentType){
                array_push($equipmentTypessArray,$equipmentType['attachment_type']);
            }       
            return $equipmentTypessArray;
        }
        return null;
    }

    public function getFileUrl($filePath){

        if($filePath){            
            return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $filePath;           
        }
        return null;
    }

    public function getFileIcon($extension,$attachemntType){
        return $this->getIconForFile->byFileExtension(
            (string)$extension,
            (int)$attachemntType
        ) ?: false;
    }
}

