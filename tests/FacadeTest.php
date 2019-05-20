<?php
declare(strict_types=1);

namespace QuickBooksOnline\Tests;

use QuickBooksOnline\Payments\Facade\FacadeConverter;


final class FacadeTest extends TestCase
{
    public function testJsonDecode(): void
    {
        $this->expectException(\RuntimeException::class);
        FacadeConverter::objectFrom("something, is not json}", "Charge");
    }
}
