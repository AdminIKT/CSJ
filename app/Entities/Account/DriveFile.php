<?php

namespace App\Entities\Account;

use Doctrine\ORM\Mapping as ORM;
use App\Entities\Account;

/**
 * Deparment 
 *
 * @ORM\Table(name="accounts_drive_files")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class DriveFile
{
    const TYPE_ESTIMATE = 'estimate';
    const TYPE_INVOICE  = 'invoice';

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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="drive_file", type="string")
     */
    private $fileId;

    /**
     * @var string
     *
     * @ORM\Column(name="drive_url", type="string")
     */
    private $fileUrl;

    /**
     * @var Account 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Account", inversedBy="files")
     */
    private $account;

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
     * Set account.
     *
     * @param Account $account
     *
     * @return DriveFile
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account.
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return DriveFile
     */
    public function setName($name)
    {
        $this->name = (string) $name;

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
     * Set folder.
     *
     * @param string $fileId
     *
     * @return DriveFile
     */
    public function setFileId($fileId)
    {
        $this->fileId = (string) $fileId;

        return $this;
    }

    /**
     * Get fileId.
     *
     * @return string
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * Set folder.
     *
     * @param string $fileUrl
     *
     * @return DriveFile
     */
    public function setFileUrl($fileUrl)
    {
        $this->fileUrl = (string) $fileUrl;

        return $this;
    }

    /**
     * Get fileUrl.
     *
     * @return string
     */
    public function getFileUrl()
    {
        return $this->fileUrl;
    }

    /**
     * Set created.
     *
     * @param \Datetime $created
     *
     * @return DriveFile
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
     * @return DriveFile
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
     * @ORM\PrePersist
     */
    public function updateTimestamps()
    {
        if ($this->getCreated() === null) {
            $this->setCreated(new \DateTime('now'));
        }
    }
}
