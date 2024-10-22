<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entities\Settings;

class SettingsController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->authorizeResource(Settings::class, 'setting');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = app('em') ->getRepository(Settings::class)
                      ->createQueryBuilder('d')
                      ->where('d.type >= :type1')
                      ->andWhere('d.type <= :type2')
                      ->setParameter('type1', Settings::TYPE_ORDER_ESTIMATED_LIMIT)
                      ->setParameter('type2', Settings::TYPE_SUPPLIER_RECOMMENDABLE_LIMIT)
                      ->getQuery()
                      ->getResult();

        return view('settings.index', [
            'collection' => $collection,
            //'collection' => $this->em->getRepository(Settings::class)->findAll(),
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
        return redirect()->to($dst)->with('success', __('Successfully updated'));
    }
}
