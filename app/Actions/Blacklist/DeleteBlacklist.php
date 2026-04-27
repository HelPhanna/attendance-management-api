<?php

namespace App\Actions\Blacklist;

use App\Models\Blacklist;

class DeleteBlacklist
{
    public function delete(Blacklist $blacklist): void
    {
        $blacklist->delete();
    }
}
