<?php
namespace Omnipay\AuthorizeNet;

use Omnipay\Common\CreditCard;

class BankAccount extends CreditCard {
    const ACCOUNT_TYPE_CHECKING = "checking";
    const ACCOUNT_TYPE_BUSINESS_CHECKING = "businessChecking";
    const ACCOUNT_TYPE_SAVINGS = "savings";

    /**
     * All known/supported bank account types, and a regular expression to match them.
     *
     *
     * @return array
     */
    public function getSupportedAccountType()
    {
        return array(
            static::ACCOUNT_TYPE_CHECKING,
            static::ACCOUNT_TYPE_BUSINESS_CHECKING,
            static::ACCOUNT_TYPE_SAVINGS
        );
    }

    /**
     * Validate this bank account. If the bank account is invalid, InvalidArgumentException is thrown.
     *
     */
    public function validate()
    {
        if (!in_array($this->getBankAccountType(), $this->getSupportedAccountType())) {
            throw new \InvalidArgumentException('The bank account type is not in the supported list.');
        }
    }

    public function getAccountNumber()
    {
        return $this->getParameter('accountNumber');
    }
    public function setAccountNumber($value)
    {
        // strip non-numeric characters
        return $this->setParameter('accountNumber', preg_replace('/\D/', '', $value));
    }
    public function getRoutingNumber()
    {
        return $this->getParameter('routingNumber');
    }
    public function setRoutingNumber($value)
    {
        // strip non-numeric characters
        return $this->setParameter('routingNumber', preg_replace('/\D/', '', $value));
    }
    public function getBankAccountType()
    {
        return $this->getParameter('bankAccountType');
    }
    public function setBankAccountType($value)
    {
        return $this->setParameter('bankAccountType', $value);
    }
    public function getBankName()
    {
        return $this->getParameter('bankName');
    }
    public function setBankName($value)
    {
        return $this->setParameter('bankName', $value);
    }
}