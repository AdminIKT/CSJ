<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entities\Settings;

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
    public function index()
    {
        return view('settings.index', [
            'collection' => $this->em->getRepository(Settings::class)->findAll(),
        ]); 
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function edit(Settings $setting)
    {
        return view('settings.edit', [
            'entity' => $setting,
        ]); 
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function update(Settings $setting, Request $request)
    {
        //dd($request->all());
        $values = $request->validate(['value' => ['required']]);
        $setting->setValue($values['value']);
        $this->em->flush();
        return redirect()->route('settings.index')
                         ->with('success', 'Successfully updated');
    }
}
