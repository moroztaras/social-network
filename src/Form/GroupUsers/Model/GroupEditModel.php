<?php

namespace App\Form\GroupUsers\Model;

use App\Components\File\FileAssistant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\GroupUsers;

class GroupEditModel
{
    private $id;

    /**
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @Assert\NotBlank()
     */
    private $confidentiality;

    /**
     * @Assert\Image(
     *  mimeTypes = {"image/jpeg", "image/png", "image/jpg"},
     *  maxSize = "20Mi",
     *  minWidth = "200",
     *  minHeight = "200",
     *  maxSizeMessage = "Image file size exceeds 20MB.",
     *  mimeTypesMessage = "Invalid file format. Allowed file formats: JPG, JPEG, PNG."
     * )
     */
    private $avatar;

    /**
     * @Assert\Image(
     *  mimeTypes = {"image/jpeg", "image/png", "image/jpg"},
     *  maxSize = "20Mi",
     *  minWidth = "1280",
     *  minHeight = "300",
     *  maxSizeMessage = "Image file size exceeds 20MB.",
     *  mimeTypesMessage = "Invalid file format. Allowed file formats: JPG, JPEG, PNG."
     * )
     */
    private $cover;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FileAssistant
     */
    private $fileAssistant;

    /**
     * GroupEditModel constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FileAssistant          $fileAssistant
     */
    public function __construct(EntityManagerInterface $entityManager, FileAssistant $fileAssistant)
    {
        $this->em = $entityManager;
        $this->fileAssistant = $fileAssistant;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return GroupEditModel
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
     * Set description.
     *
     * @param string $description
     *
     * @return GroupEditModel
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set confidentiality.
     *
     * @param string $confidentiality
     *
     * @return GroupEditModel
     */
    public function setConfidentiality($confidentiality)
    {
        $this->confidentiality = $confidentiality;

        return $this;
    }

    /**
     * Get confidentiality.
     *
     * @return string
     */
    public function getConfidentiality()
    {
        return $this->confidentiality;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param mixed $cover
     */
    public function setCover($cover): void
    {
        $this->cover = $cover;
    }

    public function setGropUsers(GroupUsers $groupUsers)
    {
        $this->id = $groupUsers->getId();
        $this->name = $groupUsers->getName();
        $this->description = $groupUsers->getDescription();
        $this->confidentiality = $groupUsers->getConfidentiality();
    }

    public function save(GroupUsers $groupUsers)
    {
        $groupUsers->setName($this->getName());
        $groupUsers->setDescription($this->getDescription());
        $groupUsers->setConfidentiality($this->getConfidentiality());

        if ($this->avatar) {
            $file = $this->fileAssistant->prepareUploadFile($this->avatar, 'group/'.$groupUsers->getId());
            $file->setStatus(1);
            $userAvatar = $groupUsers->getAvatar();
            if ($userAvatar) {
                $this->em->remove($userAvatar);
            }
            $groupUsers->setAvatar($file);
        }
        if ($this->cover) {
            $file = $this->fileAssistant->prepareUploadFile($this->cover, 'group/'.$groupUsers->getId());
            $file->setStatus(1);
            $userCover = $groupUsers->getCover();
            if ($userCover) {
                $this->em->remove($userCover);
            }
            $groupUsers->setCover($file);
        }

        $this->em->persist($groupUsers);
        $this->em->flush();
    }
}
