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

namespace App\Http\Requests;

use App\Helpers\Bencode;
use App\Helpers\TorrentTools;
use App\Models\Category;
use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Closure;
use Exception;

class StoreTorrentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array<\Illuminate\Contracts\Validation\Rule|string>|string>
     */
    public function rules(Request $request): array
    {
        $category = Category::findOrFail($request->integer('category_id'));

        return [
            'torrent' => [
                'required',
                'file',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if ($value->getClientOriginalExtension() !== 'torrent') {
                        $fail('上传的种子文件扩展名不正确（您上传的是 "'.$value->getClientOriginalExtension().'")， 您是否上传了正确的文件？');
                    }

                    $decodedTorrent = TorrentTools::normalizeTorrent($value);

                    $v2 = Bencode::is_v2_or_hybrid($decodedTorrent);

                    if ($v2) {
                        $fail('不支持 BitTorrent v2 (BEP 52) ！');
                    }

                    try {
                        $meta = Bencode::get_meta($decodedTorrent);
                    } catch (Exception) {
                        $fail('你必须提供一个有效的种子文件！');
                    }

                    foreach (TorrentTools::getFilenameArray($decodedTorrent) as $name) {
                        if (!TorrentTools::isValidFilename($name)) {
                            $fail('种子名称无效');
                        }
                    }

                    $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->where('info_hash', '=', Bencode::get_infohash($decodedTorrent))->first();

                    if ($torrent !== null) {
                        match ($torrent->status) {
                            Torrent::PENDING   => $fail('当前已有相同的种子正在等待审核'),
                            Torrent::APPROVED  => $fail('当前种子已存在'),
                            Torrent::REJECTED  => $fail('有相同的种子正在拒绝列表中，请联系管理处理'),
                            Torrent::POSTPONED => $fail('有相同的种子正在延期列表中，请联系管理处理'),
                            default            => null,
                        };
                    }
                }
            ],
            'nfo' => [
                'nullable',
                'sometimes',
                'file',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if ($value->getClientOriginalExtension() !== 'nfo') {
                        $fail('上传的NFO文件扩展名不正确（您上传的是  "'.$value->getClientOriginalExtension().'")，您是否上传了正确的文件？');
                    }
                },
            ],
            'name' => [
                'required',
                'unique:torrents',
                'max:255',
                function ($attribute, $value, $fail) use ($category): void {
                    if ($category->movie_meta || $category->tv_meta) {
                        if (!preg_match('/[\p{Han}]/u', $value)) {
                            $fail('请在标题头部添加资源中文名，如果当前资源没有中文名，请您填写任意中文字符并在上传成功后编辑取消');
                        }
                    }
                },
            ],            'description' => [
                'required',
                'max:4294967296',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (!str_contains($value, '[spoiler=')) {
                        $fail('描述内容不符合要求，请参考其他已发布的种子并根据《发种规则》要求添加专用模板');
                    }
                },
            ],
            'mediainfo' => [
                'nullable',
                'sometimes',
                'max:4294967296',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (!str_contains($value, 'Format')) {
                        $fail('请提供完整版本的Mediainfo信息');
                    }
                },
            ],
            'bdinfo' => [
                'nullable',
                'sometimes',
                'max:4294967296',
            ],
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'type_id' => [
                'required',
                'exists:types,id',
            ],
            'resolution_id' => [
                Rule::when($category->movie_meta || $category->tv_meta, 'required'),
                Rule::when(!$category->movie_meta && !$category->tv_meta, 'nullable'),
                'exists:resolutions,id',
            ],
            'region_id' => [
                Rule::when($category->no_meta, 'required'),
                Rule::when(!$category->no_meta, 'nullable'),
                'exists:regions,id',
            ],
            'distributor_id' => [
                Rule::when($category->music_meta, 'required'),
                Rule::when(!$category->music_meta, 'nullable'),
                'exists:distributors,id',
            ],
            'imdb' => [
                Rule::when($category->movie_meta || $category->tv_meta, [
                    'required',
                    'numeric',
                ]),
                Rule::when(!($category->movie_meta || $category->tv_meta), [
                    Rule::in([0]),
                ]),
            ],
            'tvdb' => [
                Rule::when($category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::when(!$category->tv_meta, [
                    Rule::in([0]),
                ]),
            ],
            'tmdb' => [
                Rule::when($category->movie_meta || $category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::when(!($category->movie_meta || $category->tv_meta), [
                    Rule::in([0]),
                ]),
            ],
            'mal' => [
                Rule::when($category->movie_meta || $category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::when(!($category->movie_meta || $category->tv_meta), [
                    Rule::in([0]),
                ]),
            ],
            'igdb' => [
                Rule::when($category->game_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::when(!$category->game_meta, [
                    Rule::in([0]),
                ]),
            ],
            'season_number' => [
                Rule::when($category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::prohibitedIf(!$category->tv_meta),
            ],
            'episode_number' => [
                Rule::when($category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::prohibitedIf(!$category->tv_meta),
            ],
            'anon' => [
                'required',
                'boolean',
            ],
            'stream' => [
                'required',
                'boolean',
            ],
            'sd' => [
                'required',
                'boolean',
            ],
            'personal_release' => [
                'required',
                'boolean',
            ],
            'internal' => [
                'sometimes',
                'boolean',
                Rule::when(!$request->user()->group->is_modo && !$request->user()->group->is_internal, 'prohibited'),
            ],
            'free' => [
                'sometimes',
                'integer',
                'numeric',
                'between:0,100',
                Rule::when(!$request->user()->group->is_modo && !$request->user()->group->is_internal, 'prohibited'),
            ],
            'refundable' => [
                'sometimes',
                'boolean',
                Rule::when(!$request->user()->group->is_modo && !$request->user()->group->is_internal, 'prohibited'),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'igdb.in'                 => '如果媒体不存在于IGDB上或您未上传游戏，IGDB ID必须为0。',
            'tmdb.in'                 => '如果媒体不存在于TMDB上或您未上传电视节目或电影，TMDB ID必须为0。',
            'imdb.in'                 => '如果媒体不存在于IMDB上或您未上传电视节目或电影，IMDB ID必须为0。',
            'tvdb.in'                 => '如果媒体不存在于TVDB上或您未上传电视节目，TVDB ID必须为0。',
            'mal.in'                  => '如果媒体不存在于MAL上或您未上传电视或电影，MAL ID必须为0。',
            'region_id.required'      => '请选择小说分类',
            'distributor_id.required' => '请选择音乐风格',
        ];
    }
}
