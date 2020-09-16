<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class AuthController extends Controller
{
    /**
     * @Route("/authenticate", methods={"POST"}, name="api_authenticate")
     *
     */
    public function authenticateAction(Request $request): Response
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);
        if ($this->get('security.password_encoder')->isPasswordValid($user, $password)) {
            $token = $this->generateUserJWTToken($user->getApiToken(), ['username' => $username]);
            return new JsonResponse(['token' => $token]);
        }

        return new Response('Invalid redentials', 401);
    }

    private function generateUserJWTToken($api_token, $data)
    {
        $issued_at = new DateTimeImmutable();

        $lifetime = 3600;

        $default_data = [
            'sub' => $api_token,
            'iat' => $issued_at->getTimestamp(),
            'exp' => $issued_at->add(new \DateInterval("PT{$lifetime}S"))->getTimestamp()
        ];

        $data = array_merge($data, $default_data);
        ksort($data);

        $secret = 'wprMD6If8MmwcHP0p19RAfX0gXvBbl6N5EmotfAH0oO3FnZfRZGZomzP93izsyN';
        return JWT::encode($data, $secret);
    }
}
