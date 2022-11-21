<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/***
 * Trait CategoryHierarchy
 * @package App\Traits
 * @property Collection<CategoryHierarchy> $tree
 * @property Collection<CategoryHierarchy> $hierarchy
 */
trait CategoryHierarchy
{
    /**
     * Returns the parent of this instance
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * Returns all children of this instance
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Returns all siblings of this instance
     * @return mixed
     */
    public function siblings()
    {
        return $this->parent
            ? $this->parent->children()->where('id', '!=', $this->id)
            : static::whereNull('parent_id')->where('id', '!=', $this->id);
    }

    /**
     * Returns the lowest level categories belonging to this instance
     */
    public function getTreeAttribute()
    {
        $result = collect($this->children);

        foreach ($this->children as $child) {
            $result = $result->concat($child->tree);
        }

        return $result;
    }

    /**
     * Returns a list of all parents related to this instance starting from highest to lowest
     * Including the current instance
     * @return Collection
     */
    public function getHierarchyAttribute()
    {
        $parents = collect([ $this ]);
        $parent = $this->parent;
        while ($parent) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents->reverse()->values();
    }

    public function calculateLevel()
    {
        return $this->hierarchy->count() - 1;
    }

    public function scopeLowest(Builder $query)
    {
        $query->doesntHave('children');
    }

    /**
     * Returns all highest level categories
     * @return mixed
     */
    public static function highest()
    {
        return static::whereNull('parent_id');
    }
}
