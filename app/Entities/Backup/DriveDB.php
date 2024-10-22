<?php

namespace App\Entities\Backup;

use Doctrine\ORM\Mapping as ORM;

/**
 * DriveDB 
 *
 * @ORM\Table(name="backups_drive_db")
 * @ORM\Entity()
 */
class DriveDB 
{
    const STATUS_PENDING  = 0;
    const STATUS_UPLOADED = 1;

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
    public $name;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", options={"default":0})
     */
    private $status = DriveDB::STATUS_PENDING;

    /**
     * @var string
     *
     * @ORM\Column(name="drive_file", type="string", nullable=true)
     */
    private $fileId;

    /**
     * @var string
     *
     * @ORM\Column(name="drive_url", type="string", nullable=true)
     */
    private $fileUrl;

    /**
     * @var DateTime 
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    public function __construct() {
        $this->created = new \Datetime;
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
     * Set name.
     *
     * @param string $name
     *
     * @return DB
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
     * Set status.
     *
     * @param int $status
     *
     * @return DB
     */
    public function setStatus(int $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Is status.
     *
     * @param int $status
     *
     * @return bool
     */
    public function isStatus(int $status)
    {
        return (bool) $this->getStatus() == $status;
    }

    /**
     * Is uploaded.
     *
     * @return bool
     */
    public function isUploaded()
    {
        return $this->isStatus(self::STATUS_UPLOADED);
    }

    /**
     * Set fileId.
     *
     * @param string $fileId
     *
     * @return DB 
     */
    public function setFileId($fileId = null)
    {
        $this->fileId = $fileId;

        return $this;
    }

    /**
     * Get fileId.
     *
     * @return string|null
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * Set fileUrl.
     *
     * @param string $fileUrl
     *
     * @return DB 
     */
    public function setFileUrl($fileUrl = null)
    {
        $this->fileUrl = $fileUrl;

        return $this;
    }

    /**
     * Get fileUrl.
     *
     * @return string|null
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
     * @return DB
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
    public function __tostring() 
    {
        return (string) $this->getName();
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return self::statusName($this->getStatus());
    }

    /**
     * @param int $status
     * @return string
     */
    public static function statusName($status)
    {
        switch ($status) {
            case self::STATUS_PENDING:
                return trans('Upload pending');
            case self::STATUS_UPLOADED:
                return trans('Uploaded');
            default:
                return trans('Undefined');
        }
    }
}
