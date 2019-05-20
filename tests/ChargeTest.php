<?php
declare(strict_types=1);

namespace QuickBooksOnline\Payments\tests;

use PHPUnit\Framework\TestCase;
use QuickBooksOnline\Payments\Facade\ChargeBuilder;
use QuickBooksOnline\Payments\PaymentClient;

final class ChargeTest extends TestCase
{
    private function createInstance()
    {
        $client = new PaymentClient();
        $client->setAccessToken("eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..eWYIgqco8YBHdtixI7XO9A.XWqCxUjTISqQoN1AZpvYPnw5lTsb2OSAGfwGhFeoM0aKmTK56nWqXJwSvkJ_EyrMQD368oeY3jCZaMARRFz-mxW1eEd92gPSGcxHB_bpI4XDOCi_WM3hA_cRpPc3c1l6ENppZcr7mmVw_3-n2TINcZsVuiebQxkvT38_6WCI68SJEHVLCoLvFj8XuN8eVrLe2deMmPrRmXlJHiphXtQsC9iwlsNL09swvz4XONV2661BfmwS1g7_i4YJwk9spdOqlwOy_HkVquogL-_t8Kxuy12HhVsBTJG4D4lu_C6mK6gjW-FYw7f5JFEtp47hHDn1Nipi62xYUmNrDIUnAOvTgAeeuDoEnnDpzl-5uFRh12dujJtGPLOrYvntgVD22wcZpuDnuC9G-vZOQcXzvOY9Cyqk0TEoMrJv30tTXvn-DCg2AFJR2DDLj51TzOsxGKhzTQUnVt3Kv3Mjq0XIG0KLf4A5FlrUKcaJqHeZkD1E0QaSehSYWZb3zV3lb1shv65pP0jzF4hf1PGbkD7Al4o0voy705xMV47IZGxD8g3yZ1raq_6F2pFYqBPNqOvYoONkLBtZvbpdi1TjcEAQ-mkNSa84nPZzl6X6xBE77pLwPUby7nVaYLgy7dUnpnw90kcxH7z0qi9b7TVQariJX2i6rL--ZKtd5Ou3lwFPBJ-1pJEOtXnETbWdO0Oib-XTrYC_-DnW1RE1LcPBAA6qjlR6Fg.5B8JUVTopA1-k-q4FocE5Q")
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
