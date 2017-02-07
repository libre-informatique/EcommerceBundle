<?php

namespace Librinfo\EcommerceBundle\Entity\OuterExtension\LibrinfoCRMBundle;

use Sylius\Component\Customer\Model\CustomerInterface;

trait AddressExtension
{
    /**
     * @var string
     */
    protected $phoneNumber;

    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var string
     */
    protected $provinceCode;

    /**
     * @var string
     */
    protected $provinceName;
    
    /**
     * @var CustomerInterface
     */
    protected $customer;

    /**
     * {@inheritdoc}
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomer(CustomerInterface $customer = null)
    {
        $this->customer = $customer;
    }

    
    /**
     * {@inheritdoc}
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * {@inheritdoc}
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCountryCode($countryCode = null)
    {
        if (null === $countryCode) {
            $this->provinceCode = null;
        }

        $this->countryCode = $countryCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getProvinceCode()
    {
        return $this->provinceCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setProvinceCode($provinceCode = null)
    {
        if (null === $this->countryCode) {
            return;
        }

        $this->provinceCode = $provinceCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getProvinceName()
    {
        return $this->provinceName;
    }

    /**
     * {@inheritdoc}
     */
    public function setProvinceName($provinceName = null)
    {
        $this->provinceName = $provinceName;
    }
    
    /**
     * @return string
     */
    public function getFullName()
    {
        return sprintf(
            '%s %s',
            $this->firstName,
            $this->lastName
        );
    }
}
