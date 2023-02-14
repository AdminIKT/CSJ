<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\CSJDriveService,
    App\Entities\Account,
    App\Entities\Account\DriveFile;

class AccountCreateDiveFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:drive-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Drive folders';

    /**
     * @var CSJDriveService
     */
    protected $drive;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        EntityManagerInterface $em,
        CSJDriveService $drive 
    )
    {
        $this->em = $em;
        $this->drive = $drive;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $accounts = $this->em->getRepository(Account::class)->findAll();

        foreach ($accounts as $entity) {
            if ($entity->getFilesId(DriveFile::TYPE_INVOICE)) continue;

            try {
                $invoices  = $this->drive->getFolder($entity, DriveFile::TYPE_INVOICE);
                $this->line(__("Folder create for :acc", [
                    'acc' => $entity->getSerial(),
                ]));
            } catch (\Exception $e) {
                $this->error(__("Cannot create folder for :acc", [
                    'acc' => $entity->getSerial(),
                ]));
            }

            $entity->setFilesId($invoices->getId(), DriveFile::TYPE_INVOICE)
                   ->setFilesUrl($invoices->getWebViewLink(), DriveFile::TYPE_INVOICE);
        }

        $this->em->flush();
        return 0;
    }
}
