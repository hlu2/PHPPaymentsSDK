<?php
declare(strict_types=1);

namespace QuickBooksOnline\Payments\tests;

use PHPUnit\Framework\TestCase;
use QuickBooksOnline\Payments\Facade\ECheckBuilder;
use QuickBooksOnline\Payments\PaymentClient;

final class ECheckTest extends TestCase
{
    private function createInstance()
    {
        $client = new PaymentClient();
        $client->setAccessToken("eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..eWYIgqco8YBHdtixI7XO9A.XWqCxUjTISqQoN1AZpvYPnw5lTsb2OSAGfwGhFeoM0aKmTK56nWqXJwSvkJ_EyrMQD368oeY3jCZaMARRFz-mxW1eEd92gPSGcxHB_bpI4XDOCi_WM3hA_cRpPc3c1l6ENppZcr7mmVw_3-n2TINcZsVuiebQxkvT38_6WCI68SJEHVLCoLvFj8XuN8eVrLe2deMmPrRmXlJHiphXtQsC9iwlsNL09swvz4XONV2661BfmwS1g7_i4YJwk9spdOqlwOy_HkVquogL-_t8Kxuy12HhVsBTJG4D4lu_C6mK6gjW-FYw7f5JFEtp47hHDn1Nipi62xYUmNrDIUnAOvTgAeeuDoEnnDpzl-5uFRh12dujJtGPLOrYvntgVD22wcZpuDnuC9G-vZOQcXzvOY9Cyqk0TEoMrJv30tTXvn-DCg2AFJR2DDLj51TzOsxGKhzTQUnVt3Kv3Mjq0XIG0KLf4A5FlrUKcaJqHeZkD1E0QaSehSYWZb3zV3lb1shv65pP0jzF4hf1PGbkD7Al4o0voy705xMV47IZGxD8g3yZ1raq_6F2pFYqBPNqOvYoONkLBtZvbpdi1TjcEAQ-mkNSa84nPZzl6X6xBE77pLwPUby7nVaYLgy7dUnpnw90kcxH7z0qi9b7TVQariJX2i6rL--ZKtd5Ou3lwFPBJ-1pJEOtXnETbWdO0Oib-XTrYC_-DnW1RE1LcPBAA6qjlR6Fg.5B8JUVTopA1-k-q4FocE5Q")
             ->setEnviornment("sandbox");
        return $client;
    }

    private function createECheckBody()
    {
        $echeckBody = ECheckBuilder::buildFrom([
          "bankAccount"=> [
       "phone"=> "1234567890",
       "routingNumber"=> "490000018",
       "name"=> "Fname LName",
       "accountType"=> "PERSONAL_CHECKING",
       "accountNumber"=> "11000000333456781"
     ],
     "description"=> "Check Auth test call",
     "checkNumber"=> "12345678",
     "paymentMode"=> "WEB",
     "amount"=> "1.11",
     "context"=> [
       "deviceInfo"=> [
         "macAddress"=> "macaddress",
         "ipAddress"=> "34",
         "longitude"=> "longitude",
         "phoneNumber"=> "phonenu",
         "latitude"=> "",
         "type"=> "type",
         "id"=> "1"
       ]
     ]
    ]);
        return $echeckBody;
    }

    public function testRetrieveECheck() : void
    {
        $client = $this->createInstance();
        $echeckBody = $this->createECheckBody();
        $response = $client->debit($echeckBody);
        $id = $response->getBody()->id;
        $response = $client->retrieveECheck($id);
        $this->assertEquals(
            $response->getBody()->id,
            $id
        );
    }

    public function testCreateDebit() : void
    {
        $client = $this->createInstance();
        $echeckBody = $this->createECheckBody();
        $response = $client->debit($echeckBody);
        $this->assertEquals(
            $response->getBody()->checkNumber,
            $echeckBody->checkNumber
        );
        $this->assertEquals(
            $response->getBody()->amount,
            $echeckBody->amount
        );
    }


}
