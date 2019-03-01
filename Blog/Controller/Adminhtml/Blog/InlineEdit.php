<?php

namespace Dung\Blog\Controller\Adminhtml\Blog;


use Dung\Blog\Api\Data\BlogInterface;
use Dung\Blog\Model\BlogFactory;
use Dung\Blog\Model\BlogRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class InlineEdit
 * @package Dung\Blog\Controller\Adminhtml\Blog
 */
class InlineEdit extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
//    const ADMIN_RESOURCE = 'Dungbv_Banner::banner_manager';


    protected $blogRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    protected $blog;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param BlogRepository $bannerRepository
     * @param BlogFactory $banner
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        BlogRepository $bannerRepository,
        BlogFactory $blog,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->blogRepository = $bannerRepository;
        $this->jsonFactory = $jsonFactory;
        $this->blog = $blog->create();
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        $postItems = $this->getRequest()->getParam('items', []);
        if ($this->getRequest()->getParam('isAjax')) {
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach ($postItems as $key=>$value) {
                    try {
                        $data = $this->blogRepository->getById($key);
                        $data->setData($value);
                        $this->blogRepository->save($data);
                    } catch (\Exception $e) {
                        $messages[] = 'không thể cập nhật blog';
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add block title to error message
     *
     * @param BannerInterface $block
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithBannerkId(BlogInterface $blog, $errorText)
    {
        return '[Banner ID: ' . $blog->getId() . '] ' . $errorText;
    }
}