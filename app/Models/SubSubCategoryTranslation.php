<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubSubCategoryTranslation extends Model
{
    public function sub_sub_category()
    {
        return $this->belongsTo(SubSubCategory::class, 'sub_sub_category_id');
    }
}
