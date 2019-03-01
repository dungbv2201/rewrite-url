<?php

namespace Dung\Blog\Controller\Adminhtml\Blog;

use Dung\Blog\Api\BlogRepositoryInterface;
use Dung\Blog\Model\Blog;
use Dung\Blog\Model\BlogFactory;
use Dung\Blog\Model\ResourceModel\Blog\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;


class Save extends Action
{
    protected $blogModel;
    protected $collection;
    protected $_dataPersistor;
    protected $blogRepository;
    protected $_urlFolder = 'blog/images/';
    protected $_storeManager;


    public function __construct(
        BlogFactory $blogModel,
        StoreManagerInterface $storeManager,
        CollectionFactory $collectionFactory,
        BlogRepositoryInterface $blogRepository,
        DataPersistorInterface $dataPersistor,
        Action\Context $context)
    {
        $this->blogModel      = $blogModel;
        $this->_storeManager  = $storeManager;
        $this->blogRepository = $blogRepository;
        $this->collection     = $collectionFactory->create();
        $this->_dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * @param array $rawData
     * @return array
     */
    protected function _filterBlogPostData(array $rawData): array
    {
        $data = $rawData;
        if (isset($data['image']) && is_array($data['image'])) {
            if (!empty($data['image']['delete'])) {
                $data['image'] = null;
            } else {
                if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {
                    $data['image'] = $data['image'][0]['name'];
                } else {
                    unset($data['image']);
                }
            }
        }
        return $data;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            if (empty($data[Blog::Blog_ID])) {
                $data[Blog::Blog_ID] = null;
            }
            $model = $this->blogModel->create();
            $id    = $this->getRequest()->getParam(Blog::Blog_ID_RQ);
            if ($id) {
                try {
                    $model = $this->blogRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This blog no longer exists.'));
                    return $this->_redirect(Blog::URI_PATH_INDEX);
                }
            }
            $model->setData($this->_filterBlogPostData($data));
            try {
                $data =$this->blogRepository->save($model);

                $this->messageManager->addSuccessMessage(__('You saved the blog.'));
                $this->_dataPersistor->clear('blog');
                $this->_eventManager->dispatch(
                    'dung_blog_save_url_rewrite',
                    ['blog_detail'=>$data->getData()]
                );
                if ($this->getRequest()->getParam('back')) {
                    return $this->_redirect(Blog::URI_PATH_Edit, [Blog::Blog_ID_RQ => $model->getId()]);
                }
            } catch (LocalizedException $e) {
                $this->_dataPersistor->set('blog', $data);
                $this->messageManager->addErrorMessage($e->getMessage());
                if ($id) {
                    return $this->_redirect(Blog::URI_PATH_Edit, [Blog::Blog_ID_RQ => $id]);
                }
                return $this->_redirect(Blog::URI_PATH_ADD);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(__('Something went wrong while saving the blog.' . $e->getMessage()));
                $this->_dataPersistor->set('blog', $data);
                if ($id) {
                    return $this->_redirect(Blog::URI_PATH_Edit, [Blog::Blog_ID_RQ => $id]);
                }
                return $this->_redirect(Blog::URI_PATH_ADD);
            }
        }
        return $this->_redirect(Blog::URI_PATH_INDEX);
    }
}