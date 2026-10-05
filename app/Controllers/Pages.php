<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function landing(): string
    {
        return $this->render('pages/landing', [
            'title' => 'Home',
            'user'  => current_user(),
        ]);
    }

    public function about(): string
    {
        return $this->render('pages/about', ['title' => 'About']);
    }
}
