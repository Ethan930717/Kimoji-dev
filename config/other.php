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

return [
    /*
    |--------------------------------------------------------------------------
    | Site title
    |--------------------------------------------------------------------------
    |
    | Title of Site
    |
    */

    'title' => 'KIMOJI',

    /*
    |--------------------------------------------------------------------------
    | Site SubTitle
    |--------------------------------------------------------------------------
    |
    | SubTitle
    |
    */

    'subTitle' => 'KIMOJI乐园',

    /*
    |--------------------------------------------------------------------------
    | Site email
    |--------------------------------------------------------------------------
    |
    | Email address to send emails
    |
    */

    'email' => env('DEFAULT_OWNER_EMAIL', 'unit3d@none.com'),

    /*
    |--------------------------------------------------------------------------
    | Meta description
    |--------------------------------------------------------------------------
    |
    | Default meta description content
    |
    */

    'meta_description' => 'KIMOJI乐园',

    /*
    |--------------------------------------------------------------------------
    | Site Birthdate
    |--------------------------------------------------------------------------
    |
    | Date Site Was Born
    |
    */
    'birthdate' => 'October 1th 2023',

    /*
    |--------------------------------------------------------------------------
    | Freelech State
    |--------------------------------------------------------------------------
    |
    | Global Freeleech
    |
    */
    'freeleech' => false,

    'freeleech_until' => '12/23/2023 3:00 PM EST',

    /*
    |--------------------------------------------------------------------------
    | Double Upload State
    |--------------------------------------------------------------------------
    |
    | Global Double Upload
    |
    */
    'doubleup' => false,

    /*
    |--------------------------------------------------------------------------
    | Refund Torrent Download
    |--------------------------------------------------------------------------
    |
    | Global Refund Download
    |
    */
    'refundable' => false,

    /*
    |--------------------------------------------------------------------------
    | Min Ratio
    |--------------------------------------------------------------------------
    |
    | Minimum Ratio To Download
    |
    */

    'ratio' => 1.0,

    /*
    |--------------------------------------------------------------------------
    | Invite only
    |--------------------------------------------------------------------------
    |
    | Invite System On/Off (Open Reg / Closed)
    | Expire time in days
    |
    | Restricted mode for invites. If set to true, invites will be restricted
    | Exempt these groups from the invite restrictions
    */
    'invite-only'   => true,
    'invite_expire' => '14',

    'invites_restriced' => true,
    'invite_groups'     => [
        '统筹',
        '主宰',
        '贵人',
        '监护',
        '园丁',
        '耕夫',
        '守卫',
        '掌固',
        '颐养',
    ],
    'max_unused_user_invites' => 99,

    /*
    |--------------------------------------------------------------------------
    | Default Users Stats
    |--------------------------------------------------------------------------
    |
    | This will be the upload and download given to new members. (In Bytes!)
    | Default: 100GiB Upload and 1GiB Download
    */
    'default_upload'   => '107374182400',
    'default_download' => '0',

    /*
    |--------------------------------------------------------------------------
    | Default Site Style
    |--------------------------------------------------------------------------
    | 0 = 旷野
    | 1 = Galactic Theme
    | 2 = Dark Blue Theme
    | 3 = Dark Green Theme
    | 4 = Dark Pink Theme
    | 5 = Dark Purple Theme
    | 6 = Dark Red Theme
    | 7 = Dark Teal Theme
    | 8 = Dark Yellow Theme
    */
    'default_style' => 0,

    /*
    |--------------------------------------------------------------------------
    | Default Font Awesome Style
    |--------------------------------------------------------------------------
    | fas = Solid
    | far = Regular
    | fal = Light
    */
    'font-awesome' => 'fas',

    /*
    |--------------------------------------------------------------------------
    | Application Signups
    |--------------------------------------------------------------------------
    | True/1 = Enabled
    | False/0 = Disabled
    */
    'application_signups' => false,

    /*
    |--------------------------------------------------------------------------
    | Rules Page URL
    |--------------------------------------------------------------------------
    | Example: 1
    */
    'rules_url' => env('APP_URL').'/pages/1',

    /*
    |--------------------------------------------------------------------------
    | FAQ Page URL
    |--------------------------------------------------------------------------
    | Example: 2
    */
    'faq_url' => env('APP_URL').'/pages/2',

    /*
    |--------------------------------------------------------------------------
    | Upload Guide Page URL For Upload Page
    |--------------------------------------------------------------------------
    | Example: 4
    */
    'upload-guide_url' => env('APP_URL').'/pages/4',

    /*
    |--------------------------------------------------------------------------
    | Hide Staff Area Forum Posts From Chat
    |--------------------------------------------------------------------------
    | 1 = Enabled
    | 0 = Disabled
    | If enabled, Staff members get notifications instead of posting being announced in chat.
    */
    'staff-forum-notify' => '0',

    /*
    |--------------------------------------------------------------------------
    | Staff Forum Id
    |--------------------------------------------------------------------------
    | Example: 2
    | The ID value of staff forum area. Should be the main / parent ID.
    */
    'staff-forum-id' => '',
];
