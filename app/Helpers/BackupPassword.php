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

namespace App\Helpers;

use Illuminate\Support\Collection;
use ZipArchive;

class BackupPassword
{
    /**
     * Path to .zip-file.
     */
    public ?string $path;

    /**
     * The chosen password.
     */
    protected string $password;

    /**
     * Read the .zip, apply password and encryption, then rewrite the file.
     */
    public function __construct(string $path)
    {
        $this->password = config('backup.security.password');

        // If no password is set, just return the backup-path
        if (! $this->password) {
            return;
        }

        consoleOutput()->info('正在使用 ZipArchive 加密 zip 文件');

        $this->makeZip($path);

        consoleOutput()->info('已成功加密 zip 文件');
    }

    /**
     * Use native PHP ZipArchive.
     */
    protected function makeZip(string $path): void
    {
        $encryption = config('backup.security.encryption');

        $zipArchive = new ZipArchive();

        $zipArchive->open($path, ZipArchive::OVERWRITE);
        $zipArchive->addFile($path, 'backup.zip');
        $zipArchive->setPassword($this->password);
        Collection::times($zipArchive->numFiles, fn ($i) => $zipArchive->setEncryptionIndex($i - 1, $encryption));
        $zipArchive->close();

        $this->path = $path;
    }
}
