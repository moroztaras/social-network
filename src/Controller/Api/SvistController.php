<?php

namespace App\Controller\Api;

use App\Entity\Svistyn;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\NotFoundException;
use App\Exception\JsonHttpException;
use App\Exception\AccessDeniedException;
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

    /**
     * @Route("svist", name="api_svist_new", methods={"POST"})
     */
    public function newSvisit(Request $request)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Bad Request');
        }
        $em = $this->getDoctrine()->getManager();
        $apiToken = $request->headers->get('x-api-key');

        /** @var User $user */
        $user = $em->getRepository(User::class)
          ->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Authentication error');
        }
        /* @var Svistyn $svistyn */
        $svistyn = $this->serializer->deserialize($request->getContent(), Svistyn::class, 'json');
        $svistyn
          ->setUser($user)
          ->setCreated(new \DateTime())
          ->setUpdated(new \DateTime())
          ;

        $errors = $this->validator->validate($svistyn);
        if (count($errors)) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, (string) $errors->get(0)->getPropertyPath().': '.(string) $errors->get(0)->getMessage());
        }
        $this->getDoctrine()->getManager()->persist($svistyn);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['svistyn' => $svistyn]);
    }

    /**
     * @Route("svist/{id}", name="api_svist_delete", methods={"DELETE"}, requirements={"id": "\d+"})
     */
    public function removeArticle(Request $request, Svistyn $svistyn)
    {
        if (!$svistyn) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Svist Not Found.');
        }
        $em = $this->getDoctrine()->getManager();
        $apiToken = $request->headers->get('x-api-key');

        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Authentication error');
        }
        if ($user !== $svistyn->getUser()) {
            throw new AccessDeniedException(Response::HTTP_FORBIDDEN, 'Access Denied.');
        }
        $this->getDoctrine()->getManager()->remove($svistyn);
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
          'success' => [
            'code' => Response::HTTP_OK,
            'message' => 'Svist was deleted',
          ],
        ], Response::HTTP_OK);
    }
}
