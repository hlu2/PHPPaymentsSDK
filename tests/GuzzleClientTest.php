<?php
declare(strict_types=1);

namespace QuickBooksOnline\Tests;

use PHPUnit\Framework\TestCase;
use QuickBooksOnline\Payments\OAuth\{DiscoverySandboxURLs, DiscoveryURLs, OAuth2Authenticator, OAuth1Encrypter};
use QuickBooksOnline\Payments\HttpClients\Request\{RequestInterface, IntuitRequest, RequestFactory};
use QuickBooksOnline\Payments\HttpClients\core\{HttpClientInterface, GuzzleClient};

final class GuzzleClientTest extends TestCase
{
  private function createClient() : OAuth2Authenticator {
    $oauth2Helper = OAuth2Authenticator::create([
      'client_id' => 'L0vmMZIfwUBfv9PPM96dzMTYATnLs6TSAe5SyVkt1Z4MAsvlCU',
      'client_secret' => '2ZZnCnnDyoZxUlVCP1D9X7khxA3zuXMyJE4cHXdq',
      'redirect_uri' => 'https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl',
      'environment' => 'development'
    ]);
    return $oauth2Helper;
  }


  public function testExchangeCodeRequestSentByCurlClient(): void
  {
      $oauth2Helper = $this->createClient();
      $code = "L011557358660z3axu8cgM7YHVyRGAaU63Ap0hgtEzfdkgwu5d";
      $request = $oauth2Helper->createRequestToExchange($code);
      $client = new GuzzleClient();
      $response = $client->send($request);
      $this->assertEquals(
          $response->getUrl(),
          $request->getUrl()
      );
  }

  public function testSuccessRefreshTokenSentByGuzzleClient(): void
  {
    $oauth2Helper = $this->createClient();
    $token = "Q011566515029GAOmrmBNcRcwgromiuZUX6vUSZyKhueJIJEET";
    $request = $oauth2Helper->createRequestToRefresh($token);
    $client = new GuzzleClient();
    $response = $client->send($request);
    $array = json_decode($response->getBody(), true);
    $this->assertEquals(
        $token,
        $array["refresh_token"]
    );
  }


}
