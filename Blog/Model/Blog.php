<?php
/**
 * Created by PhpStorm.
 * User: vandung
 * Date: 27/02/2019
 * Time: 11:41
 */

namespace Dung\Blog\Model;


use Dung\Blog\Api\Data\BlogInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Blog
 * @package Dung\Blog\Model
 */
class Blog extends AbstractModel implements BlogInterface
{
    public function _construct()
    {
         $this->_init(\Dung\Blog\Model\ResourceModel\Blog::class);
    }
}