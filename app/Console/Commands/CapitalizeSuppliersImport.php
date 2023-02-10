<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Doctrine\ORM\EntityManagerInterface;
use App\Entities\Supplier;

class CapitalizeSuppliersImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suppliers:capitalize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capitalize supplier names for importation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $suppliers = $this->em->getRepository(Supplier::class)->findAll();

        foreach ($suppliers as $supplier) { 
            /*$this->line("{$supplier->getId()}
    <fg=green>Name</>:    <fg=white;bg=black>{$supplier->getName()}</> => <fg=default;bg=black>{$this->getCapitalizedStr($supplier->getName())}</>
    <fg=green>Address</>: <fg=white;bg=black>{$supplier->getAddress()}</> => <fg=default;bg=black>{$this->getCapitalizedStr($supplier->getAddress())}</>
    <fg=green>City</>:    <fg=white;bg=black>{$supplier->getCity()} ({$supplier->getRegion()})</> => <fg=default;bg=black>{$this->getCapitalizedStr($supplier->getCity())} ({$this->getCapitalizedStr($supplier->getRegion())})</>
            ");*/

            $supplier->setName($this->getCapitalizedStr($supplier->getName()));
            $supplier->setAddress($this->getCapitalizedStr($supplier->getAddress()));
            $supplier->setCity($this->getCapitalizedStr($supplier->getCity()));
            $supplier->setRegion($this->getCapitalizedStr($supplier->getRegion()));
            foreach ($supplier->getContacts() as $supplierContact) { 
                $supplierContact->setName($this->getCapitalizedStr($supplierContact->getName()));
            }

        }

        $this->em->flush();

        /*foreach ($suppliers as $supplier) {
            $values = [
                'name' => $supplier->getName(),
                'address' => $supplier->getAddress(),
                'city' => $supplier->getCity(),
                'region' => $supplier->getRegion(),
            ];
            $this->line($supplier->getId());
            foreach ($values as $key => $value) {
                $this->info("\t $key: '$value' => '{$this->getCapitalizedStr($value)}");
            }
        }*/
        return 0;
    }

    /**
     * @param string $values
     * @return string
     */
    protected function getCapitalizedStr($value)
    {
        $replaces = [
            ' De '  => ' de ',
            ' A '   => ' a ',
            ' Y '   => ' y ',
            'C/'    => '',
            'Coop'  => 'COOP',
            'Dpt'   => 'DPT',
        ];

        $capitalized = mb_convert_case(strtolower($value), MB_CASE_TITLE);

        //Static words
        $capitalized = str_replace(array_keys($replaces), array_values($replaces), $capitalized);

        //White spaces
        $capitalized = preg_replace('!\s+!', ' ', $capitalized);

        //S.A, S.L, M.Martin, 2ªG, 2ºB, ...
        $capitalized = preg_replace_callback('/([\.|ª|º])(\w{1})/', function($m){
            return $m[1].ucfirst($m[2]);
        }, $capitalized);

        //XXI, VII, II, ...
        $capitalized = preg_replace_callback('/\s([x|X|i|I|v|V]{2,}\s)/', function($m){
            return strtoupper($m[0]);
        }, $capitalized);

        //Trim also with commas
        $capitalized = trim($capitalized, " \t\n\r\0\x0B\,\;");

        return $capitalized; 
    }
}
