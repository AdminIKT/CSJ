<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    Illuminate\Support\Facades\Hash,
    Illuminate\Support\Facades\Auth;
use Doctrine\ORM\EntityManagerInterface;

use App\Entities\User;

class AuthController extends BaseController
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return redirect()->route('redirectToProvider');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);
 
        $er = $this->em->getRepository(User::class);
        if ($er->findOneBy(['email' => $credentials['email']]) === null) {
 
            $user = new User;
            $user->setEmail($credentials['email']);
            $user->setPassword(Hash::make($credentials['password']));
            $this->em->persist($user);
            $this->em->flush();
            Auth::loginUsingId($user->getId());
            return redirect()->intended('/');
        }
 
        return back()->withErrors([
            'email' => 'The provided email allready exists.',
        ]);
    }

    protected function authorization()
    {}
}
