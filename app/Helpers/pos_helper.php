<?php

use App\Models\ProductModel;
use App\Models\UserModel;

if (! function_exists('peso')) {
    /**
     * Formats an amount in Philippine pesos, e.g. ₱1,234.50.
     */
    function peso(float|string $amount): string
    {
        return '₱' . number_format((float) $amount, 2);
    }
}

if (! function_exists('avatar_url')) {
    /**
     * URL of a staff avatar, or of the placeholder when none was uploaded.
     */
    function avatar_url(?string $filename): string
    {
        return $filename
            ? base_url(UserModel::AVATAR_DIR . rawurlencode($filename))
            : base_url('images/avatar-placeholder.svg');
    }
}

if (! function_exists('product_image_url')) {
    /**
     * URL of a product image, or of the placeholder when none was uploaded.
     */
    function product_image_url(?string $filename): string
    {
        return $filename
            ? base_url(ProductModel::IMAGE_DIR . rawurlencode($filename))
            : base_url('images/product-placeholder.svg');
    }
}
