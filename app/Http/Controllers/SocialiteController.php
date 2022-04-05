<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Entities\User;

class SocialiteController extends BaseController
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } 
        catch (\Exception $e) {
            return response($e->getMessage(), 505);
        }

        $user = $this->em->getRepository(User::class)->findOneBy([
            'email' => $googleUser->email
        ]);

        if($user === null){
            return response("{$googleUser->email} Unauthorized", 403);
        }

        if (!$user->getGoogleId()) {
            $user->setGoogleId($googleUser->id)
                ->setName($googleUser->name)
                ->setAvatar($googleUser->avatar)
                ;

            $this->em->persist($user);
            $this->em->flush();
        }

        Auth::login($user, true);
        return redirect()->intended('/');
        /*
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
        } else {
            // create a new user
            $newUser                  = new User;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $newUser->google_id       = $user->id;
            $newUser->avatar          = $user->avatar;
            $newUser->avatar_original = $user->avatar_original;
            $newUser->save();
            auth()->login($newUser, true);
        }
        return redirect()->to('/home');
         */
    }
}
