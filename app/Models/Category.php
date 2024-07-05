<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'parent_id', 'active'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function deactivate()
    {
        $this->update(['active' => false]);
        foreach ($this->children as $child) {
            $child->deactivate();
        }
    }

    public static function getNestedCategories($parentId)
    {
        return self::with('children')->where('parent_id', $parentId)->get();
    }

}
