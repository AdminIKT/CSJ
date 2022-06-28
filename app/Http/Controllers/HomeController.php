<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        return view('home', [
        ]); 
    }
}
