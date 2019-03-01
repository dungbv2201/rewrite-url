<?php
/**
 * Created by PhpStorm.
 * User: vandung
 * Date: 01/03/2019
 * Time: 13:48
 */

namespace Dung\Blog\Observer;


use Magento\Framework\UrlInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\UrlRewrite\Model\UrlRewriteFactory;

/**
 * Class BlogUrlRewrite
 * @package Dung\Blog\Observer
 */
class BlogUrlRewrite implements ObserverInterface
{
    protected $urlRewriteFactory;
    protected $url;

    /**
     * BlogUrlRewrite constructor.
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param UrlInterface $url
     */
    public function __construct(UrlRewriteFactory $urlRewriteFactory, UrlInterface $url)
    {
        $this->url               = $url;
        $this->urlRewriteFactory = $urlRewriteFactory;
    }

    /**
     * @param Observer $observer
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {

        $dataUrlRewrite  = $observer->getData('blog_detail');

        $urlRewrite = 'blog/'.$dataUrlRewrite['slug'].'-'.$dataUrlRewrite['id'].'.html';

        $urlBaseAction = 'blog/index/detail?id='.$dataUrlRewrite['id'].'&slug='.$dataUrlRewrite['slug'];
        $urlRewriteModel = $this->urlRewriteFactory->create();
        /* set current store id */
        $urlRewriteModel->setStoreId($dataUrlRewrite['store_id']);
        /* this url is not created by system so set as 0 */
        $urlRewriteModel->setIsSystem(0);
        /* unique identifier - set random unique value to id path */
        $urlRewriteModel->setEntityType('dung-blog');
        /* set actual url path to target path field */
        $urlRewriteModel->setTargetPath($urlBaseAction);
        $urlRewriteModel->setMetadata(['id'=>$dataUrlRewrite['id'],'slug'=>$dataUrlRewrite['slug']]);
        /* set requested path which you want to create */
        $urlRewriteModel->setRequestPath($urlRewrite);
        /* set current store id */
        $urlRewriteModel->save();
    }
}