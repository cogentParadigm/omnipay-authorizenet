<?php

namespace Omnipay\AuthorizeNet\Message;

use Omnipay\Common\CreditCard;

/**
 * Authorize.Net AIM Authorize Request
 */
class AIMAuthorizeRequest extends AIMAbstractRequest
{
    protected $action = 'authOnlyTransaction';

    public function getData()
    {
        $this->validate('amount');
        $data = $this->getBaseData();
        $data->transactionRequest->amount = $this->getAmount();
        $this->addPayment($data);
        $this->addBillingData($data);
        $this->addCustomerIP($data);
        $this->addTransactionSettings($data);

        return $data;
    }

    protected function addPayment(\SimpleXMLElement $data)
    {
        /**
         * @link http://developer.authorize.net/api/reference/features/acceptjs.html Documentation on opaque data
         */
        if ($this->getOpaqueDataDescriptor() && $this->getOpaqueDataValue()) {
            $data->transactionRequest->payment->opaqueData->dataDescriptor = $this->getOpaqueDataDescriptor();
            $data->transactionRequest->payment->opaqueData->dataValue = $this->getOpaqueDataValue();
            return;
        }

        if ($bankAccount = $this->getBankAccount()) {
            $bankAccount->validate();
            $data->transactionRequest->payment->bankAccount->accountType = $this->getBankAccount()->getBankAccountType();
            $data->transactionRequest->payment->bankAccount->routingNumber = $this->getBankAccount()->getRoutingNumber();
            $data->transactionRequest->payment->bankAccount->accountNumber = $this->getBankAccount()->getAccountNumber();
            $data->transactionRequest->payment->bankAccount->nameOnAccount = $this->getBankAccount()->getName();
            $data->transactionRequest->payment->bankAccount->echeckType = "WEB";
        } else {
            $this->validate('card');
            $card = $this->getCard();
            $card->validate();
            $data->transactionRequest->payment->creditCard->cardNumber = $card->getNumber();
            $data->transactionRequest->payment->creditCard->expirationDate = $card->getExpiryDate('my');
            $data->transactionRequest->payment->creditCard->cardCode = $card->getCvv();
        }
    }

    protected function addCustomerIP(\SimpleXMLElement $data)
    {
        $ip = $this->getClientIp();
        if (!empty($ip)) {
            $data->transactionRequest->customerIP = $ip;
        }
    }
}
