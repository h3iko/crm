<?php

namespace Oro\Bundle\MarketingListBundle\Twig;

use Oro\Bundle\MarketingListBundle\Model\ContactInformationFieldHelper;

class ContactInformationFieldsExtension extends \Twig_Extension
{
    const NAME = 'oro_marketing_list_contact_information_fields';

    /**
     * @var ContactInformationFieldHelper
     */
    protected $helper;

    /**
     * @param ContactInformationFieldHelper $helper
     */
    public function __construct(ContactInformationFieldHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'get_contact_information_fields_info',
                array($this, 'getContactInformationFieldsInfo')
            )
        );
    }

    /**
     * @param string $entityClass
     * @return array
     */
    public function getContactInformationFieldsInfo($entityClass)
    {
        if (!$entityClass) {
            return array();
        }

        return $this->helper->getEntityContactInformationFieldsInfo($entityClass);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }
}
