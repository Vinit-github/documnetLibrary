<?php
namespace Xlab\DocumentLibrary\Controller\List;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;


class SearchResult extends Action
{
    protected $resultJsonFactory;  
    protected $_coreRegistry;
    
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory       
    ) {
        $this->resultJsonFactory = $resultJsonFactory; 
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;    
        parent::__construct($context);
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        if ($this->_coreRegistry->registry('search_params')) {
            $this->_coreRegistry->unregister('search_params');
        }
        $this->_coreRegistry->register('search_params', $params);

        $resultPage = $this->resultPageFactory->create();
        $block = $resultPage->getLayout()
            ->createBlock(\Xlab\DocumentLibrary\Block\List\Search::class)
            ->setData('ajax_data', true)
            ->setData('search_fields', $params)
            ->setTemplate("Xlab_DocumentLibrary::list/search-list.phtml");
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $data = ['content' => $block->toHtml()];      
        $resultJson->setData($data);
        return $resultJson;
    }
}
