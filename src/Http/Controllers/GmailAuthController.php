<?php


namespace BinomeWay\NovaContactTool\Http\Controllers;

use Dacastro4\LaravelGmail\LaravelGmailClass;
use Illuminate\Routing\Controller;

class GmailAuthController extends Controller
{
    private LaravelGmailClass $gmail;

    public function __construct()
    {
        $this->gmail = app('laravelgmail');
    }

    public function login()
    {
        return $this->gmail->redirect();
    }

    public function callback()
    {
        $token = $this->gmail->makeToken();

        return redirect()->to('/');
    }

    public function logout()
    {
        $this->gmail->logout(); //It returns exception if fails
        return redirect()->to('/');
    }
}
