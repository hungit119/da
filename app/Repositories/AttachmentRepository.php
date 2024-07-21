<?php

namespace App\Repositories;

use App\Models\Attachment;

class AttachmentRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return Attachment::class;
    }
}
