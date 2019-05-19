<?php
declare(strict_types=1);

namespace QuickBooksOnline\Payments\tests;

use PHPUnit\Framework\TestCase;
use QuickBooksOnline\Payments\Facade\ChargeBuilder;
use QuickBooksOnline\Payments\PaymentClient;

final class ChargeTest extends TestCase
{
    // public function testCanBeCreatedFromValidEmailAddress(): void
    // {
    //      $paymentClient = new PaymentClient([
    //        'environment' => 'development'
    //        'accessToken' => '',
    //        'refreshToken' => ''
    //      ]);
    //
    //
    //      $paymentClient->setAccessToken()
    //                    ->setRefreshToken()
    //                    ->setEnvironment();
    //
    //      $chargeBody = ChargeBuilder::buildFrom([
    //         "amount" => "10.55",
    //         "currency" => "USD",
    //         "card" => [
    //             "name" => "emulate=0",
    //             "number" => "4111111111111111",
    //             "address" => [
    //               "streetAddress" => "1130 Kifer Rd",
    //               "city" => "Sunnyvale",
    //               "region" => "CA",
    //               "country" => "US",
    //               "postalCode" => "94086"
    //             ],
    //             "expMonth" => "02",
    //             "expYear" => "2020",
    //             "cvc" => "123"
    //         ],
    //         "context" => [
    //           "mobile" => "false",
    //           "isEcommerce" => "true"
    //         ]
    //       ]);
    //      $response = $client->getRefundDetail($chargeId, $refundId, $requestId);
    //
    //      $response = $client->debit($debitBody, $requestId);
    //      $response = $client->retrieveEcheckRefund($echeckId, $refundId, $requestId);
    //      $response = $client->getEcheck($echeckId, $requestId);
    //      $response = $client->voidEcheck($echeckId, $body, $requestId);
    //
    //      $response = $client->createToken($token, $requestId);
    //
    //
    //      //$json = FacadeConverter::getJsonFrom($chargeBody);
    // }
    private function createInstance()
    {
        $client = new PaymentClient();
        $client->setAccessToken("eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..r4Tmp3d7MMF48XZpF_i3Lg.Yd9fJFNIthHOU99f4sbsKeEDyEpAWiQcVLpxTScnaUAR4HSyGLrhbz_ED0YvE6kHsTBGhEIiwccfBtVKmtsfrOWni6Ibtl3biyfOuOL5h4NQ8Q2jII1JHlGKKeN73Vo18ISYAyHEd_mBf5Cst2Z2VndfSaI_vBVZPpN50ckXZ-OkPT8Ng8SPo6oaG6UeDm6KXwiP-EeFaDMKJHe0UmF5C2PnOrCi7vbSEhSbGO5H6PGKDO7mCvvYLtEWBck7elbtjBUf1LR7TclK6ub3CoGtkck_XyE8GEjugC_2nEMSyJSomSBSHXjeg_xwsYRo69cij_SjXQ4_baEYqDJn7NhgPRWcckjt87DcYV74MkFbtiO9VUuQb59FEi4G50fvhRFT84yM-RnFGYnzuKDFq2oZlJuSVnFCi_fzd9n3kf4fzNqrCwsEeAnzjMkelDaWg_Wvuxc5w9o0PgKGWSS3q0Ut9OB4-nkcwI8H61k07QkV20biAr-WmWVRHXioxRUm0Pi8w6fMBEnyIuGNBkXyHbN4AWMOWATivVN7a__UW7SVQqvlEf7GzEE-lxut4VhCc_M6aWBdftK9okopXex5eWCfK-5QkoHuhrCDs73I0afrfILvNnVGphoMznfvQMwmZF9YCBtc9tNPIbRw6fNj6oKGnlfdynyR6x3DnYy2W_1tIrz13OkdtJsfq4bnzy9dfmWIRmI4FLcjZKWOFBynNaH4bQ.kCQiXbVryg4OZgA17IRVEw")
          ->setEnviornment("sandbox");
        return $client;
    }

    private function createChargeBody()
    {
        $chargeBody = ChargeBuilder::buildFrom([
            "amount" => "10.55",
            "currency" => "USD",
            "capture" => false,
            "card" => [
                "name" => "emulate=0",
                "number" => "4111111111111111",
                "address" => [
                  "streetAddress" => "1130 Kifer Rd",
                  "city" => "Sunnyvale",
                  "region" => "CA",
                  "country" => "US",
                  "postalCode" => "94086"
                ],
                "expMonth" => "02",
                "expYear" => "2020",
                "cvc" => "123"
            ],
            "context" => [
              "mobile" => "false",
              "isEcommerce" => "true"
            ]
                 ]);
        return $chargeBody;
    }

    private function createChargeBodyWithCapture()
    {
        $chargeBody = ChargeBuilder::buildFrom([
            "amount" => "10.55",
            "currency" => "USD",
            "card" => [
                "name" => "emulate=0",
                "number" => "4111111111111111",
                "address" => [
                  "streetAddress" => "1130 Kifer Rd",
                  "city" => "Sunnyvale",
                  "region" => "CA",
                  "country" => "US",
                  "postalCode" => "94086"
                ],
                "expMonth" => "02",
                "expYear" => "2020",
                "cvc" => "123"
            ],
            "context" => [
              "mobile" => "false",
              "isEcommerce" => "true"
            ]
                 ]);
        return $chargeBody;
    }



    private function createRefundBody()
    {
        $chargeBody = ChargeBuilder::buildFrom([
            "amount" => "10.55",
            "description" => "first refund",
            "id" => "E5753FS0CL2F"
        ]);
        return $chargeBody;
    }

    private function createCaptureBody()
    {
        $chargeBody = ChargeBuilder::buildFrom([
            "amount" => "10.55",
            "context" => [
                "mobile" => "false",
                "isEcommerce" => "true"
            ]
        ]);
        return $chargeBody;
    }

    public function testRequestId() : void {
       $client = $this->createInstance();
       $chargeBody = $this->createChargeBody();
       $requestId = rand() . "abd";
        $response = $client->charge($chargeBody, $requestId);
        //var_dump($response);
        $this->assertEquals(
            $response->getAssociatedRequest()->getHeader()['Request-Id'],
            $requestId
        );

          $response = $client->charge($chargeBody);
          $this->assertEquals(
              strlen($response->getAssociatedRequest()->getHeader()['Request-Id']),
              20
          );
    }

    public function testCreateChargeRequestOnSandbox(): void
    {
        $client = $this->createInstance();

        //No space in RequestId
        $chargeBody = $this->createChargeBody();
        $response = $client->charge($chargeBody, rand() . "abd");
        $chargeResponse = $response->getBody();
        $this->assertEquals(
            $chargeResponse->amount,
            $chargeBody->amount
        );

        $this->assertEquals(
            $chargeResponse->card->address->streetAddress,
            $chargeBody->card->address->streetAddress
        );
    }

    public function testGetCharge() :void
    {
        $client = $this->createInstance();

        $chargeBody = $this->createChargeBody();
        $response = $client->charge($chargeBody, rand() . "abd");
        $chargeResponse = $response->getBody();
        $id = $chargeResponse->id;

        $client->getHttpClient()->enableDebug();
        $response = $client->retrieveCharge($id, rand() . "abd");
        $information = $client->getHttpClient()->getDebugInfo();
        $this->assertEquals(
            $chargeResponse->id,
            $id
        );
    }

    public function testRefundCharge() :void
    {
        $client = $this->createInstance();

        $chargeBody = $this->createChargeBody();
        $response = $client->charge($chargeBody, rand() . "abd");
        $chargeResponse = $response->getBody();
        $id = $chargeResponse->id;
        $response = $client->refundCharge($this->createRefundBody(), $id, rand() . "abd");
        $refundResponse = $response->getBody();
        $this->assertEquals(
            $refundResponse->status,
            "ISSUED"
        );

        $this->assertEquals(
            $refundResponse->amount,
            $chargeBody->amount
        );
    }

    public function testCaptureCharge() : void
    {
        $client = $this->createInstance();
        $chargeBody = $this->createChargeBody();
        $response = $client->charge($chargeBody, rand() . "abd");
        $chargeResponse = $response->getBody();
        $id = $chargeResponse->id;
        $response = $client->captureCharge($this->createCaptureBody(), $id, rand() . "abd");
        $refundResponse = $response->getBody();
        $this->assertEquals(
            $refundResponse->status,
            "CAPTURED"
         );
    }

    public function testRefundById() : void
    {
        $client = $this->createInstance();

        $chargeBody = $this->createChargeBodyWithCapture();
        $response = $client->charge($chargeBody, rand() . "abd");
        $chargeResponse = $response->getBody();
        $id = $chargeResponse->id;
        $response = $client->refundCharge($this->createRefundBody(), $id, rand() . "abd");
        $refundResponse = $response->getBody();
        $chargeId = $refundResponse->id;
        $response = $client->getRefundDetail($id, $chargeId, rand() . "abd");
        $refundResponse = $response->getBody();
        $this->assertEquals(
            $refundResponse->id,
            $chargeId
         );
    }


}
