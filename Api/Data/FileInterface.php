<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xlab\DocumentLibrary\Api\Data;

interface FileInterface
{

    const FILE_NAME = 'File_name';
    const FILE_ID = 'file_id';

    /**
     * Get file_id
     * @return string|null
     */
    public function getFileId();

    /**
     * Set file_id
     * @param string $fileId
     * @return \Xlab\DocumentLibrary\File\Api\Data\FileInterface
     */
    public function setFileId($fileId);

    /**
     * Get File_name
     * @return string|null
     */
    public function getFileName();

    /**
     * Set File_name
     * @param string $fileName
     * @return \Xlab\DocumentLibrary\File\Api\Data\FileInterface
     */
    public function setFileName($fileName);
}

