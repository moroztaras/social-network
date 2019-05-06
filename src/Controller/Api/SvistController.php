<?php

namespace App\Controller\Api;

use App\Entity\Svistyn;
use App\Entity\Comment;
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
 * @Route("api/svist")
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

    /**
     * SvistController constructor.
     *
     * @param SerializerInterface $serializer
     * @param ValidatorInterface  $validator
     * @param PaginatorInterface  $paginator
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, PaginatorInterface $paginator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/{id}", name="api_svist_show", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function showSvist(Svistyn $svistyn)
    {
        if (!$svistyn) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Svisit Not Found.');
        }

        return $this->json(['svist' => $svistyn], Response::HTTP_OK);
    }

    /**
     * @Route("", name="api_svist_new", methods={"POST"})
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
     * @Route("/{id}", name="api_svist_edit", methods={"PUT"}, requirements={"id": "\d+"})
     */
    public function editComment(Request $request, Svistyn $svistyn)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Bad Request');
        }
        $em = $this->getDoctrine()->getManager();
        $apiToken = $request->headers->get('x-api-key');

        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(Response::HTTP_UNAUTHORIZED, 'Authentication error');
        }

        $this->serializer->deserialize($request->getContent(), Svistyn::class, 'json', ['object_to_populate' => $svistyn]);

        $this->getDoctrine()->getManager()->persist($svistyn);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['svist' => $svistyn]);
    }

    /**
     * @Route("/{id}", name="api_svist_delete", methods={"DELETE"}, requirements={"id": "\d+"})
     */
    public function removeSvist(Request $request, Svistyn $svistyn)
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

    /**
     * @Route("/{id}/comments", name="api_svist_list_comments", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function listCommentsForSvist(Svistyn $svistyn)
    {
        if (!$svistyn) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Svisit Not Found.');
        }
        if (!$svistyn->getComments()) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Comments Not Found.');
        }

        return $this->json(['comments' => $svistyn->getComments()], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/comments", name="api_svist_add_comments", methods={"POST"}, requirements={"id": "\d+"})
     */
    public function addCommentForSvist(Svistyn $svistyn, Request $request)
    {
        if (!$svistyn) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Svisit Not Found.');
        }
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $em = $this->getDoctrine()->getManager();
        $apiToken = $request->headers->get('x-api-key');

        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(400, 'Authentication error');
        }

        /* @var Comment $comment */
        $comment = $this->serializer->deserialize($request->getContent(), Comment::class, 'json');
        $comment
          ->setUser($user)
          ->setSvistyn($svistyn)
          ->setCreatedAt(new \DateTime())
          ->setApproved(true);

        $errors = $this->validator->validate($comment);
        if (count($errors)) {
            throw new JsonHttpException(400, (string) $errors->get(0)->getPropertyPath().': '.(string) $errors->get(0)->getMessage());
        }
        $this->getDoctrine()->getManager()->persist($comment);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['comment' => $comment]);
    }

    /**
     * @Route("/{id}/share", name="api_svist_share", methods={"POST"}, requirements={"id"="\d+", "state" : "1|2"}, defaults={"id" = null})
     */
    public function shareSvisit($id, $state = 1, Request $request)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Bad Request');
        }
        $em = $this->getDoctrine()->getManager();
        $apiToken = $request->headers->get('x-api-key');

        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Authentication error');
        }
        /* @var Svistyn $svistyn */
        $svistyn = $em->getRepository(Svistyn::class)->find($id);
        if (!$svistyn) {
            throw new JsonHttpException(Response::HTTP_NOT_FOUND, 'Svist Not Found.');
        }
        if (!$svistyn || $svistyn->getUser()->getId() == $user->getId() || null != $svistyn->getParent()) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Share the svist is not allowed');
        }

        $this->serializer->deserialize($request->getContent(), Svistyn::class, 'json');

        $newSvist = new Svistyn();
        $newSvist
          ->setState($state)
          ->setParent($svistyn)
          ->setUser($user)
          ->setCreated(new \DateTime())
          ->setUpdated(new \DateTime())
        ;
        $newSvist
          ->setUpdatedAtValue()
        ;
        $newSvist->setMarking('active');

        $errors = $this->validator->validate($newSvist);
        if (count($errors)) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, (string) $errors->get(0)->getPropertyPath().': '.(string) $errors->get(0)->getMessage());
        }
        $this->getDoctrine()->getManager()->persist($newSvist);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['newSvist' => $newSvist]);
    }
}
