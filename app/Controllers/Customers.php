<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\HTTP\RedirectResponse;

class Customers extends BaseController
{
    private array $rules = [
        'full_name' => [
            'label' => 'Full Name',
            'rules' => 'required|max_length[100]',
        ],
        'email' => [
            'label' => 'Email',
            'rules' => 'required|valid_email|max_length[100]',
        ],
        'phone' => [
            'label'  => 'Phone',
            'rules'  => ['permit_empty', 'max_length[20]', 'regex_match[/^[0-9+()\s-]+$/]'],
            'errors' => [
                'regex_match' => 'The Phone field may only contain numbers, spaces, and the characters + ( ) -.',
            ],
        ],
    ];

    public function index(): string
    {
        return $this->render('customers/index', [
            'title'     => 'Customers',
            'customers' => model(CustomerModel::class)->orderBy('full_name')->findAll(),
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
        return $this->form($this->findOr404(model(CustomerModel::class), $id));
    }

    public function update(int $id): RedirectResponse|string
    {
        return $this->save($this->findOr404(model(CustomerModel::class), $id));
    }

    public function delete(int $id): RedirectResponse
    {
        $customer = $this->findOr404(model(CustomerModel::class), $id);

        model(CustomerModel::class)->delete($id);

        return redirect()->to('customers')->with('success', "{$customer['full_name']} was deleted.");
    }

    private function form(array $customer = []): string
    {
        $isNew = ! isset($customer['id']);

        return $this->render('customers/form', [
            'title'    => $isNew ? 'New Customer' : 'Edit Customer',
            'action'   => site_url($isNew ? 'customers' : 'customers/' . $customer['id']),
            'customer' => $customer,
        ]);
    }

    /**
     * Validates the form, then creates a customer or updates the one being edited.
     */
    private function save(array $customer = []): RedirectResponse|string
    {
        if (! $this->validate($this->rules)) {
            return $this->form($customer);
        }

        $data = $this->request->getPost(['full_name', 'email', 'phone']);

        // Store a blank phone number as NULL instead of an empty string.
        if (($data['phone'] ?? '') === '') {
            $data['phone'] = null;
        }

        if (! isset($customer['id'])) {
            model(CustomerModel::class)->insert($data);

            return redirect()->to('customers')->with('success', 'Customer added.');
        }

        model(CustomerModel::class)->update($customer['id'], $data);

        return redirect()->to('customers')->with('success', 'Customer updated.');
    }
}
