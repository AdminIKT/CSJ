<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Doctrine\ORM\EntityManagerInterface;

use App\Entities\Settings;

abstract class BaseController extends Controller
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

        //FIXME
        $years = [date('Y') + 1 => date('Y') + 1];
        for ($i=0; $i<=10; $i++) $years[date('Y') - $i] = date('Y') - $i;

        View::share(
            'currentYearOptions', 
            $years,
        );
        View::share(
            'currentYear', 
            $em->getRepository(Settings::class)->findOneBy(['type' => Settings::TYPE_CURRENT_YEAR])
        );
    }
}
