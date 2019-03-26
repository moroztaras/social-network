<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Media.
 *
 * @ORM\Table()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Entity()
 */
class Media implements \JsonSerializable
{
    const ALLOWED_CONTENT_TYPES = ['image/jpeg', 'image/jpg', 'image/png'];

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var resource|string
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $contentType;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $s3Key;

    /**
     * @var string
     */
    private $oldS3Key;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=191, nullable=true)
     */
    private $nameFile;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $url;

    public function __construct($prefix = null)
    {
        $name = bin2hex(random_bytes(16));
        $this->setS3Key($prefix ? sprintf('%s/%s', $prefix, $name) : $name);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return resource|string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param resource|string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getS3Key()
    {
        return $this->s3Key;
    }

    /**
     * @param string $s3Key
     *
     * @return $this
     */
    public function setS3Key($s3Key)
    {
        if ($this->getS3Key()) {
            $this->setOldS3Key($this->getS3Key());
        }
        $this->s3Key = $s3Key;

        return $this;
    }

    /**
     * @return string
     */
    public function getOldS3Key()
    {
        return $this->oldS3Key;
    }

    /**
     * @param string $oldS3Key
     *
     * @return $this
     */
    public function setOldS3Key($oldS3Key)
    {
        $this->oldS3Key = $oldS3Key;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameFile()
    {
        return $this->nameFile;
    }

    /**
     * @param string $nameFile
     *
     * @return $this
     */
    public function setNameFile($nameFile)
    {
        $this->nameFile = $nameFile;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     *
     * @return $this
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    public static function getAllowedTypes()
    {
        return static::ALLOWED_CONTENT_TYPES;
    }

    public function jsonSerialize()
    {
        return $this->getUrl();
    }
}
