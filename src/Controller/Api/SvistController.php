<?php

namespace App\Controller\Api;

use App\Entity\Svistyn;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class SvistController.
 *
 * @Route("api/")
 */
class SvistController extends Controller
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("svist/{id}", name="api_svist_show", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function showArticle(Svistyn $svistyn)
    {
        if (!$svistyn) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Svisit Not Found.');
        }

        return $this->json(['svist' => $svistyn], Response::HTTP_OK);
    }
}
