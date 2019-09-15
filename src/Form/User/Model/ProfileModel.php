<?php

namespace App\Form\User\Model;

use App\Components\File\FileAssistant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

class ProfileModel
{
    private $id;

    /**
     * @Assert\NotBlank()
     */
    private $fullname;

    /**
     * @Assert\NotBlank()
     */
    private $birthday;
    /**
     * @Assert\NotBlank()
     */
    private $gender;

    /**
     * @Assert\Country()
     */
    private $region;
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

    private $fileAssistant;

    public function __construct(EntityManagerInterface $entityManager, FileAssistant $fileAssistant)
    {
        $this->em = $entityManager;
        $this->fileAssistant = $fileAssistant;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region): void
    {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $fullname
     */
    public function setFullname($fullname): void
    {
        $this->fullname = $fullname;
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

    public function setUser(User $user)
    {
        $this->id = $user->getId();
        $this->fullname = $user->getFullname();
        $this->birthday = $user->getBirthday();
        $this->gender = $user->getGender();
        $this->region = $user->getRegion();
    }

    public function save(User $user)
    {
        $user->setFullname($this->getFullname());
        $user->setBirthday($this->getBirthday());
        $user->setGender($this->getGender());
        $user->setRegion($this->getRegion());
        if ($this->avatar) {
            $file = $this->fileAssistant->prepareUploadFile($this->avatar, 'user/'.$user->getId());
            $file->setStatus(1);
            $userAvatar = $user->getAvatar();
            if ($userAvatar) {
                $this->em->remove($userAvatar);
            }
            $user->setAvatar($file);
        }
        if ($this->cover) {
            $file = $this->fileAssistant->prepareUploadFile($this->cover, 'user/'.$user->getId());
            $file->setStatus(1);
            $userCover = $user->getCover();
            if ($userCover) {
                $this->em->remove($userCover);
            }
            $user->setCover($file);
        }

        $this->em->persist($user);
        $this->em->flush();
    }
}
