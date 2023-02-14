<?php

namespace App\Exceptions\Drive;

class FileUploadException extends \Exception
{
    /**
     * @inheritDoc
     */
    public function __construct($e, $name, $parent = null, $code = 0, \Throwable $prev = null)
    {
        $msg = __("Cannot create :name folder in Drive: :message", [
            'name'      => $name,
            'parent'    => $parent,
            'message'   => $e
        ]);

        parent::__construct($msg, $code, $prev);
    }
}
