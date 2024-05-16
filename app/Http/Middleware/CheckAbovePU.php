<?php

namespace App\Http\Middleware;

use App\Models\Group;
use Closure;
use Illuminate\Http\Request;

class CheckAbovePU
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // 获取所有不允许访问的组的 ID
        $restrictedGroupIds = Group::whereIn('slug', [
            'validating',
            'guest',
            'user',
            'banned',
            'leech',
            'disabled',
            'pruned'
        ])->pluck('id')->toArray();

        // 检查当前用户是否属于这些组
        if (in_array($request->user()->group_id, $restrictedGroupIds)) {
            abort(403, 'Access denied: You are in a restricted group.');
        }

        return $next($request);
    }
}
