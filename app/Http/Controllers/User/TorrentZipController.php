<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\User;

use App\Helpers\Bencode;
use App\Http\Controllers\Controller;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use ZipArchive;
use Illuminate\Support\Facades\Log;

class TorrentZipController extends Controller
{
    /**
     * Show zip file containing all torrents user has history of.
     */
    public function show(Request $request, User $user): \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        //  Extend The Maximum Execution Time
        set_time_limit(1200);

        // Authorized User
        abort_unless($request->user()->is($user), 403);

        // Define Dir For Zip
        $zipPath = getcwd().'/files/tmp_zip/';

        // Check Directory exists
        if (!File::isDirectory($zipPath)) {
            File::makeDirectory($zipPath, 0755, true, true);
        }

        // Zip File Name
        $zipFileName = $user->username.'.zip';

        // Create ZipArchive Obj
        $zipArchive = new ZipArchive();

        // Get Users History
        $historyTorrents = Torrent::whereRelation('history', 'user_id', '=', $user->id)->get();

        if ($zipArchive->open($zipPath.$zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $announceUrl = route('announce', ['passkey' => $user->passkey]);

            foreach ($historyTorrents as $torrent) {
                if (file_exists(getcwd().'/files/torrents/'.$torrent->file_name)) {
                    $dict = Bencode::bdecode(file_get_contents(getcwd().'/files/torrents/'.$torrent->file_name));

                    // Set the announce key and add the user passkey
                    $dict['announce'] = $announceUrl;

                    // Set link to torrent as the comment
                    if (config('torrent.comment')) {
                        $dict['comment'] = config('torrent.comment').'. '.route('torrents.show', ['id' => $torrent->id]);
                    } else {
                        $dict['comment'] = route('torrents.show', ['id' => $torrent->id]);
                    }

                    $fileToDownload = Bencode::bencode($dict);

                    $filename = str_replace(
                        [' ', '/', '\\'],
                        ['.', '-', '-'],
                        '['.config('torrent.source').']'.$torrent->name.'.torrent'
                    );

                    $zipArchive->addFromString($filename, $fileToDownload);
                }
            }

            $zipArchive->close();
        }

        if (file_exists($zipPath.$zipFileName)) {
            return response()->download($zipPath.$zipFileName)->deleteFileAfterSend(true);
        }

        return redirect()->back()->withErrors(trans('common.something-went-wrong'));
    }

    public function downloadUrgentSeedersZip(Request $request, User $user)
    {
        set_time_limit(1200); // Extend execution time

        abort_unless($request->user()->is($user), 403); // Authorized user only

        $zipPath = getcwd().'/files/tmp_zip/';

        if (!File::isDirectory($zipPath)) {
            File::makeDirectory($zipPath, 0755, true, true);
        } else {
            Log::info('Directory already exists', ['path' => $zipPath]);
        }

        $zipFileName = $user->username.'_urgent_seeders.zip';
        $zipArchive = new ZipArchive();
        $selectedVolumeBytes = (int) $request->input('volume', 0);

        $historyTorrentIds = $user->history()->pluck('torrent_id')->toArray();

        $urgentTorrents = Torrent::whereNotIn('id', $historyTorrentIds)
            ->where('internal', 1) // 只选取 internal 字段为 1 的种子
            ->where('category_id', 3) // 只选取 category_id 字段为 3 的种子
            ->where('seeders', '>', 0) // 排除 seeders 字段为 0 的种子
            ->orderBy('seeders', 'asc')
            ->get();

        $totalSize = 0;

        if ($zipArchive->open($zipPath.$zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $announceUrl = route('announce', ['passkey' => $user->passkey]);

            foreach ($urgentTorrents as $torrent) {
                if ($totalSize + $torrent->size <= $selectedVolumeBytes && !\in_array($torrent->id, $historyTorrentIds)) {
                    $totalSize += $torrent->size;
                    $filePath = getcwd().'/files/torrents/'.$torrent->file_name;

                    if (file_exists($filePath)) {
                        // Log details about the torrent being processed

                        $dict = Bencode::bdecode(file_get_contents($filePath));
                        $dict['announce'] = $announceUrl;

                        if (config('torrent.comment')) {
                            $dict['comment'] = config('torrent.comment').'. '.route('torrents.show', ['id' => $torrent->id]);
                        } else {
                            $dict['comment'] = route('torrents.show', ['id' => $torrent->id]);
                        }

                        $fileToDownload = Bencode::bencode($dict);
                        $filename = '['.config('torrent.source').']'.$torrent->name.'.torrent';
                        $filename = str_replace([' ', '/', '\\'], ['.', '-', '-'], $filename);

                        $zipArchive->addFromString($filename, $fileToDownload);
                    } else {
                        Log::warning('Torrent file does not exist', ['file_path' => $filePath]);
                    }
                }
            }

            $zipArchive->close();
        } else {
            Log::error('Failed to open ZIP archive for writing', ['path' => $zipPath.$zipFileName]);
        }

        if (file_exists($zipPath.$zipFileName)) {
            return response()->download($zipPath.$zipFileName)->deleteFileAfterSend(true);
        }

        return redirect()->back()->withErrors(trans('common.something-went-wrong'));
    }

    use Carbon\Carbon;

    public function downloadDeadSeedersZip(Request $request)
    {
        set_time_limit(1200); // Extend execution time

        $zipPath = getcwd().'/files/tmp_zip/';

        if (!File::isDirectory($zipPath)) {
            File::makeDirectory($zipPath, 0755, true, true);
        } else {
            Log::info('Directory already exists', ['path' => $zipPath]);
        }

        $zipFileName = 'dead_seeders.zip';
        $zipArchive = new ZipArchive();

        // 获取当前时间10分钟前的时间点
        $MinutesAgo = Carbon::now()->subMinutes(10);

        $urgentTorrents = Torrent::where('internal', 1) // 只选取 internal 字段为 1 的种子
        ->where('category_id', 3) // 只选取 category_id 字段为 3 的种子
        ->where('seeders', '=', 0) // 排除 seeders 字段为 0 的种子
        ->where('created_at', '<', $MinutesAgo) // 筛选发布时间超过10分钟的种子
        ->orderBy('seeders', 'asc')
            ->get();

        if ($zipArchive->open($zipPath.$zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $announceUrl = route('announce', ['passkey' => '36c14cf43e8377acd195719cc2f387c2']);

            foreach ($urgentTorrents as $torrent) {
                $filePath = getcwd().'/files/torrents/'.$torrent->file_name;

                if (file_exists($filePath)) {
                    $dict = Bencode::bdecode(file_get_contents($filePath));
                    $dict['announce'] = $announceUrl;

                    if (config('torrent.comment')) {
                        $dict['comment'] = config('torrent.comment').'. '.route('torrents.show', ['id' => $torrent->id]);
                    } else {
                        $dict['comment'] = route('torrents.show', ['id' => $torrent->id]);
                    }

                    $fileToDownload = Bencode::bencode($dict);
                    $filename = '['.config('torrent.source').']'.$torrent->name.'.torrent';
                    $filename = str_replace([' ', '/', '\\'], ['.', '-', '-'], $filename);

                    $zipArchive->addFromString($filename, $fileToDownload);
                } else {
                    Log::warning('Torrent file does not exist', ['file_path' => $filePath]);
                }
            }

            $zipArchive->close();
        } else {
            Log::error('Failed to open ZIP archive for writing', ['path' => $zipPath.$zipFileName]);
        }

        if (file_exists($zipPath.$zipFileName)) {
            return response()->download($zipPath.$zipFileName)->deleteFileAfterSend(true);
        }

        return redirect()->back()->withErrors(trans('common.something-went-wrong'));
    }

}
