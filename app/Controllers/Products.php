<?php

namespace App\Controllers;

use App\Libraries\ImageUploader;
use App\Models\ProductModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Images\Exceptions\ImageException;

class Products extends BaseController
{
    private array $rules = [
        'name' => [
            'label' => 'Product Name',
            'rules' => 'required|max_length[100]',
        ],
        'price' => [
            'label'  => 'Price',
            'rules'  => ['required', 'regex_match[/^\d{1,8}(\.\d{1,2})?$/]', 'greater_than[0]'],
            'errors' => [
                'regex_match' => 'The Price must be an amount like 49.99, with at most 2 decimal places.',
            ],
        ],
        'stock_quantity' => [
            'label' => 'Stock Quantity',
            'rules' => 'required|is_natural|less_than_equal_to[1000000]',
        ],
    ];

    public function index(): string
    {
        return $this->render('products/index', [
            'title'    => 'Products',
            'products' => model(ProductModel::class)->orderBy('name')->findAll(),
        ]);
    }

    public function new(): string
    {
        return $this->form();
    }

    public function create(): RedirectResponse|string
    {
        return $this->save();
    }

    public function edit(int $id): string
    {
        return $this->form($this->findOr404(model(ProductModel::class), $id));
    }

    public function update(int $id): RedirectResponse|string
    {
        return $this->save($this->findOr404(model(ProductModel::class), $id));
    }

    public function delete(int $id): RedirectResponse
    {
        $product = $this->findOr404(model(ProductModel::class), $id);

        model(ProductModel::class)->delete($id);

        return redirect()->to('products')->with('success', "{$product['name']} was archived.");
    }

    private function form(array $product = []): string
    {
        $isNew = ! isset($product['id']);

        return $this->render('products/form', [
            'title'   => $isNew ? 'New Product' : 'Edit Product',
            'action'  => site_url($isNew ? 'products' : 'products/' . $product['id']),
            'product' => $product,
        ]);
    }

    /**
     * Validates the form, then creates a product or updates the one being edited.
     */
    private function save(array $product = []): RedirectResponse|string
    {
        $uploader = new ImageUploader('image', 'Product Image', ProductModel::IMAGE_DIR, 400);
        $image    = $uploader->file($this->request);
        $rules    = $this->rules + ($image !== null ? $uploader->rules() : []);

        if (! $this->validate($rules)) {
            return $this->form($product);
        }

        $data = $this->request->getPost(['name', 'price', 'stock_quantity']);

        if ($image !== null) {
            try {
                $data['image'] = $uploader->store($image);
            } catch (ImageException) {
                $this->validator->setError('image', $uploader->processingError());

                return $this->form($product);
            }
        }

        if (! isset($product['id'])) {
            model(ProductModel::class)->insert($data);

            return redirect()->to('products')->with('success', 'Product added.');
        }

        model(ProductModel::class)->update($product['id'], $data);

        // The old image is no longer used once a new one is saved.
        if (isset($data['image'])) {
            $uploader->delete($product['image']);
        }

        return redirect()->to('products')->with('success', 'Product updated.');
    }
}
