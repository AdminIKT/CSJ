<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

class SettingsController extends Controller
{
    /**
     * @EntityManagerInterface
     */ 
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('settings.show', [
        ]); 
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        return view('settings.update', [
        ]); 
    }
}
