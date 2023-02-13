<?php

namespace DoctrineProxies\__CG__\App\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class OrderCharge extends \App\Entities\OrderCharge implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array<string, null> properties to be lazy loaded, indexed by property name
     */
    public static $lazyPropertiesNames = array (
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', '' . "\0" . 'App\\Entities\\OrderCharge' . "\0" . 'order'];
        }

        return ['__isInitialized__', '' . "\0" . 'App\\Entities\\OrderCharge' . "\0" . 'order'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (OrderCharge $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function setOrder(\App\Entities\Order $order)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOrder', [$order]);

        return parent::setOrder($order);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOrder', []);

        return parent::getOrder();
    }

    /**
     * {@inheritDoc}
     */
    public function getSupplier()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSupplier', []);

        return parent::getSupplier();
    }

    /**
     * {@inheritDoc}
     */
    public function getHzYear()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHzYear', []);

        return parent::getHzYear();
    }

    /**
     * {@inheritDoc}
     */
    public function getHzEntry()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHzEntry', []);

        return parent::getHzEntry();
    }

    /**
     * {@inheritDoc}
     */
    public function getHzCode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHzCode', []);

        return parent::getHzCode();
    }

    /**
     * {@inheritDoc}
     */
    public function setHzCode($hzCode)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHzCode', [$hzCode]);

        return parent::setHzCode($hzCode);
    }

    /**
     * {@inheritDoc}
     */
    public function getInvoice()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInvoice', []);

        return parent::getInvoice();
    }

    /**
     * {@inheritDoc}
     */
    public function setInvoice($invoice)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInvoice', [$invoice]);

        return parent::setInvoice($invoice);
    }

    /**
     * {@inheritDoc}
     */
    public function getInvoiceDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInvoiceDate', []);

        return parent::getInvoiceDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setInvoiceDate($invoiceDate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInvoiceDate', [$invoiceDate]);

        return parent::setInvoiceDate($invoiceDate);
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(array $raw)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hydrate', [$raw]);

        return parent::hydrate($raw);
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubaccount(\App\Entities\Subaccount $subaccount)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubaccount', [$subaccount]);

        return parent::setSubaccount($subaccount);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubaccount()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubaccount', []);

        return parent::getSubaccount();
    }

    /**
     * {@inheritDoc}
     */
    public function getArea()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getArea', []);

        return parent::getArea();
    }

    /**
     * {@inheritDoc}
     */
    public function setType(int $type)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setType', [$type]);

        return parent::setType($type);
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getType', []);

        return parent::getType();
    }

    /**
     * {@inheritDoc}
     */
    public function setDetail($detail = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDetail', [$detail]);

        return parent::setDetail($detail);
    }

    /**
     * {@inheritDoc}
     */
    public function getDetail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDetail', []);

        return parent::getDetail();
    }

    /**
     * {@inheritDoc}
     */
    public function setCredit($credit)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCredit', [$credit]);

        return parent::setCredit($credit);
    }

    /**
     * {@inheritDoc}
     */
    public function getCredit()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCredit', []);

        return parent::getCredit();
    }

    /**
     * {@inheritDoc}
     */
    public function getAccount()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAccount', []);

        return parent::getAccount();
    }

    /**
     * {@inheritDoc}
     */
    public function setCreated(\Datetime $created)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreated', [$created]);

        return parent::setCreated($created);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreated', []);

        return parent::getCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function setUpdated(\Datetime $updated)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUpdated', [$updated]);

        return parent::setUpdated($updated);
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUpdated', []);

        return parent::getUpdated();
    }

    /**
     * {@inheritDoc}
     */
    public function updateTimestamps()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'updateTimestamps', []);

        return parent::updateTimestamps();
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTypeName', []);

        return parent::getTypeName();
    }

}
