<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmPage extends Model
{
    protected $table = 'crm_pages';

    protected $fillable = [
        'agent_id', 'slug', 'title', 'path', 'meta_title', 'meta_keyword', 'meta_description', 'status',
    ];

    public function contents(): HasMany
    {
        return $this->hasMany(CrmContent::class, 'page_id')->orderBy('sort_order')->orderBy('id');
    }
}
