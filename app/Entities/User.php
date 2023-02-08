<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use \Illuminate\Contracts\Auth\Authenticatable;
use LaravelDoctrine\ACL\Roles\HasRoles;
use LaravelDoctrine\ACL\Mappings as ACL;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;

/**
 * User 
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class User implements Authenticatable, HasRolesContract
{
    use HasRoles;

    const SITE_DOMAIN = "@fpsanjorge.com";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="google_id", type="string", nullable=true)
     */
    private $googleId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=100, nullable=true)
     */
    private $remember_token;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Account", mappedBy="users")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $accounts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="user", cascade={"persist","remove"})
     */
    private $orders;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Supplier", mappedBy="user", cascade={"persist","remove"})
     */
    private $suppliers;

    /**
     * @var DateTime 
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var DateTime 
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var DateTime 
     *
     * @ORM\Column(name="login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ACL\HasRoles()
     */
    protected $roles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accounts  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->suppliers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles     = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set googleId.
     *
     * @param string $googleId
     *
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * Get googleId.
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getShort()
    {
        return str_replace(self::SITE_DOMAIN, "", $this->getEmail());
    }

    /**
     * Set avatar.
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add account.
     *
     * @param \Account $account
     *
     * @return User
     */
    public function addAccount(Account $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * Remove account.
     *
     * @param \Account $account
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAccount(Account $account)
    {
        return $this->accounts->removeElement($account);
    }

    /**
     * Get accounts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Get areas.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAreas()
    {
        $areas = [];
        foreach ($this->getAccounts() as $account) {
            $areas = array_merge(
                        $areas, 
                        $account->getAreas()->toArray());
        }
        return new ArrayCollection($areas);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @inheritDoc
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * @inheritDoc
     */
    public function getAuthIdentifier()
    {
        return $this->getId();
    }

    /**
     * @inheritDoc
     */
    public function getAuthPassword()
    {
        return $this->getPassword();
    }

    /**
     * @inheritDoc
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * @inheritDoc
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRememberTokenName()
    {
        return "remember_token";
    }

    /**
     * Set created.
     *
     * @param \Datetime $created
     *
     * @return User
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
     * Set updated.
     *
     * @param \Datetime $updated
     *
     * @return User
     */
    public function setUpdated(\Datetime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \Datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param Role $role
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->getRoles()->add($role);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param int $roleId
     * @return bool
     */
    protected function isRole(int $roleId)
    {
        foreach ($this->getRoles() as $role) {
            if ($role->getId() === $roleId) 
                return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isRole(Role::ROLE_ADMIN);
    }

    /**
     * @return bool
     */
    public function isReception()
    {
        return $this->isRole(Role::ROLE_RECEPTION);
    }

    /**
     * @return bool
     */
    public function isSales()
    {
        return $this->isRole(Role::ROLE_SALES);
    }

    /**
     * Set lastLogin.
     *
     * @param \Datetime $lastLogin
     *
     * @return User
     */
    public function setLastLogin(\Datetime $lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin.
     *
     * @return \Datetime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        $this->setUpdated(new \DateTime('now'));
        if ($this->getCreated() === null) {
            $this->setCreated(new \DateTime('now'));
        }
    }
}
