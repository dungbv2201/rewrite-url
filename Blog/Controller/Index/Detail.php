<?php
/**
 * Created by PhpStorm.
 * User: vandung
 * Date: 28/02/2019
 * Time: 12:59
 */

namespace Dung\Blog\Controller\Index;


use Dung\Blog\Model\BlogFactory;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;


/**
 * Class Detail
 * @package Dung\Blog\Controller\Index
 */
class Detail extends Action
{
    protected $page;
    protected $blog;

    /**
     * Detail constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Context $context, PageFactory $pageFactory, BlogFactory $blogFactory)
    {
        $this->page = $pageFactory;
        $this->blog = $blogFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $params  = $this->_request->getParams();
        $blog = $this->blog->create()->load($params['id']);

        if (!$blog->getId() || $blog->getData('slug') !== $params['slug']) {
            return $this->_redirect('no-route');
        }
        $resultPage =$this->page->create();
        $resultPage->getConfig()->setMetaTitle($blog->getData('title'));
        $resultPage->getConfig()->setDescription($blog->getData('description'));
        return $resultPage;
    }
}