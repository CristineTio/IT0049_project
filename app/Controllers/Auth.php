<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    public function login(): string
    {
        return $this->form();
    }

    public function attempt(): RedirectResponse|string
    {
        // Allow at most 10 login attempts per minute from the same IP address.
        $throttleKey = 'login_' . md5($this->request->getIPAddress());

        if (! service('throttler')->check($throttleKey, 10, MINUTE)) {
            return $this->form('Too many login attempts. Please wait a minute and try again.');
        }

        $rules = [
            'username' => ['label' => 'Username', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required'],
        ];

        if (! $this->validate($rules)) {
            return $this->form();
        }

        $user = model(UserModel::class)->findByCredentials(
            (string) $this->request->getPost('username'),
            (string) $this->request->getPost('password'),
        );

        if ($user === null) {
            return $this->form('Incorrect username or password.');
        }

        // A new session ID on login prevents session fixation.
        session()->regenerate(true);
        session()->set('user_id', $user['id']);

        return redirect()->to('/')->with('success', "Welcome back, {$user['full_name']}!");
    }

    public function logout(): RedirectResponse
    {
        session()->remove('user_id');
        session()->regenerate(true);

        return redirect()->to('login')->with('success', 'You have been logged out.');
    }

    private function form(?string $error = null): string
    {
        return $this->render('auth/login', [
            'title' => 'Staff Login',
            'error' => $error,
        ]);
    }
}
