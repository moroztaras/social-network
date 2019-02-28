<?php

namespace App\Form\Svistyn\Model;

use App\Components\User\CurrentUser;
use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Components\VideoEmbed\VideoEmbedManager;
use App\Entity\Svistyn;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SvistynModel
{
    public $id;

    public $text;

    public $embed;

    public $image;
    /**
     * @var Svistyn
     */
    private $svistyn;

    private $embedManager;

    private $em;

    private $state;

    private $parent;

    private $currentUser;

    /**
     * SvistynModel constructor.
     *
     * @param VideoEmbedManager      $embedManager
     * @param EntityManagerInterface $entityManager
     * @param CurrentUser            $currentUser
     */
    public function __construct(
    VideoEmbedManager $embedManager,
    EntityManagerInterface $entityManager,
    CurrentUser $currentUser)
    {
        $this->embedManager = $embedManager;
        $this->em = $entityManager;
        $this->currentUser = $currentUser;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * @return Svistyn
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @param Svistyn $svistyn
     */
    public function setSvistynEntity(Svistyn $svistyn)
    {
        $this->svistyn = $svistyn;
        $this->preprocess();
    }

    /**
     * Add values from Entity Svistyn to Model.
     */
    public function preprocess()
    {
        $this->id = $this->svistyn->getId();
        $this->text = $this->svistyn->getText();
        $this->embed = $this->svistyn->getEmbedVideo();
        $this->state = $this->svistyn->getState();
        $this->parent = $this->svistyn->getParent();
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (!empty($this->text)) {
            $this->text = strip_tags($this->text);
            $this->text = trim($this->text);
            if (empty($this->text)) {
                $context->buildViolation('Message is empty')->atPath('text')->addViolation();
            }

            return;
        }
        if (!empty($this->embed)) {
            if (!$this->embedManager->checkProviderInput($this->embed)) {
                $context->buildViolation('Invalid embed video link')->atPath('embed')->addViolation();
            }

            return;
        }
        if ($this->isEmpty()) {
            $context->buildViolation('Something is wrong')->addViolation();
        }
    }

    public function isEmpty()
    {
        return empty($this->text) && empty($this->embed) && !$this->svistyn->getPhoto() instanceof File;
    }

    public function save()
    {
        $this->beforeSave();
        $this->svistyn->setMarking('active');
        $this->em->persist($this->svistyn);
        $this->em->flush();
    }

    protected function beforeSave()
    {
        if (!empty($this->text)) {
            $this->text = substr($this->text, 0, 200);
            $this->svistyn->setText($this->text);
        }
        if (!empty($this->embed)) {
            $this->svistyn->setEmbedVideo($this->embed);
        }
        $user = $this->currentUser->getUser();
        $this->svistyn->setUser($user);
        $this->svistyn->setState($this->state);
        if ($this->parent) {
            $this->svistyn->setParent($this->parent);
        }
    }
}
