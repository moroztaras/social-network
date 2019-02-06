<?php

namespace App\Components\Svistyn\Events;

use App\Components\File\Events\FileUploadEvent;
use App\Components\File\ImageStyle;
use App\Components\File\Model\ImageModel;
use App\Components\User\CurrentUser;
use App\Components\Utils\View\ViewJson;
use App\Entity\File;
use App\Entity\Svistyn;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SvistynImageFileUploadListener
{
    private $em;

    private $currentUser;

    private $validator;

    private $imageStyle;

    public function __construct(
    EntityManagerInterface $entityManager, CurrentUser $currentUser,
    ValidatorInterface $validator, ImageStyle $imageStyle)
    {
        $this->em = $entityManager;
        $this->currentUser = $currentUser;
        $this->validator = $validator;
        $this->imageStyle = $imageStyle;
    }

    public function onFileUpload(FileUploadEvent $events)
    {
        $options = $events->getOptions();
        if (!isset($options['handler']) || 'svistyn_image' != $options['handler']) {
            return;
        }

        $events->stopPropagation();
        if (!$this->currentUser->getUser() instanceof User) {
            return;
        }
        $user = $this->currentUser->getUser();
        $svistRepo = $this->em->getRepository(Svistyn::class);
        $svist = $svistRepo->findOneBy(['user' => $user->getId(), 'marking' => 'new']);
        if (!$svist) {
            $svist = new Svistyn();
            $svist->setUser($user);
            $this->em->persist($svist);

            $this->em->flush($svist);
        }
        $imageModel = new ImageModel();
        $imageModel->image = $events->getFile()->getUploadFile();
        $errors = $this->validator->validate($imageModel);
        if (count($errors) > 0) {
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                ViewJson::setMessageWarning($error->getMessage());
            }

            return;
        }

        $fileAssistant = $events->getFileAssistant();
        $fileAssistant->setFolderFile($events->getFile(), 'svistyn/'.$fileAssistant->getFolderMonthYear().'/'.$fileAssistant->getFolderDay());
        $image = $svist->getPhoto();

        if ($image instanceof File) {
            $this->em->remove($image);
        }
        $photo = $events->getFile();
        $svist->setPhoto($photo);
        $this->em->persist($svist);
        $this->em->flush();

        $this->imageStyle->resizeImage($photo, '1024w');
        //Update file size
        $newSize = filesize($fileAssistant->rootUrl($photo->getUrl()));
        $photo->setFileSize($newSize);
        $this->em->persist($photo);
        $this->em->flush();
        ViewJson::add('image_uploaded', $fileAssistant->webDir($photo->getUrl(), true));
    }
}
