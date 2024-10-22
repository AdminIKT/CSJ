<?php

namespace App\Services;

use App\Entities\Order,
    App\Entities\Account,
    App\Entities\Account\DriveFile,
    App\Entities\Account\EstimateDriveFile,
    App\Entities\Account\InvoiceDriveFile,
    App\Exceptions\Drive\FileUploadException as DriveException;

use Illuminate\Http\UploadedFile,
    Illuminate\Support\Facades\Storage;

//FIXME: Permissions
class CSJDriveService
{
    /**
     * @param string $name
     * @param string $parent
     * @throws DriveException If cannot connect or create files 
     * @return Google\Service\Drive\DriveFile
     */
    protected function createFolder($name, $parent)
    {
        try {
            $storage = Storage::disk('google');

            $fileMetadata = new \Google_Service_Drive_DriveFile([
                'name'     => $name,
                'parents'  => [$parent],
                'mimeType' => 'application/vnd.google-apps.folder',
            ]);

            $service = $storage->getAdapter()->getService();
            return $service->files->create($fileMetadata, [
                       'fields' => 'id, name, webViewLink'
                   ]);

        } catch (\Google\Exception $e) {
            foreach ($e->getErrors() as $err)
                throw new DriveException($err['message'], $name);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            throw new DriveException("Connection error", $name);
        } catch (\Exception $e) {
            throw new DriveException($e->getMessage(), $name);
        }
    }

    /**
     * @param UploadedFile|string
     * @param string $name
     * @param string $parent
     * @param string $local storage
     * @throws DriveException If cannot connect or create files 
     * @return Google\Service\Drive\DriveFile
     */
    protected function createFile($file, $name, $parent, $local = 'local')
    {
        try {
            $storage = Storage::disk('google');

            $fileMetadata = new \Google_Service_Drive_DriveFile([
                'name'     => $name,
                'parents'  => [$parent],
            ]);

            $service = $storage->getAdapter()->getService();

            if ($file instanceof UploadedFile) {
                $data = $file->get();
                $mimeType = $file->getClientMimeType();
            } 
            else {
                $data = Storage::disk($local)->get($file);
                $mimeType = Storage::disk($local)->mimeType($file);
            }

            if (!$data) {
                throw new \Exception("File '{$name}' is empty");
            }

            return $service->files->create($fileMetadata, [
                        'data'       => $data,
                        'mimeType'   => $mimeType,
                        'uploadType' => 'multipart',
                        'fields'     => 'id, webViewLink',
                   ]);

        } catch (\Google\Exception $e) {
            foreach ($e->getErrors() as $err)
                throw new DriveException($err['message'], $name);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            throw new DriveException("Connection error", $name);
        } catch (\Exception $e) {
            throw new DriveException($e->getMessage(), $name);
        }
    }

    /**
     * @param Account $account
     * @return Google\Service\Drive\DriveFile
     */
    protected function createEstimatesFolder(Account $account)
    {
        return $this->createFolder(
            "{$account->getSerial()} ({$account->getName()})", 
            env('GOOGLE_DRIVE_ESTIMATES_FOLDER_ID')
        );
    }

    /**
     * @param Account $account
     * @return Google\Service\Drive\DriveFile
     */
    protected function createInvoicesFolder(Account $account)
    {
        return $this->createFolder(
            "{$account->getSerial()} ({$account->getName()})", 
            env('GOOGLE_DRIVE_INVOICES_FOLDER_ID')
        );
    }

    /**
     * @param Account $account
     * @param string $type
     * @return Google\Service\Drive\DriveFile
     */
    public function createAccountFolder(Account $account, $type)
    {
        switch ($type) {
            case DriveFile::TYPE_ESTIMATE:
                return $this->createEstimatesFolder($account);
            case DriveFile::TYPE_INVOICE:
                return $this->createInvoicesFolder($account);
        }
    }

    /**
     * @param UploadedFile $file
     * @param Order $order
     * @param string $type
     * @return Google\Service\Drive\DriveFile
     */
    public function uploadFile(UploadedFile $file, Order $order, $type)
    {
        $account = $order->getAccount();
        $name    = $order->getDate()->format('Y');
        $files   = $account->getFiles($type);
        foreach ($files as $folder) {
            if ($folder->getName() === $name) {
                $child = $folder;
            }
        }

        if (!isset($child)) {
            $folder = $this->createFolder($name, $account->getFilesId($type));
            switch ($type) {
                case DriveFile::TYPE_ESTIMATE:
                    $child = new EstimateDriveFile;
                    break;
                case DriveFile::TYPE_INVOICE:
                    $child = new InvoiceDriveFile;
                    break;
                default:
                    throw new \Exception("Unknown {$type} type file");
            }
            $child->setName($folder->getName())
                  ->setFileId($folder->getId())
                  ->setFileUrl($folder->getWebViewLink());

            $account->addFile($child, $type);
        }

        return $this->createFile(
                   $file, 
                   "{$order->getSequence()}.pdf",
                   $child->getFileId(),
                );
    }

    /**
     * @param DriveDB $db
     * @return Google\Service\Drive\DriveFile
     */
    public function uploadBackup($db)
    {
        return $this->createFile(
            $db->getName(), 
            $db->getName(), 
            env("GOOGLE_DRIVE_BACKUPS_FOLDER_ID"),
            'backups');
    }


    //$children = $service->files->listFiles([
    //                  'q' => "'{$parent}' in parents", 
    //             ])->getFiles();
    //foreach ($children as $child) {
    //    if ($child->getName() == $order->getDate()->format('y')) {
    //        $folder = $child;
    //    }
    //}
}
