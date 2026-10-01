<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Images\Exceptions\ImageException;

class Users extends BaseController
{
    protected $helpers = ['form'];

    /**
     * Folder (inside public/) where prepared avatar thumbnails are stored.
     */
    private const AVATAR_DIR = 'uploads/avatars/';

    private const AVATAR_RULES = [
        'label' => 'Profile Picture',
        'rules' => [
            'max_size[avatar,2048]',
            'is_image[avatar]',
            'mime_in[avatar,image/jpg,image/jpeg,image/png]',
            'ext_in[avatar,jpg,jpeg,png]',
        ],
        'errors' => [
            'max_size' => 'The Profile Picture must not be larger than 2MB.',
            'is_image' => 'The Profile Picture must be a JPG or PNG image.',
            'mime_in'  => 'The Profile Picture must be a JPG or PNG image.',
            'ext_in'   => 'The Profile Picture must be a JPG or PNG image.',
        ],
    ];

    public function index(): string
    {
        return $this->render('users/index', [
            'title' => 'User Accounts',
            'users' => model(UserModel::class)->findAll(),
        ]);
    }

    public function new(): string
    {
        return $this->render('users/form', [
            'title'  => 'New User',
            'action' => site_url('users'),
            'user'   => [],
        ]);
    }

    public function create(): RedirectResponse|string
    {
        if (! $this->validate($this->rules())) {
            return $this->new();
        }

        model(UserModel::class)->insert($this->request->getPost(['username', 'full_name']));

        return redirect()->to('users')->with('success', 'User account created.');
    }

    public function edit(int $id): string
    {
        return $this->render('users/form', [
            'title'  => 'Edit User',
            'action' => site_url('users/' . $id),
            'user'   => $this->findUser($id),
        ]);
    }

    public function update(int $id): RedirectResponse|string
    {
        $user  = $this->findUser($id);
        $rules = $this->rules($id);

        // The avatar is optional, so its rules only apply when a file was chosen.
        $file      = $this->request->getFile('avatar');
        $hasAvatar = $file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE;

        if ($hasAvatar) {
            $rules['avatar'] = self::AVATAR_RULES;
        }

        if (! $this->validate($rules)) {
            return $this->edit($id);
        }

        $data = $this->request->getPost(['username', 'full_name']);

        if ($hasAvatar) {
            try {
                $data['avatar'] = $this->saveAvatar($file);
            } catch (ImageException) {
                $this->validator->setError('avatar', 'The Profile Picture could not be processed. Please try another image.');

                return $this->edit($id);
            }
        }

        model(UserModel::class)->update($id, $data);

        // The old thumbnail is no longer referenced once a new one is saved.
        if ($hasAvatar && $user['avatar'] !== null) {
            $this->deleteAvatar($user['avatar']);
        }

        return redirect()->to('users')->with('success', 'User account updated.');
    }

    /**
     * Validation rules for the username and full name. When editing, the
     * user's own row is ignored by the unique username check.
     */
    private function rules(?int $id = null): array
    {
        $unique = $id === null
            ? 'is_unique[users.username]'
            : "is_unique[users.username,id,{$id}]";

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
        ];
    }

    private function findUser(int $id): array
    {
        $user = model(UserModel::class)->find($id);

        if ($user === null) {
            throw PageNotFoundException::forPageNotFound('User not found.');
        }

        return $user;
    }

    /**
     * Prepares a 300x300 display-ready thumbnail of the uploaded avatar,
     * stores it in public/uploads/avatars, and returns its filename.
     */
    private function saveAvatar(UploadedFile $file): string
    {
        $filename = $file->getRandomName();

        service('image')
            ->withFile($file->getTempName())
            ->reorient(true)
            ->fit(300, 300, 'center')
            ->save(FCPATH . self::AVATAR_DIR . $filename);

        return $filename;
    }

    private function deleteAvatar(string $filename): void
    {
        $path = FCPATH . self::AVATAR_DIR . basename($filename);

        if (is_file($path)) {
            unlink($path);
        }
    }
}
