<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entities\Settings;

class SettingsController extends BaseController
{
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
        $values = $request->validate(['value' => ['required']]);
        $setting->setValue($values['value']);
        $this->em->flush();
        $dst = $request->get('destination', route('settings.index'));
        return redirect()->to($dst)->with('success', 'Successfully updated');
    }
}
