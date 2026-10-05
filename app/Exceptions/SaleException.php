<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when a sale cannot be recorded. The message is safe to show to staff.
 */
class SaleException extends RuntimeException
{
    public static function forInsufficientStock(array $product): self
    {
        if ($product['stock_quantity'] <= 0) {
            return new self("{$product['name']} is out of stock.");
        }

        return new self("Not enough stock for {$product['name']}. Only {$product['stock_quantity']} left.");
    }
}
