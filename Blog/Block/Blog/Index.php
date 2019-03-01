<?php
/**
 * Created by PhpStorm.
 * User: vandung
 * Date: 28/02/2019
 * Time: 11:07
 */

namespace Dung\Blog\Block\Blog;

use Dung\Blog\Model\ResourceModel\Blog\CollectionFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;

/**
 * Class Index
 * @package Dung\Blog\Block\Blog
 */
class Index extends Template
{
    protected $collection;

    /**
     * Index constructor.
     * @param Template\Context $context
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                CollectionFactory $collectionFactory )
    {
        $this->collection = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @return \Dung\Blog\Model\ResourceModel\Blog\Collection
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBlog(){
        $storeIdCurrent= $this->_storeManager->getStore()->getId();
        $blogs = $this->collection->create();
        $blogs->addFieldToSelect(['id','description','image','content','title','store_id','slug'])
                ->addFieldToFilter('store_id',['eq'=>$storeIdCurrent])
                ->setCurPage($this->getCurrentPage())
                ->setPageSize(1)
                ->getData();
        return $blogs;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUrlImage(){
        return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA).'blog/images/';
    }

    /**
     * @return int|mixed
     */
    public function getCurrentPage(){
        $request = $this->_request;
        if($request->getParam('p')){
            $page = $request->getParam('p');
        }else{
            $page = 1;
        }
        return $page;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPager(){
        $page = $this->getChildBlock('blogdungbv_list_pager');
        $collection = $this->getBlog();
        $page->setAvailableLimit([1=>1]);
        $page->setTotalNum($collection->getSize());
        $page->setCollection($collection);
        $page->setShowPerPage(TRUE);
        return $page->toHtml();
    }


}