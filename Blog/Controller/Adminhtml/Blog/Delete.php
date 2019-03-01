<?php

namespace Dung\Blog\Controller\Adminhtml\Blog;


use Dung\Blog\Api\BlogRepositoryInterface;
use Dung\Blog\Model\Blog;
use Dung\Blog\Api\Data\BlogInterfaceFactory;
//use Dungbv\Banner\Model\Banner;
use Magento\Backend\App\Action;

/**
 * Class Delete
 * @package Dung\Blog\Controller\Adminhtml\Blog
 */
class Delete extends Action
{
    protected $blog;
    protected $repoBlog;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param BlogInterfaceFactory $blog
     * @param BlogRepositoryInterface $repoBlog
     */
    public function __construct(Action\Context $context, BlogInterfaceFactory $blog, BlogRepositoryInterface $repoBlog)
    {
        $this->blog     = $blog;
        $this->repoBlog = $repoBlog;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->repoBlog->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the block.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
            return $this->_redirect(Blog::URI_PATH_INDEX);
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a block to delete.'));
        // go to grid
        return $this->_redirect(Blog::URI_PATH_INDEX);
    }
}