<?php
namespace Dung\Blog\Controller;
/**
 * Class Route
 * @package Dung\Blog\Controller
 */
class Router implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * Router constructor.
     *
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     */
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory
    )
    {
        $this->actionFactory = $actionFactory;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool|\Magento\Framework\App\ActionInterface
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        return;
        $identifier = trim($request->getPathInfo(), '/');
        $d          = explode('/', $identifier);

        if (isset($d[0]) && ($d[0] !== 'blog')) {
            return false;
        }
        if (substr($identifier, -5) !== '.html') {
            return false;
        }

        $paramStr = '';
        if (isset($d[1])) {
            $paramStr = $d[1];
        }
        $params = [];

        if ($paramStr) {
            $array = explode('-',$paramStr);
            $array2 = explode('-',$paramStr);
            $id =explode('.',array_pop($array))[0];
            array_pop($array2);
            $slug= implode('-',$array2);
        }
        $params = ['slug' => $slug, 'id' => $id];

        $request->setModuleName('blog')->setControllerName('index')->setActionName('detail');
        if (count($params)) {
            $request->setParams($params);
        }

        $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);

        return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
    }
}