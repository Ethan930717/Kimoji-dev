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

use App\Enums\UserGroups;

return [
    /*
    |--------------------------------------------------------------------------
    | User Pruning
    |--------------------------------------------------------------------------
    | Users Account Must Be At least x Days Old
    | Users Last Login At least x Days Ago
    | Soft Delete Disabled Users After x Days (Pruned Group)
    | Groups That Can Be Auto Disabled [DEFAULT] (User, PowerUser, SuperUser, Leech)
    */

    'user_pruning' => false,
    'account_age'  => 60,
    'last_login'   => 60,
    'soft_delete'  => 30,
    'group_ids'    => [UserGroups::USER->value, UserGroups::POWERUSER->value, UserGroups::SUPERUSER->value, UserGroups::LEECH->value],
];
