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
        $client->setAccessToken("eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..r4Tmp3d7MMF48XZpF_i3Lg.Yd9fJFNIthHOU99f4sbsKeEDyEpAWiQcVLpxTScnaUAR4HSyGLrhbz_ED0YvE6kHsTBGhEIiwccfBtVKmtsfrOWni6Ibtl3biyfOuOL5h4NQ8Q2jII1JHlGKKeN73Vo18ISYAyHEd_mBf5Cst2Z2VndfSaI_vBVZPpN50ckXZ-OkPT8Ng8SPo6oaG6UeDm6KXwiP-EeFaDMKJHe0UmF5C2PnOrCi7vbSEhSbGO5H6PGKDO7mCvvYLtEWBck7elbtjBUf1LR7TclK6ub3CoGtkck_XyE8GEjugC_2nEMSyJSomSBSHXjeg_xwsYRo69cij_SjXQ4_baEYqDJn7NhgPRWcckjt87DcYV74MkFbtiO9VUuQb59FEi4G50fvhRFT84yM-RnFGYnzuKDFq2oZlJuSVnFCi_fzd9n3kf4fzNqrCwsEeAnzjMkelDaWg_Wvuxc5w9o0PgKGWSS3q0Ut9OB4-nkcwI8H61k07QkV20biAr-WmWVRHXioxRUm0Pi8w6fMBEnyIuGNBkXyHbN4AWMOWATivVN7a__UW7SVQqvlEf7GzEE-lxut4VhCc_M6aWBdftK9okopXex5eWCfK-5QkoHuhrCDs73I0afrfILvNnVGphoMznfvQMwmZF9YCBtc9tNPIbRw6fNj6oKGnlfdynyR6x3DnYy2W_1tIrz13OkdtJsfq4bnzy9dfmWIRmI4FLcjZKWOFBynNaH4bQ.kCQiXbVryg4OZgA17IRVEw")
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
}
