<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deparment 
 *
 * @ORM\Table(name="actions")
 * @ORM\Entity(repositoryClass="App\Repositories\ActionRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *  "order" = "App\Entities\Action\OrderAction" 
 * })
 * @ORM\HasLifecycleCallbacks
 */
abstract class Action
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(type="integer")
     */
    public $type;

    /**
     * @var string
     *
     * @ORM\Column(type="integer")
     */
    public $action;

    /**
     * @var User 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\User", inversedBy="actions")
     */
    private $user;

    /**
     * @var DateTime 
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type.
     *
     * @param int $type
     *
     * @return Action
     */
    public function setType($type)
    {
        $this->type = (int) $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set action.
     *
     * @param int $action
     *
     * @return Action
     */
    public function setAction($action)
    {
        $this->action = (int) $action;

        return $this;
    }

    /**
     * Get action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set user.
     *
     * @param string $user
     *
     * @return Action 
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set created.
     *
     * @param \Datetime $created
     *
     * @return Action
     */
    public function setCreated(\Datetime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \Datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return static::typeName($this->getType());
    }

    /**
     * @param mixed $entity
     * @return Action
     */
    abstract public function setEntity($entity);

    /**
     * @return mixed
     */
    abstract public function getEntity(); 

    /**
     *
     */
    abstract public function typeName($type);
}
