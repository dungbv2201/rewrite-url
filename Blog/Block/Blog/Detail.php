<?php
/**
 * Created by PhpStorm.
 * User: vandung
 * Date: 28/02/2019
 * Time: 13:38
 */

namespace Dung\Blog\Block\Blog;


use Dung\Blog\Model\BlogRepository;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;

/**
 * Class Detail
 * @package Dung\Blog\Block\Blog
 */
class Detail extends Template
{
    protected $blogRepo;

    public function __construct(Template\Context $context, BlogRepository $blogRepository, array $data = [])
    {
        $this->blogRepo = $blogRepository;
        parent::__construct($context, $data);
    }


    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBlog()
    {
        $id = $this->_request->getParam('id');
        return $this->blogRepo->getById($id);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUrlImage(){
        return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA).'blog/images/';
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreIdCurrent(){
        return $this->_storeManager->getStore()->getId();
    }
}