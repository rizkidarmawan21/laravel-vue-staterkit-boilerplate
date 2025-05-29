<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Access\Authorizable;

class MenuPermission
{
    public static function filterMenuByPermission(array $menu): array
    {
        // Filter menu items
        $filteredMenu = array_map(function ($group) {
            if (isset($group['items'])) {
                $group['items'] = array_filter($group['items'], function ($item) {
                    if (empty($item['can'])) {
                        return true;
                    }

                    /** @var Authorizable|null $user */
                    $user = Auth::user();
                    if (!$user) {
                        return false;
                    }

                    $permissions = explode(',', $item['can']);
                    return collect($permissions)->some(
                        fn($permission) => $user->can($permission)
                    );
                });

                // Convert array_filter result back to array with reset keys
                $group['items'] = array_values($group['items']);
            }
            return $group;
        }, $menu);

        // Remove empty groups
        $filteredMenu = array_values(array_filter($filteredMenu, function ($group) {
            return !empty($group['items']);
        }));

        // Remove 'can' field from final response
        return self::removeCanField($filteredMenu);
    }

    private static function removeCanField(array $menu): array
    {
        return array_map(function ($group) {
            if (isset($group['items'])) {
                $group['items'] = array_map(function ($item) {
                    unset($item['can']);
                    return $item;
                }, $group['items']);
            }
            return $group;
        }, $menu);
    }
}
