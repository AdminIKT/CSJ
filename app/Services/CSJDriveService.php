<?php

namespace App\Services;

use App\Entities\Order,
    App\Entities\Account,
    App\Entities\Account\DriveFile,
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
     * @param UploadedFile $file
     * @param string $name
     * @param string $parent
     * @throws DriveException If cannot connect or create files 
     * @return Google\Service\Drive\DriveFile
     */
    protected function createFile(UploadedFile $file, $name, $parent)
    {
        try {
            $storage = Storage::disk('google');

            $fileMetadata = new \Google_Service_Drive_DriveFile([
                'name'     => $name,
                'parents'  => [$parent],
            ]);

            $service = $storage->getAdapter()->getService();
            return $service->files->create($fileMetadata, [
                        'data'       => file_get_contents($file),
                        'mimeType'   => $file->getClientMimeType(),
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
    protected function getEstimatesFolder(Account $account)
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
    protected function getInvoicesFolder(Account $account)
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
    public function getFolder(Account $account, $type)
    {
        switch ($type) {
            case DriveFile::TYPE_ESTIMATE:
                return $this->getEstimatesFolder($account);
            case DriveFile::TYPE_INVOICE:
                return $this->getInvoicesFolder($account);
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
            $child  = new DriveFile;
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

    //$children = $service->files->listFiles([
    //                  'q' => "'{$parent}' in parents", 
    //             ])->getFiles();
    //foreach ($children as $child) {
    //    if ($child->getName() == $order->getDate()->format('y')) {
    //        $folder = $child;
    //    }
    //}
}
