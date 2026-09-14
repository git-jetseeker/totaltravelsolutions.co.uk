<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmContent extends Model
{
    protected $table = 'crm_contents';

    protected $fillable = [
        'page_id', 'group_name', 'field_key', 'label', 'type', 'value', 'help_text', 'sort_order',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CrmPage::class, 'page_id');
    }
}
