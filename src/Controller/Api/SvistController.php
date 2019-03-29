<?php

namespace App\Controller\Api;

use App\Entity\Svistyn;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\NotFoundException;
use App\Exception\JsonHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Knp\Component\Pager\PaginatorInterface;

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

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, PaginatorInterface $paginator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->paginator = $paginator;
    }

    /**
     * @Route("user/{id}/svist/page={page}", name="api_user_list_svist", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function userListSvists($id, Request $request, string $page, $limit = 10)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['id' => $id]);

        if (!$user) {
            throw new JsonHttpException(Response::HTTP_NOT_FOUND, 'User not found');
        }
        $svists = $em->getRepository(Svistyn::class)->findBy(['user' => $user]);

        if (!$svists) {
            throw new JsonHttpException(Response::HTTP_NOT_FOUND, 'Svists not found');
        }

        return $this->json(
          [
          'svists' => $this->paginator->paginate(
            $svists,
            $request->query->getInt('page', $page), $limit),
          ],
          Response::HTTP_OK);
    }

    /**
     * @Route("svist/{id}", name="api_svist_show", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function showSvist(Svistyn $svistyn)
    {
        if (!$svistyn) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Svisit Not Found.');
        }

        return $this->json(['svist' => $svistyn], Response::HTTP_OK);
    }
}
