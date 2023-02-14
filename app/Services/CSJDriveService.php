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
    public function getEstimatesFolder(Account $account)
    {
        return $this->createFolder(
            "{$account->getSerial()} ({$account->getName()})", 
            env('GOOGLE_DRIVE_ESTIMATEDS_FOLDER_ID')
        );
    }

    /**
     * @param UploadedFile $file
     * @param Order $order
     * @return Google\Service\Drive\DriveFile
     */
    public function uploadEstimateFile(UploadedFile $file, Order $order)
    {
        $account = $order->getAccount();
        $name    = $order->getDate()->format('Y');
        foreach ($account->getEstimateFiles() as $folder) {
            if ($folder->getName() === $name) {
                $child = $folder;
            }
        }

        if (!isset($child)) {
            $folder = $this->createFolder($name, $account->getEstimatesFileId());
            $child  = new DriveFile;
            $child->setName($folder->getName())
                  ->setFileId($folder->getId())
                  ->setFileUrl($folder->getWebViewLink());

            $account->addEstimateFile($child);
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
