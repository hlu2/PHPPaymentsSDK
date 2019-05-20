<?php
declare(strict_types=1);

namespace QuickBooksOnline\Payments\tests;

use PHPUnit\Framework\TestCase;
use QuickBooksOnline\Payments\Facade\CardBuilder;
use QuickBooksOnline\Payments\PaymentClient;

final class CardTest extends TestCase
{
    private function createInstance()
    {
      $client = new PaymentClient();
      $client->setAccessToken("eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..eWYIgqco8YBHdtixI7XO9A.XWqCxUjTISqQoN1AZpvYPnw5lTsb2OSAGfwGhFeoM0aKmTK56nWqXJwSvkJ_EyrMQD368oeY3jCZaMARRFz-mxW1eEd92gPSGcxHB_bpI4XDOCi_WM3hA_cRpPc3c1l6ENppZcr7mmVw_3-n2TINcZsVuiebQxkvT38_6WCI68SJEHVLCoLvFj8XuN8eVrLe2deMmPrRmXlJHiphXtQsC9iwlsNL09swvz4XONV2661BfmwS1g7_i4YJwk9spdOqlwOy_HkVquogL-_t8Kxuy12HhVsBTJG4D4lu_C6mK6gjW-FYw7f5JFEtp47hHDn1Nipi62xYUmNrDIUnAOvTgAeeuDoEnnDpzl-5uFRh12dujJtGPLOrYvntgVD22wcZpuDnuC9G-vZOQcXzvOY9Cyqk0TEoMrJv30tTXvn-DCg2AFJR2DDLj51TzOsxGKhzTQUnVt3Kv3Mjq0XIG0KLf4A5FlrUKcaJqHeZkD1E0QaSehSYWZb3zV3lb1shv65pP0jzF4hf1PGbkD7Al4o0voy705xMV47IZGxD8g3yZ1raq_6F2pFYqBPNqOvYoONkLBtZvbpdi1TjcEAQ-mkNSa84nPZzl6X6xBE77pLwPUby7nVaYLgy7dUnpnw90kcxH7z0qi9b7TVQariJX2i6rL--ZKtd5Ou3lwFPBJ-1pJEOtXnETbWdO0Oib-XTrYC_-DnW1RE1LcPBAA6qjlR6Fg.5B8JUVTopA1-k-q4FocE5Q")
             ->setEnviornment("sandbox");
      return $client;
    }

    private function createCardBody()
    {
        $cardBody = CardBuilder::buildFrom([
        "expMonth"=> "12",
            "address"=> [
              "postalCode"=> "44112",
              "city"=> "Richmond",
              "streetAddress"=> "1245 Hana Rd",
              "region"=> "VA",
              "country"=> "US"
            ],
            "number"=> "4131979708684369",
            "name"=> "Test User",
            "expYear"=> "2026"
      ]);
        return $cardBody;
    }

    private function createCardBody2()
    {
        $cardBody = CardBuilder::buildFrom([
        "expMonth"=> "11",
            "address"=> [
              "postalCode"=> "44112",
              "city"=> "Richmond",
              "streetAddress"=> "White Street 132",
              "region"=> "VA",
              "country"=> "US"
            ],
            "number"=> "4948759199127257",
            "name"=> "Sophia Perez",
            "expYear"=> "2022"
      ]);
        return $cardBody;
    }


    public function testCreateCardRequestOnSandbox(): void
    {
        $client = $this->createInstance();
        $card = $this->createCardBody();
        $clientId = rand();
        $response = $client->createCard($card, $clientId, rand() . "abd");
        $cardResponse = $response->getBody();
        $this->assertEquals(
            $cardResponse->name,
            $card->name
          );

        $this->assertEquals(
            $cardResponse->expYear,
            $card->expYear
          );
    }

    public function testDeleteCardRequestOnSandbox(): void
    {
        $client = $this->createInstance();
        $card = $this->createCardBody();
        $customerId = rand();
        $response = $client->createCard($card, $customerId, rand() . "abd");
        $cardResponse = $response->getBody();
        $response = $client->deleteCard($customerId, $cardResponse->id, rand() . "abd");
        $this->assertEquals(
            $response->getStatusCode(),
            "204"
          );

        $this->assertEmpty($response->getBody());
    }

    public function testallallCardsOnSandbox(): void
    {
        $client = $this->createInstance();
        $card = $this->createCardBody();
        $card2 = $this->createCardBody2();
        $customerId = rand();
        $response = $client->createCard($card, $customerId);
        $id1 = $response->getBody()->id;
        $response = $client->createCard($card2, $customerId);
        $id2 = $response->getBody()->id;

        $response = $client->getAllCardsFor($customerId);
        $body = $response->getBody();
        $card1 = $body[0];
        $card2 = $body[1];

        $this->assertEquals(
                $card1->id,
                $id2
        );

        $this->assertEquals(
                  $card2->id,
                  $id1
        );

        $client->deleteCard($customerId, $id1);
        $client->deleteCard($customerId, $id2);
    }

    public function testCreateCardToken(): void
    {
        $client = $this->createInstance();
        $card = $this->createCardBody();
        $response = $client->createToken($card);
        $value = $response->getBody()->value;
        $customerId = rand();
        $response = $client->createCardFromToken($customerId, $value);
        $this->assertEquals(
                    $card->expMonth,
                    $response->getBody()->expMonth
            );

            $this->assertEquals(
              $card->name,
              $response->getBody()->name
            );
    }
}
