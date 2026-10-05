<?php

namespace App\Controllers;

use App\Exceptions\SaleException;
use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\SaleModel;
use CodeIgniter\HTTP\RedirectResponse;

class Sales extends BaseController
{
    public function index(): string
    {
        $sales = model(SaleModel::class);

        return $this->render('sales/index', [
            'title' => 'Sales History',
            'sales' => $sales->withDetails()->paginate(20),
            'pager' => $sales->pager,
        ]);
    }

    public function new(): string
    {
        return $this->form();
    }

    public function create(): RedirectResponse|string
    {
        $rules = [
            'product_id' => [
                'label'  => 'Product',
                'rules'  => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => 'Please select a product.',
                ],
            ],
            'customer_id' => [
                'label' => 'Customer',
                'rules' => 'permit_empty|is_natural_no_zero',
            ],
            'quantity' => [
                'label' => 'Quantity',
                'rules' => 'required|is_natural_no_zero|less_than_equal_to[1000000]',
            ],
        ];

        if (! $this->validate($rules)) {
            return $this->form();
        }

        $customerId = $this->request->getPost('customer_id');

        try {
            $sale = model(SaleModel::class)->record(
                (int) $this->request->getPost('product_id'),
                ($customerId ?? '') === '' ? null : (int) $customerId,
                (int) current_user()['id'],
                (int) $this->request->getPost('quantity'),
            );
        } catch (SaleException $e) {
            return $this->form($e->getMessage());
        }

        $message = sprintf('Sale recorded: %d × %s for %s.', $sale['quantity'], $sale['product'], peso($sale['total_price']));

        return redirect()->to('sales/new')->with('success', $message);
    }

    private function form(?string $error = null): string
    {
        return $this->render('sales/new', [
            'title'     => 'Record Sale',
            'products'  => model(ProductModel::class)->orderBy('name')->findAll(),
            'customers' => model(CustomerModel::class)->orderBy('full_name')->findAll(),
            'error'     => $error,
        ]);
    }
}
