<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use ApiBundle\Entity\User;

class TokenController extends Controller
{

    /**
     * @Route("/register", name="register")
     * @Method({"POST"})
     */
    public function registerAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $encoder = $this->container->get('security.password_encoder');

        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        $user = new User($username);
        $user->setPassword($encoder->encodePassword($user, $password));

        $em->persist($user);
        $em->flush($user);

        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }

    /**
     * @Route("/api", name="api")
     */
    public function apiAction(Request $request)
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }

}
