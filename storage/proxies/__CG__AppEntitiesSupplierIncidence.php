<?php

namespace DoctrineProxies\__CG__\App\Entities\Supplier;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Incidence extends \App\Entities\Supplier\Incidence implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'id', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'status', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'detail', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'supplier', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'order', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'user', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'created', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'updated'];
        }

        return ['__isInitialized__', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'id', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'status', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'detail', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'supplier', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'order', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'user', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'created', '' . "\0" . 'App\\Entities\\Supplier\\Incidence' . "\0" . 'updated'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Incidence $proxy) {
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
    public function isClosed()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isClosed', []);

        return parent::isClosed();
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
    public function getStatusColor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatusColor', []);

        return parent::getStatusColor();
    }

    /**
     * {@inheritDoc}
     */
    public function setOrder(\App\Entities\Order $order = NULL)
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
    public function setSupplier(\App\Entities\Supplier $supplier)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSupplier', [$supplier]);

        return parent::setSupplier($supplier);
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
    public function setUser(\App\Entities\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUser', [$user]);

        return parent::setUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function getUser()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUser', []);

        return parent::getUser();
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

}