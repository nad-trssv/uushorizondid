<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'description',
        'is_deleted',
        'parent_id',
        'order',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'is_deleted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function translation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
    }

    public function getNameAttribute()
    {
        return $this->translation()?->name;
    }

    public function getDescriptionAttribute()
    {
        return $this->translation()?->description;
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->with(['childrenRecursive', 'translations']);
    }
 
    /**
     * Move category to the end of root categories
     */
    public function moveToEndRoot($explicitOrder = null)
    {
        $this->parent_id = null;
        $this->order = $explicitOrder ?? (self::whereNull('parent_id')->max('order') + 1);
        $this->save();
    }
    
    /**
     * Move category to the end of parent's children
     */
    public function moveToEnd(?Category $target)
    {
        if ($target) {
            $this->parent_id = $target->id; // Явно устанавливаем parent_id
            $maxOrder = self::where('parent_id', $target->id)->max('order') ?? 0;
            $this->order = $maxOrder + 1;
        } else {
            $this->parent_id = null;
            $maxOrder = self::whereNull('parent_id')->max('order') ?? 0;
            $this->order = $maxOrder + 1;
        }
        $this->save();
    }
    
    /**
     * Move category before target
     */
    public function moveBefore($target)
    {
        // Для корневых категорий
        if (is_null($target->parent_id)) {
            $query = self::whereNull('parent_id')
                ->where('order', '>=', $target->order);
        } else {
            $query = self::where('parent_id', $target->parent_id)
                ->where('order', '>=', $target->order);
        }
        
        if ($this->id) {
            $query->where('id', '!=', $this->id);
        }
            
        $query->increment('order');
        
        $this->order = $target->order;
        $this->save();
    }
    
    /**
     * Move category after target
     */
    public function moveAfter($target)
    {
        // Увеличиваем порядок всех категорий после целевой
        $query = self::where('parent_id', $target->parent_id)
            ->where('order', '>', $target->order);
            
        if ($this->id) {
            $query->where('id', '!=', $this->id);
        }
            
        $query->increment('order');
        
        // Устанавливаем порядок текущей категории
        $this->order = $target->order + 1;
        $this->save();
    }

    public function gallery()
    {
        return $this->morphMany(Gallery::class, 'galleryable');
    }


}
