<?php
namespace App\Controllers;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function home()
    {
        // Don't check DB here — just show landing page
        if (session()->get('user')) {
            return redirect()->to('/movies');
        }
        return view('home');
    }

    public function register()
    {
        return view('register');
    }

    public function saveUser()
    {
        $model    = new UserModel();
        $username = trim($this->request->getPost('username'));
        $email    = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('confirm_password');

        if (!$username || !$email || !$password) {
            return redirect()->back()->with('error', 'All fields are required.');
        }

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        // ✅ NEW: Check duplicate username
        if ($model->where('username', $username)->first()) {
            return redirect()->back()->with('error', 'Username already taken. Please choose another.');
        }

        // Check duplicate email
        if ($model->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Email already registered.');
        }

        $model->save([
            'username' => $username,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/login')->with('success', 'Account created! Please log in.');
    }

    public function login()
    {
        if (session()->get('user')) {
            return redirect()->to('/movies');
        }
        return view('login');
    }

    public function checkLogin()
    {
        $model    = new UserModel();
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (!$email || !$password) {
            return redirect()->back()->with('error', 'Email and password are required.');
        }

        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'user'    => $user['username'],
                'user_id' => $user['id'],
            ]);
            return redirect()->to('/movies');
        }

        return redirect()->to('/login')->with('error', 'Invalid email or password.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
