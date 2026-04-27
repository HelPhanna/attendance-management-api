<?php

namespace App\Actions\Classes;

use App\Models\Classes;

class DeleteClass
{
    public function delete(Classes $class): void
    {
        $class->delete();
    }
}
