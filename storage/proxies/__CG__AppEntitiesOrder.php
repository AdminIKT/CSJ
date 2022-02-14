<?php

namespace DoctrineProxies\__CG__\App\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Order extends \App\Entities\Order implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'id', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'status', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'detail', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'sequence', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'date', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'area', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'products', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'movements', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'incidences', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'estimatedCredit', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'credit', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'invoice', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'created', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'updated'];
        }

        return ['__isInitialized__', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'id', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'status', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'detail', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'sequence', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'date', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'area', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'products', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'movements', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'incidences', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'estimatedCredit', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'credit', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'invoice', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'created', '' . "\0" . 'App\\Entities\\Order' . "\0" . 'updated'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Order $proxy) {
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
    public function getSequence()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSequence', []);

        return parent::getSequence();
    }

    /**
     * {@inheritDoc}
     */
    public function setSequence($sequence)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSequence', [$sequence]);

        return parent::setSequence($sequence);
    }

    /**
     * {@inheritDoc}
     */
    public function setDetail($detail)
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
    public function setEstimatedCredit(float $credit)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEstimatedCredit', [$credit]);

        return parent::setEstimatedCredit($credit);
    }

    /**
     * {@inheritDoc}
     */
    public function getEstimatedCredit()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEstimatedCredit', []);

        return parent::getEstimatedCredit();
    }

    /**
     * {@inheritDoc}
     */
    public function setCredit(float $credit = NULL)
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
    public function getInvoice()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInvoice', []);

        return parent::getInvoice();
    }

    /**
     * {@inheritDoc}
     */
    public function setInvoice($invoice = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInvoice', [$invoice]);

        return parent::setInvoice($invoice);
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus(int $status)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStatus', [$status]);

        return parent::setStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function isStatus(int $status)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isStatus', [$status]);

        return parent::isStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', []);

        return parent::getStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatusName', []);

        return parent::getStatusName();
    }

    /**
     * {@inheritDoc}
     */
    public function setArea(\App\Entities\Area $area)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setArea', [$area]);

        return parent::setArea($area);
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
    public function addMovement(\App\Entities\Movement $movement)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addMovement', [$movement]);

        return parent::addMovement($movement);
    }

    /**
     * {@inheritDoc}
     */
    public function getMovements()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMovements', []);

        return parent::getMovements();
    }

    /**
     * {@inheritDoc}
     */
    public function addProduct(\App\Entities\Order\Product $product)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addProduct', [$product]);

        return parent::addProduct($product);
    }

    /**
     * {@inheritDoc}
     */
    public function removeProduct(\App\Entities\Order\Product $product)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeProduct', [$product]);

        return parent::removeProduct($product);
    }

    /**
     * {@inheritDoc}
     */
    public function getProducts()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProducts', []);

        return parent::getProducts();
    }

    /**
     * {@inheritDoc}
     */
    public function setDate(\Datetime $date)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDate', [$date]);

        return parent::setDate($date);
    }

    /**
     * {@inheritDoc}
     */
    public function getDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDate', []);

        return parent::getDate();
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
    public function fromArray(array $values)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'fromArray', [$values]);

        return parent::fromArray($values);
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'jsonSerialize', []);

        return parent::jsonSerialize();
    }

}
