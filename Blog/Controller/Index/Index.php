<?php
/**
 * Created by PhpStorm.
 * User: vandung
 * Date: 27/02/2019
 * Time: 11:09
 */

namespace Dung\Blog\Controller\Index;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Dung\Blog\Controller\Index
 */
class Index extends Action
{
    protected $pageResult;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Context $context,PageFactory $pageFactory)
    {
        $this->pageResult = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        return $this->pageResult->create();
    }
}