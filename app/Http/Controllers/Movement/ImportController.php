<?php

namespace App\Http\Controllers\Movement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use App\Entities\Order,
    App\Entities\Movement;

class ImportController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('movements.imports.form', [
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);
        $path = $request->file->path();
        $rows = FastExcel::import($path);
        $collection = [];
        foreach ($rows as $row) {
            $entity = new Movement;
            $entity->setCredit($row['credit'])
                   ->setInvoice($row['invoice']);

            $order = null;
            if (preg_match(
                Order::SEQUENCE_PATTERN, 
                $row['sequence'], 
                $matches)) 
            {
                $order = $this->em->getRepository(Order::class)
                              ->findOneBy(['sequence' => $matches[0]]);
                if ($order) {
                    $entity->setOrder($order);
                }
                $entity->setDetail(str_replace($matches[0], "", $row['sequence']));
            }
            if (!$order) {
                $entity->setDetail($row['sequence']);
            }
            $collection[] = $entity;
        }
        return view('movements.imports.list', [
            'collection' => $collection,
        ]); 
    }

    public function store(Request $request)
    {
        dd($request->all());
    } 
}