<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Customers extends BaseController
{
    protected $helpers = ['form'];

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
            'title'     => 'Customer Accounts',
            'customers' => model(CustomerModel::class)->findAll(),
        ]);
    }

    public function new(): string
    {
        return $this->render('customers/form', [
            'title'    => 'New Customer',
            'action'   => site_url('customers'),
            'customer' => [],
        ]);
    }

    public function create(): RedirectResponse|string
    {
        if (! $this->validate($this->rules)) {
            return $this->new();
        }

        model(CustomerModel::class)->insert($this->postedCustomer());

        return redirect()->to('customers')->with('success', 'Customer account created.');
    }

    public function edit(int $id): string
    {
        return $this->render('customers/form', [
            'title'    => 'Edit Customer',
            'action'   => site_url('customers/' . $id),
            'customer' => $this->findCustomer($id),
        ]);
    }

    public function update(int $id): RedirectResponse|string
    {
        $this->findCustomer($id);

        if (! $this->validate($this->rules)) {
            return $this->edit($id);
        }

        model(CustomerModel::class)->update($id, $this->postedCustomer());

        return redirect()->to('customers')->with('success', 'Customer account updated.');
    }

    private function findCustomer(int $id): array
    {
        $customer = model(CustomerModel::class)->find($id);

        if ($customer === null) {
            throw PageNotFoundException::forPageNotFound('Customer not found.');
        }

        return $customer;
    }

    private function postedCustomer(): array
    {
        $data = $this->request->getPost(['full_name', 'email', 'phone']);

        // Store a blank phone number as NULL instead of an empty string.
        if (($data['phone'] ?? '') === '') {
            $data['phone'] = null;
        }

        return $data;
    }
}
