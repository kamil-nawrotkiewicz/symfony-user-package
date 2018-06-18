<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\TokenAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/user")
 */
class ApiController extends Controller {

    /**
     * @Route("/{id}", methods="GET")
     */
    public function show(Request $request, User $user, TokenAuthenticator $token): Response
    {
        if($token->supports($request))
        {
            $encoders = array(new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $user->setPassword('');
            $user->setApiKey('');
        } else {
            return new JsonResponse([
                'message' => 'Authentication Required'
            ]);
        }

        return new Response($serializer->serialize($user, 'json'));
    }

    /**
     * @Route("/new", name="", methods="GET|POST")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($request->isMethod('post'))
        {
            $em = $this->getDoctrine()->getManager();
            $username = $request->get('username');
            $email = $request->get('email');

            $user = $em->getRepository(User::class)->findOneBy(
                [
                    'username' => $username,
                    'email'    => $email
                ]
            );

            if (empty($user))
            {
                $user = new User();
                $message = 'User created!';
            } else {
                $message = 'User updated!';
            }

            $user->setFullName($request->get('fullName'));
            $user->setUsername($username);
            $user->setEmail($request->get('email'));
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            //Save user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $return = [
                'message' => $message,
                'status'  => '200'
            ];

            return new JsonResponse($return, 200);
        }
    }
}
