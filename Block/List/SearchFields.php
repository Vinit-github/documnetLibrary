<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xlab\DocumentLibrary\Block\List;

use Xlab\DocumentLibrary\Model\ResourceModel\File\CollectionFactory as FileCollectionFactory;
use Magento\Framework\Registry;

class SearchFields extends \Magento\Framework\View\Element\Template
{   

    
    /**
     * @var FileCollectionFactory
     */
    protected $fileCollectionFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        FileCollectionFactory $fileCollectionFactory,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->fileCollectionFactory = $fileCollectionFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
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
            return array_filter(array_unique($manufacturesArray));
        }
        return null;
    }

    public function getEquipmentType(){
        $equipmentTypes = $this->fileCollectionFactory->create();
        $equipmentTypes->addFieldToSelect('equipment_type');
        $equipmentTypes->distinct(true);
        $equipmentTypessArray = [];
        $string ='';
        if($equipmentTypes){
            foreach($equipmentTypes as $equipmentType){
                if($string != ''){
                    $string .= ',';
                }
                $string .= $equipmentType['equipment_type'];                
            }     
            $equipmentTypessArray = array_filter(array_unique((explode(",",(string)$string))));
            
            return $equipmentTypessArray;
        }
        return null;
    }

    public function getDocType(){
        $equipmentTypes = $this->fileCollectionFactory->create();
        $equipmentTypes->addFieldToSelect('label');
        $equipmentTypes->distinct(true);
        $equipmentTypessArray = [];
        if($equipmentTypes){
            foreach($equipmentTypes as $equipmentType){
                array_push($equipmentTypessArray,$equipmentType['label']);
            }       
            return $equipmentTypessArray;
        }
        return null;
    }
}

