<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use QuickBooksOnline\Payments\Facade\FacadeConverter;


final class FacadeTest extends TestCase
{
    public function testJsonDecode(): void
    {
        $this->expectException(\RuntimeException::class);
        FacadeConverter::objectFrom("something, is not json}", "Charge");
    }
}
