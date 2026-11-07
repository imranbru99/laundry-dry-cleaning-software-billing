<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_exclusive_tax_math(): void
    {
        $result = Money::applyTax(10000, 500, false);
        $this->assertSame(10000, $result['subtotal']);
        $this->assertSame(500, $result['tax']);
        $this->assertSame(10500, $result['total']);
    }

    public function test_inclusive_tax_math(): void
    {
        $result = Money::applyTax(10500, 500, true);
        $this->assertSame(10500, $result['total']);
        $this->assertSame(500, $result['tax']);
        $this->assertSame(10000, $result['subtotal']);
    }
}
