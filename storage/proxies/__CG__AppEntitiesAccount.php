<?php

namespace DoctrineProxies\__CG__\App\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Account extends \App\Entities\Account implements \Doctrine\ORM\Proxy\Proxy
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
  'name' => NULL,
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
  'name' => NULL,
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
    {
        unset($this->name);

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }

    /**
     * 
     * @param string $name
     */
    public function __get($name)
    {
        if (\array_key_exists($name, self::$lazyPropertiesNames)) {
            $this->__initializer__ && $this->__initializer__->__invoke($this, '__get', [$name]);
            return $this->$name;
        }

        trigger_error(sprintf('Undefined property: %s::$%s', __CLASS__, $name), E_USER_NOTICE);

    }

    /**
     * 
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        if (\array_key_exists($name, self::$lazyPropertiesNames)) {
            $this->__initializer__ && $this->__initializer__->__invoke($this, '__set', [$name, $value]);

            $this->$name = $value;

            return;
        }

        $this->$name = $value;
    }

    /**
     * 
     * @param  string $name
     * @return boolean
     */
    public function __isset($name)
    {
        if (\array_key_exists($name, self::$lazyPropertiesNames)) {
            $this->__initializer__ && $this->__initializer__->__invoke($this, '__isset', [$name]);

            return isset($this->$name);
        }

        return false;
    }

    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', 'id', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'status', 'name', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'fileId', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'fileUrl', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'acronym', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'lcode', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'type', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'detail', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'compromisedCredit', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'credit', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'files', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'subaccounts', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'users', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'created', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'updated'];
        }

        return ['__isInitialized__', 'id', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'status', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'fileId', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'fileUrl', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'acronym', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'lcode', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'type', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'detail', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'compromisedCredit', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'credit', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'files', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'subaccounts', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'users', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'created', '' . "\0" . 'App\\Entities\\Account' . "\0" . 'updated'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Account $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

            unset($this->name);
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
    public function getStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', []);

        return parent::getStatus();
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
    public function isActive()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isActive', []);

        return parent::isActive();
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setName', [$name]);

        return parent::setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', []);

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function setType($type)
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
    public function setAcronym($acronym)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAcronym', [$acronym]);

        return parent::setAcronym($acronym);
    }

    /**
     * {@inheritDoc}
     */
    public function getAcronym()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAcronym', []);

        return parent::getAcronym();
    }

    /**
     * {@inheritDoc}
     */
    public function setLCode($lcode = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLCode', [$lcode]);

        return parent::setLCode($lcode);
    }

    /**
     * {@inheritDoc}
     */
    public function getLCode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLCode', []);

        return parent::getLCode();
    }

    /**
     * {@inheritDoc}
     */
    public function getSerial()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSerial', []);

        return parent::getSerial();
    }

    /**
     * {@inheritDoc}
     */
    public function increaseCredit(float $credit)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'increaseCredit', [$credit]);

        return parent::increaseCredit($credit);
    }

    /**
     * {@inheritDoc}
     */
    public function decreaseCredit(float $credit)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'decreaseCredit', [$credit]);

        return parent::decreaseCredit($credit);
    }

    /**
     * {@inheritDoc}
     */
    public function increaseCompromisedCredit(float $credit)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'increaseCompromisedCredit', [$credit]);

        return parent::increaseCompromisedCredit($credit);
    }

    /**
     * {@inheritDoc}
     */
    public function decreaseCompromisedCredit(float $credit)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'decreaseCompromisedCredit', [$credit]);

        return parent::decreaseCompromisedCredit($credit);
    }

    /**
     * {@inheritDoc}
     */
    public function setCompromisedCredit(float $credit)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCompromisedCredit', [$credit]);

        return parent::setCompromisedCredit($credit);
    }

    /**
     * {@inheritDoc}
     */
    public function getCompromisedCredit()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCompromisedCredit', []);

        return parent::getCompromisedCredit();
    }

    /**
     * {@inheritDoc}
     */
    public function setCredit(float $credit)
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
    public function getAvailableCredit()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAvailableCredit', []);

        return parent::getAvailableCredit();
    }

    /**
     * {@inheritDoc}
     */
    public function setFileId($fileId)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFileId', [$fileId]);

        return parent::setFileId($fileId);
    }

    /**
     * {@inheritDoc}
     */
    public function getFileId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFileId', []);

        return parent::getFileId();
    }

    /**
     * {@inheritDoc}
     */
    public function setFileUrl($fileUrl)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFileUrl', [$fileUrl]);

        return parent::setFileUrl($fileUrl);
    }

    /**
     * {@inheritDoc}
     */
    public function getFileUrl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFileUrl', []);

        return parent::getFileUrl();
    }

    /**
     * {@inheritDoc}
     */
    public function addFile(\App\Entities\Account\DriveFile $file)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addFile', [$file]);

        return parent::addFile($file);
    }

    /**
     * {@inheritDoc}
     */
    public function getFiles()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFiles', []);

        return parent::getFiles();
    }

    /**
     * {@inheritDoc}
     */
    public function addUser(\App\Entities\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addUser', [$user]);

        return parent::addUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function removeUser(\App\Entities\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeUser', [$user]);

        return parent::removeUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function getUsers()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUsers', []);

        return parent::getUsers();
    }

    /**
     * {@inheritDoc}
     */
    public function addSubaccount(\App\Entities\Subaccount $subaccount)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addSubaccount', [$subaccount]);

        return parent::addSubaccount($subaccount);
    }

    /**
     * {@inheritDoc}
     */
    public function removeSubaccount(\App\Entities\Subaccount $subaccount)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeSubaccount', [$subaccount]);

        return parent::removeSubaccount($subaccount);
    }

    /**
     * {@inheritDoc}
     */
    public function getAreas()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAreas', []);

        return parent::getAreas();
    }

    /**
     * {@inheritDoc}
     */
    public function getSubaccounts()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubaccounts', []);

        return parent::getSubaccounts();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrders()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOrders', []);

        return parent::getOrders();
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
    public function getTypeName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTypeName', []);

        return parent::getTypeName();
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
    public function getStatusName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatusName', []);

        return parent::getStatusName();
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
    public function __toString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__toString', []);

        return parent::__toString();
    }

}
