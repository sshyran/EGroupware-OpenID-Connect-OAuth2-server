<?php
/**
 * EGroupware OpenID Connect / OAuth2 server
 *
 * Implement RFC7662 OAuth 2.0 Token Introspection
 * Until OAuth2 server pull request #925 is not merged:
 * @link https://github.com/thephpleague/oauth2-server/pull/925
 *
 * @link https://www.egroupware.org
 * @author Ralf Becker <rb-At-egroupware.org>
 * @package openid
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 *
 * Based on the following MIT Licensed packages:
 * @link https://github.com/steverhoades/oauth2-openid-connect-server
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @link https://github.com/thephpleague/oauth2-server
 */

namespace EGroupware\OpenID\ResponseTypes;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;

class BearerTokenIntrospectionResponse extends IntrospectionResponse
{
    /**
     * Add the token data to the response.
     *
     * @return array
     */
    protected function validIntrospectionResponse()
    {
        $token = $this->getTokenFromRequest();

        $responseParams = [
            'active' => true,
            'token_type' => 'access_token',
            'scope' => $token->getClaim('scopes', ''),
            'client_id' => $token->getClaim('aud'),
            'exp' => $token->getClaim('exp'),
            'iat' => $token->getClaim('iat'),
            'sub' => $token->getClaim('sub'),
            'jti' => $token->getClaim('jti'),
        ];

        return array_merge($this->getExtraParams(), $responseParams);
    }

    /**
     * Gets the token from the request body.
     *
     * @return Token
     */
    protected function getTokenFromRequest()
    {
        $jwt = $this->request->getParsedBody()['token'] ?? null;

        return (new Parser())
            ->parse($jwt);
    }
}
