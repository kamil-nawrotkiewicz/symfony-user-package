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
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/user")
 */
class ApiController extends Controller {

    /**
     * @Route("/{id}", methods="GET")
     * @param Request $request
     * @param User $user
     * @param TokenAuthenticator $token
     * @return Response
     */
    public function show(Request $request, User $user, TokenAuthenticator $token): Response
    {
        if($token->supports($request))
        {
            $user->setPassword('');
            $user->setApiKey('');
        } else {
            return new JsonResponse([
                'message' => 'Authentication Required'
            ]);
        }

        return new Response($this->toJson($user));
    }

    /**
     * @Route("/new", name="", methods="GET|POST")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TokenAuthenticator $token
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenAuthenticator $token, ValidatorInterface $validator): Response
    {
        if ($request->isMethod('post') && $token->supports($request))
        {
            $em = $this->getDoctrine()->getManager();
            $username = $request->get('username', '');
            $email = $request->get('email', '');
            $password = $request->get('password', '');

            //get repository
            $user = $em->getRepository(User::class)->findOneBy(
                ['username' => $username,
                    'email'    => $email]);

            //Checking if the user exists
            if (empty($user))
            {
                $user = new User();
                $message = 'User created!';
            } else {
                $message = 'User updated!';
            }

            //set data
            $user->setFullName($request->get('fullName'));
            $user->setUsername($username);
            $user->setEmail($request->get('email'));
            $password = $passwordEncoder->encodePassword($user, $password);
            $user->setPassword($password);
            $user->setPlainPassword($password);

            //validation
            $errors = $validator->validate($user);
            $response = [];
            if (count($errors) >0)
            {
                $response['code'] = 422;
                $response['message'] = 'Validation Failed';
                foreach ($errors as $violation)
                {
                    $response['errors'][$violation->getPropertyPath()] = $violation->getMessage();;
                }

                //serialize data
                $jsonContent = $this->toJson($response);
                return new Response($jsonContent, 422);
            }
            //end - validation

            //save user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //return message
            $return = [
                'message' => $message,
                'status'  => '200'
            ];

            return new JsonResponse($return, 200);
        }
    }

    /**
     * @param $text
     * @return bool|float|int|string
     */
    public function toJson($text)
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($text, 'json');

        return $jsonContent;
    }
}
