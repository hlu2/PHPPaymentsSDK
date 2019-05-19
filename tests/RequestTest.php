<?php
declare(strict_types=1);

namespace QuickBooksOnline\Payments\tests;

use PHPUnit\Framework\TestCase;

use QuickBooksOnline\Payments\HttpClients\Request\{Request, RequestFactory, IntuitRequest};

final class RequestTest extends TestCase
{

  public function testCanCreateRequestThroughFactoryMethod(): void
  {
      $intuitRequest = RequestFactory::createStandardIntuitRequest();

      $this->assertInstanceOf(
          IntuitRequest::class,
          $intuitRequest
      );
  }

  public function testRequestMethod(): void
  {
      $intuitRequest = RequestFactory::createStandardIntuitRequest();
      $intuitRequest->setMethod(Request::GET);
      $this->assertEquals(
          "GET",
          $intuitRequest->getMethod()
      );
      $intuitRequest->setMethod(Request::POST);
      $this->assertEquals(
          "POST",
          $intuitRequest->getMethod()
      );
  }


}
