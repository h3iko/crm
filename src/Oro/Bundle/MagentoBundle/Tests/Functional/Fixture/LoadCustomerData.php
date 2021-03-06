<?php

namespace Oro\Bundle\MagentoBundle\Tests\Functional\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\UserBundle\Model\Gender;
use Oro\Bundle\MagentoBundle\Entity\Customer;

class LoadCustomerData extends AbstractFixture implements ContainerAwareInterface, DependentFixtureInterface
{
    /** @var  ObjectManager */
    protected $em;

    /** @var  ContainerInterface */
    protected $container;

    /** @var  User */
    protected $user;

    /** @var  Organization */
    protected $organization;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getDependencies()
    {
        return [
            'Oro\Bundle\MagentoBundle\Tests\Functional\Fixture\LoadMagentoChannel'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->em = $manager;

        $this->user         = $manager->getRepository('OroUserBundle:User')->findOneBy(['username' => 'admin']);
        $this->organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();

        $this->createCustomer('Richard', 'Bradley');
        $this->createCustomer('Brenda', 'Brock');
        $this->createCustomer('Shawn', 'Bryson');

        $this->em->flush();
    }


    /**
     * @param $firstName
     * @param $lastname
     */
    protected function createCustomer($firstName, $lastname)
    {
        $customer = new Customer();
        $customer->setChannel($this->getReference('integration'));
        $customer->setDataChannel($this->getReference('default_channel'));
        $customer->setFirstName($firstName);
        $customer->setLastName($lastname);
        $customer->setEmail(strtolower($firstName . '_' . $lastname .'@example.com'));
        $customer->setIsActive(true);
        $customer->setWebsite($this->getReference('website'));
        $customer->setStore($this->getReference('store'));
        $customer->setAccount($this->getReference('account'));
        $customer->setGender(Gender::MALE);
        $customer->setGroup($this->getReference('customer_group'));
        $customer->setCreatedAt(new \DateTime('now'));
        $customer->setUpdatedAt(new \DateTime('now'));
        $customer->setOwner($this->user);
        $customer->setOrganization($this->organization);

        $this->em->persist($customer);
    }
}
