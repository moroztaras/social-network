<?php

namespace App\Components\User\Models;

use App\Components\File\FileAssistant;
use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\UserAccount;

class ProfileAccountModel
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

    private $em;
    /**
     * @var UserAccount
     */
    private $account;

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

    public function setUser(UserAccount $account)
    {
        $this->account = $account;
        $this->id = $account->getId();
        $this->fullname = $account->getFullname();
        $this->birthday = $account->getBirthday();
        $this->gender = $account->getSex();
        $this->region = $account->getRegion();
    }

    public function save()
    {
        $this->account->setFullname($this->getFullname());
        $this->account->setBirthday($this->getBirthday());
        $this->account->setSex($this->getGender());
        $this->account->setRegion($this->getRegion());
        if ($this->avatar) {
            $file = $this->fileAssistant->prepareUploadFile($this->avatar, 'user/'.$this->account->getUser()->getId());
            $file->setStatus(1);
            $accountAvatar = $this->account->getAvatar();
            if ($accountAvatar) {
                $this->em->remove($accountAvatar);
            }
            $this->account->setAvatar($file);
        }
        if ($this->cover) {
            $file = $this->fileAssistant->prepareUploadFile($this->cover, 'user/'.$this->account->getUser()->getId());
            $file->setStatus(1);
            $accountCover = $this->account->getCover();
            if ($accountCover) {
                $this->em->remove($accountCover);
            }
            $this->account->setCover($file);
        }

        $this->em->persist($this->account);
        $this->em->flush();
    }
}
