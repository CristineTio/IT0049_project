<?php

namespace App\Controllers;

use App\Libraries\ImageUploader;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Images\Exceptions\ImageException;

/**
 * Staff account management.
 */
class Users extends BaseController
{
    public function index(): string
    {
        return $this->render('users/index', [
            'title' => 'Staff',
            'users' => model(UserModel::class)->orderBy('full_name')->findAll(),
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
        return $this->form($this->findOr404(model(UserModel::class), $id));
    }

    public function update(int $id): RedirectResponse|string
    {
        return $this->save($this->findOr404(model(UserModel::class), $id));
    }

    public function delete(int $id): RedirectResponse
    {
        $user = $this->findOr404(model(UserModel::class), $id);

        if ($id === (int) current_user()['id']) {
            return redirect()->to('users')->with('error', 'You cannot delete your own account while logged in.');
        }

        model(UserModel::class)->delete($id);

        return redirect()->to('users')->with('success', "{$user['full_name']} was removed from staff.");
    }

    private function form(array $user = []): string
    {
        $isNew = ! isset($user['id']);

        return $this->render('users/form', [
            'title'  => $isNew ? 'New Staff Member' : 'Edit Staff Member',
            'action' => site_url($isNew ? 'users' : 'users/' . $user['id']),
            'user'   => $user,
        ]);
    }

    /**
     * Validates the form, then creates a staff member or updates the one being edited.
     */
    private function save(array $user = []): RedirectResponse|string
    {
        $isNew    = ! isset($user['id']);
        $uploader = new ImageUploader('avatar', 'Profile Picture', UserModel::AVATAR_DIR, 300);
        $avatar   = $uploader->file($this->request);
        $rules    = $this->rules($user['id'] ?? null) + ($avatar !== null ? $uploader->rules() : []);

        if (! $this->validate($rules)) {
            return $this->form($user);
        }

        $data     = $this->request->getPost(['username', 'full_name']);
        $password = (string) $this->request->getPost('password');

        // A blank password keeps the current one. UserModel hashes it before saving.
        if ($password !== '') {
            $data['password'] = $password;
        }

        if ($avatar !== null) {
            try {
                $data['avatar'] = $uploader->store($avatar);
            } catch (ImageException) {
                $this->validator->setError('avatar', $uploader->processingError());

                return $this->form($user);
            }
        }

        if ($isNew) {
            model(UserModel::class)->insert($data);

            return redirect()->to('users')->with('success', 'Staff member added.');
        }

        model(UserModel::class)->update($user['id'], $data);

        // The old avatar is no longer used once a new one is saved.
        if (isset($data['avatar'])) {
            $uploader->delete($user['avatar']);
        }

        return redirect()->to('users')->with('success', 'Staff member updated.');
    }

    /**
     * Validation rules for the staff form. A password is required for new staff;
     * when editing, it can be left blank to keep the current one.
     */
    private function rules(int|string|null $id): array
    {
        $unique = $id === null
            ? 'is_unique[users.username]'
            : 'is_unique[users.username,id,' . (int) $id . ']';

        return [
            'username' => [
                'label'  => 'Username',
                'rules'  => ['required', 'max_length[50]', 'alpha_dash', $unique],
                'errors' => [
                    'is_unique' => 'That username is already taken.',
                ],
            ],
            'full_name' => [
                'label' => 'Full Name',
                'rules' => 'required|max_length[100]',
            ],
            'password' => [
                'label' => 'Password',
                'rules' => ($id === null ? 'required' : 'permit_empty') . '|min_length[8]|max_length[72]',
            ],
            'password_confirm' => [
                'label'  => 'Confirm Password',
                'rules'  => 'matches[password]',
                'errors' => [
                    'matches' => 'The passwords do not match.',
                ],
            ],
        ];
    }
}
