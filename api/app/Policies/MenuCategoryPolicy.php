<?php

namespace App\Policies;

use App\Models\MenuCategory;
use App\Models\User;

class MenuCategoryPolicy
{
    /**
     * Determine if the user can view any menu categories.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('menus.view');
    }

    /**
     * Determine if the user can view the menu category.
     */
    public function view(User $user, MenuCategory $menuCategory): bool
    {
        return $user->can('menus.view');
    }

    /**
     * Determine if the user can create menu categories.
     */
    public function create(User $user): bool
    {
        return $user->can('menus.create');
    }

    /**
     * Determine if the user can update the menu category.
     */
    public function update(User $user, MenuCategory $menuCategory): bool
    {
        return $user->can('menus.update');
    }

    /**
     * Determine if the user can delete the menu category.
     */
    public function delete(User $user, MenuCategory $menuCategory): bool
    {
        return $user->can('menus.delete');
    }
}

