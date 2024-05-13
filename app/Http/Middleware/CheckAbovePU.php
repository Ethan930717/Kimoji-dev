<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

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
        $specialGroupIds = Group::whereNotIn('slug', [
            'validating',
            'guest',
            'user',
            'banned',
            'leech',
            'disabled',
            'pruned'
        ])->pluck('id')->toArray();

        abort_unless(in_array($request->user()->group_id, $specialGroupIds), 403);

        return $next($request);
    }
}
