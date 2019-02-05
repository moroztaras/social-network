<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class File.
 *
 * @ORM\Table(name="file_usage")
 * @ORM\Entity
 */
class FileUsage
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="origin_id", nullable=true )
     */
    private $origin;

    /**
     * @ORM\OneToOne(targetEntity="File", inversedBy="usage")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id")
     */
    private $file;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $entityId;

    /**
     * @ORM\Column(type="string")
     */
    private $entityType;

    private $entity;

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
     * Set entityId.
     *
     * @param string $entityId
     *
     * @return FileUsage
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId.
     *
     * @return string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set entityType.
     *
     * @param string $entityType
     *
     * @return FileUsage
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Get entityType.
     *
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set file.
     *
     * @param File $file
     *
     * @return FileUsage
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param mixed $origin
     */
    public function setOrigin($origin): void
    {
        $this->origin = $origin;
    }
}
