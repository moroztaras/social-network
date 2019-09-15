<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class File.
 *
 * @ORM\Table(name="file_manager")
 * @ORM\Entity
 */
class File
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="user_id", nullable=true )
     */
    private $user;

    /**
     * @ORM\Column(type="string")
     */
    private $filename;

    /**
     * @ORM\Column(type="string")
     */
    private $originName;

    /**
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @ORM\Column(type="integer")
     */
    private $fileSize;

    /**
     * @ORM\Column(type="string")
     */
    private $fileMime;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $handler;

    /**
     * @ORM\OneToOne(targetEntity="FileUsage", mappedBy="file", cascade={"remove", "persist"})
     */
    private $usage;

    private $uploadFile;

    private $isPrivate = false;

    /**
     * File constructor.
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->fileSize = 0;
        $this->status = 0;
        $this->user = 0;
    }

    /**
     * Return folder of existed file in system.
     *
     * @deprecated
     */
    public function getFolder()
    {
        $url = $this->getUrl();
        $url = str_replace('public://', '', $url);
        $url = str_replace('private://', '', $url);
        $url = explode('/', $url);
        array_pop($url);

        return implode('/', $url);
    }

    public function isPrivate()
    {
        return !is_null($this->handler);
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
     * Set user.
     *
     * @param int $user
     *
     * @return File
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set filename.
     *
     * @param string $filename
     *
     * @return File
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return File
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set fileSize.
     *
     * @param int $fileSize
     *
     * @return File
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize.
     *
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set fileMime.
     *
     * @param string $fileMime
     *
     * @return File
     */
    public function setFileMime($fileMime)
    {
        $this->fileMime = $fileMime;

        return $this;
    }

    /**
     * Get fileMime.
     *
     * @return string
     */
    public function getFileMime()
    {
        return $this->fileMime;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return File
     */
    public function setStatus($status)
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
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return File
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /** @return UploadedFile */
    public function getUploadFile()
    {
        return $this->uploadFile;
    }

    public function setUploadFile(UploadedFile $upload_file = null)
    {
        $this->uploadFile = $upload_file;

        return $this;
    }

    /**
     * Set handler.
     *
     * @param string $handler
     *
     * @return File
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * Get handler.
     *
     * @return string
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Set usage.
     *
     * @param FileUsage $usage
     *
     * @return File
     */
    public function setUsage(FileUsage $usage = null)
    {
        $this->usage = $usage;

        return $this;
    }

    /**
     * Get usage.
     *
     * @return FileUsage
     */
    public function getUsage()
    {
        return $this->usage;
    }

    /**
     * @return mixed
     */
    public function getOriginName()
    {
        if (!$this->originName) {
            return $this->filename;
        }

        return $this->originName;
    }

    /**
     * @param mixed $originName
     */
    public function setOriginName($originName)
    {
        $this->originName = $originName;
    }

    public function attachUsage($entity)
    {
        $fileUsage = $this->getUsage() ? $this->getUsage() : new FileUsage();
        $className = strtolower((new \ReflectionClass($entity))->getShortName());
        $fileUsage
      ->setFile($this)
      ->setEntityType($className)
      ->setEntityId(-1)
      ->setEntity($entity);
        $this->setUsage($fileUsage);
    }
}
