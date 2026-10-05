<?php

namespace App\Models;

use App\Exceptions\SaleException;
use CodeIgniter\Model;
use Throwable;

class SaleModel extends Model
{
    protected $table         = 'sales';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['product_id', 'customer_id', 'sold_by', 'quantity', 'total_price', 'created_at'];

    // The sales table only has created_at, so updated_at is disabled.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Adds the product, customer, and staff names to the query, newest sale first.
     * Archived products and deleted customers or staff are still included.
     */
    public function withDetails(): static
    {
        return $this->select('sales.*, products.name AS product_name, customers.full_name AS customer_name, users.full_name AS staff_name')
            ->join('products', 'products.id = sales.product_id')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->join('users', 'users.id = sales.sold_by')
            ->orderBy('sales.created_at', 'DESC')
            ->orderBy('sales.id', 'DESC');
    }

    /**
     * Records a sale and decreases the product's stock in a single transaction.
     *
     * @return array{product: string, quantity: int, total_price: float}
     *
     * @throws SaleException When the product or customer is unavailable, or there isn't enough stock.
     */
    public function record(int $productId, ?int $customerId, int $soldBy, int $quantity): array
    {
        $product = model(ProductModel::class)->find($productId);

        if ($product === null) {
            throw new SaleException('The selected product is no longer available.');
        }

        if ($customerId !== null && model(CustomerModel::class)->find($customerId) === null) {
            throw new SaleException('The selected customer no longer exists.');
        }

        if ($quantity > $product['stock_quantity']) {
            throw SaleException::forInsufficientStock($product);
        }

        $totalPrice = round($product['price'] * $quantity, 2);

        $this->db->transBegin();

        try {
            // The stock condition makes this update match no rows if another sale
            // used up the stock after the check above, so stock never goes negative.
            $this->db->table('products')
                ->where('id', $productId)
                ->where('deleted_at', null)
                ->where('stock_quantity >=', $quantity)
                ->decrement('stock_quantity', $quantity);

            if ($this->db->affectedRows() === 0) {
                throw new SaleException("Not enough stock for {$product['name']}. Please check the stock and try again.");
            }

            $this->insert([
                'product_id'  => $productId,
                'customer_id' => $customerId,
                'sold_by'     => $soldBy,
                'quantity'    => $quantity,
                'total_price' => $totalPrice,
            ]);

            $this->db->transCommit();
        } catch (Throwable $e) {
            $this->db->transRollback();

            throw $e;
        }

        return [
            'product'     => $product['name'],
            'quantity'    => $quantity,
            'total_price' => $totalPrice,
        ];
    }
}
