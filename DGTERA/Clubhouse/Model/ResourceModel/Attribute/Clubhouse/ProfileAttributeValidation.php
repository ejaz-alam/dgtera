<?php
declare(strict_types=1);


namespace DGTERA\Clubhouse\Model\ResourceModel\Attribute\Clubhouse;


use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class ProfileAttributeValidation extends AbstractBackend
{
    private $minLength = 1;
    private $maLength = 16;

    /**
     * @param DataObject $object
     * @return ProfileAttributeValidation
     * @throws LocalizedException
     */
    public function beforeSave($object)
    {
        $this->lenghtValidate($object);

        return parent::beforeSave($object);
    }

    /**
     * @param $object
     * @return bool
     * @throws LocalizedException
     */
    public function lenghtValidate($object)
    {
        $attribute = $this->getAttribute();
        $attributeCode = $attribute->getAttributeCode();

        $value = strlen((string)$object->getData($attributeCode));

        if (($this->getAttribute()->getIsRequired() && $value < $this->minLength) || $value > $this->maLength) {
            throw new LocalizedException(
                __('The value of attribute "%1" must be greater/equals to %2 or less than %3',
                    $attributeCode,
                    $this->minLength,
                    $this->maLength
                )
            );
        }

        return true;
    }
}
