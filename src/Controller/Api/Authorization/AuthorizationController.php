<?php
declare(strict_types=1);

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Bundle\StudioApiBundle\Controller\Api\Authorization;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use Pimcore\Bundle\StudioApiBundle\Attributes\Request\CredentialsRequestBody;
use Pimcore\Bundle\StudioApiBundle\Attributes\Request\TokenRequestBody;
use Pimcore\Bundle\StudioApiBundle\Attributes\Response\SuccessResponse;
use Pimcore\Bundle\StudioApiBundle\Attributes\Response\UnauthorizedResponse;
use Pimcore\Bundle\StudioApiBundle\Config\Tags;
use Pimcore\Bundle\StudioApiBundle\Controller\Api\AbstractApiController;
use Pimcore\Bundle\StudioApiBundle\Dto\Credentials;
use Pimcore\Bundle\StudioApiBundle\Dto\Token;
use Pimcore\Bundle\StudioApiBundle\Dto\Token\Refresh;
use Pimcore\Bundle\StudioApiBundle\Service\SecurityServiceInterface;
use Pimcore\Bundle\StudioApiBundle\Service\TokenServiceInterface;
use Pimcore\Security\User\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class AuthorizationController extends AbstractApiController
{
    public function __construct(
        private readonly SecurityServiceInterface $securityService,
        private readonly TokenServiceInterface $tokenService,
        SerializerInterface $serializer
    ) {
        parent::__construct($serializer);
    }

    #[Route('/login', name: 'pimcore_studio_api_login', methods: ['POST'])]
    #[POST(
        path: self::API_PATH . '/login',
        summary: 'Login with user credentials and get access token',
        tags: [Tags::Authorization->name]
    )]
    #[CredentialsRequestBody]
    #[SuccessResponse(
        description: 'Token, lifetime and user identifier',
        content: new JsonContent(ref: Token::class)
    )]
    #[UnauthorizedResponse]
    public function login(#[MapRequestPayload] Credentials $credentials): JsonResponse
    {
        /** @var User $user */
        $user = $this->securityService->authenticateUser($credentials);

        $token = $this->tokenService->generateAndSaveToken($user->getUserIdentifier());

        return $this->jsonResponse(new Token($token, $this->tokenService->getLifetime(), $user->getUserIdentifier()));
    }

    #[Route('/refresh', name: 'pimcore_studio_api_refresh', methods: ['POST'])]
    #[POST(
        path: self::API_PATH . '/refresh',
        summary: 'Login with user credentials and get access token',
        tags: ['Authorization']
    )]
    #[TokenRequestBody]
    #[SuccessResponse(
        description: 'Token, lifetime and user identifier',
        content: new JsonContent(ref: Token::class)
    )]
    #[UnauthorizedResponse]
    public function refresh(#[MapRequestPayload] Refresh $refresh): JsonResponse
    {
        $token = $this->tokenService->refreshToken($refresh->getToken());

        return $this->jsonResponse(
            new Token(
                $token->getToken(),
                $this->tokenService->getLifetime(),
                $token->getUsername())
        );
    }
}
