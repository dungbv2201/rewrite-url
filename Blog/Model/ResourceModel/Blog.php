<?php
/**
 * Created by PhpStorm.
 * User: vandung
 * Date: 27/02/2019
 * Time: 11:43
 */

namespace Dung\Blog\Model\ResourceModel;


use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;


/**
 * Class Blog
 * @package Dungbv\Blog\Model\ResourceModel
 */
class Blog extends AbstractDb
{
    protected $directoryList;
    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context,DirectoryList $directoryList, string $connectionName = null)
    {
        $this->directoryList = $directoryList;
        parent::__construct($context, $connectionName);
    }

    public function _construct()
    {
        $this->_init('blog', 'id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $start_date = $object->getData('start_date');
        $end_date   = $object->getData('end_date');
        if(strtotime($start_date) > strtotime($end_date)){
            throw new LocalizedException(__('Trường ngày bắt đầu phải nhỏ hơn trường ngày kết thúc'));
        }
        return $this;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return AbstractDb|void
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $image = $object->getData('image');
        if ($image != null) {
            $imageUpload = ObjectManager::getInstance()->create('Dung\Virtual\Blog\Model\ImageUploader');
            $imageUpload->moveFileFromTmp($image);
        }
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return AbstractDb
     */
    protected function _afterDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $fileImagePath = $this->directoryList->getPath('media') . '/blog/images/'.$object->getData('image');
//        $path_file = 'pub/media/'.\Dungbv\Banner\Model\Banner::BASE_PATH_IMAGE. $object->getData('image');
        if(file_exists($fileImagePath)){
            unlink($fileImagePath);
        }
        return parent::_afterDelete($object);
    }

}