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
     * @array
     */
    protected $years = [];

    /**
     * @int
     */
    protected $currentYear;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        $this->years = [date('Y') + 1 => date('Y') + 1];
        for ($i=0; $i<=10; $i++) 
            $this->years[date('Y') - $i] = date('Y') - $i;

        $this->currentYear = $em->getRepository(Settings::class)
                                ->findOneBy(['type' => Settings::TYPE_CURRENT_YEAR]);

        View::share(
            'currentYearOptions', 
            $this->years,
        );
        View::share(
            'currentYear', 
            $this->currentYear
        );

        $this->authorization();
    }

    /**
     *
     */
    abstract protected function authorization();
}
