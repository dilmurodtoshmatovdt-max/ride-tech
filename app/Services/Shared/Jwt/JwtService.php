<?php

namespace App\Services\Shared\Jwt;

use App\Constants\TokenTypes;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Auth\User;
use Str;

class JwtService
{
    public function __construct(private JwtBuilderService $jwtBuilderService)
    {
    }

    public function createTemporaryToken($userId, CarbonInterface $ttl = null, $payload = null): string
    {
        return $this->jwtBuilderService
            ->issuedBy(config('app.url'))
            ->audience(config('app.name'))
            ->tokenType(TokenTypes::TEMPORARY)
            ->payload($payload)
            ->issuedAt(now()->getTimestamp())
            ->canOnlyBeUsedAfter(now())
            ->expiresAt($ttl ?? now()->addSeconds(config('jwt.temporary_token_ttl')))
            ->relatedTo($userId)
            ->sessionId(Str::random(20))
            ->generateToken();
    }
    public function createAccessToken($userId, CarbonInterface $ttl = null, $payload = null): string
    {
        return $this->jwtBuilderService
            ->issuedBy(config('app.url'))
            ->audience(config('app.name'))
            ->tokenType(TokenTypes::ACCESS)
            ->payload($payload)
            ->issuedAt(now()->timestamp)
            ->canOnlyBeUsedAfter(now())
            ->expiresAt($ttl ?? now()->addSeconds(config('jwt.access_token_ttl')))
            ->relatedTo($userId)
            ->sessionId(Str::random(20))
            ->generateToken();
    }

    public function createRefreshToken($userId, CarbonInterface $ttl = null, $payload = null): string
    {
        return $this->jwtBuilderService
            ->issuedBy(config('app.url'))
            ->audience(config('app.name'))
            ->tokenType(TokenTypes::REFRESH)
            ->payload($payload)
            ->issuedAt(now()->getTimestamp())
            ->canOnlyBeUsedAfter(now())
            ->expiresAt($ttl ?? now()->addSeconds(config('jwt.refresh_token_ttl')))
            ->relatedTo($userId)
            ->sessionId(Str::random(20))
            ->generateToken();
    }
}
