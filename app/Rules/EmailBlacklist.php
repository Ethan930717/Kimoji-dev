<?php

namespace App\Rules;

use App\Helpers\EmailBlacklistUpdater;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
use Psr\SimpleCache\InvalidArgumentException;

class EmailBlacklist implements Rule
{
    /**
     * Array of blacklisted domains.
     */
    private array $domains = [];

    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        // Load blacklisted domains
        $this->refresh();

        // Extract domain from supplied email address
        $domain = Str::after(strtolower((string) $value), '@');

        // Run validation check
        return !\in_array($domain, $this->domains, true);
    }

    /**
     * Retrive the latest selection of blacklisted domains and cache them.
     */
    public function refresh(): void
    {
        $this->shouldUpdate();
        $this->domains = cache()->get(config('email-blacklist.cache-key'));
        $this->appendCustomDomains();
    }

    /**
     * Should update blacklist?.
     */
    protected function shouldUpdate(): void
    {
        $autoupdate = config('email-blacklist.auto-update');

        try {
            if ($autoupdate && !cache()->has(config('email-blacklist.cache-key'))) {
                EmailBlacklistUpdater::update();
            }
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Append custom defined blacklisted domains.
     */
    protected function appendCustomDomains(): void
    {
        $appendList = config('email-blacklist.append');

        if ($appendList === null) {
            return;
        }

        $appendDomains = explode('|', strtolower((string) $appendList));
        $this->domains = array_merge($this->domains, $appendDomains);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return '邮箱在黑名单列表中！';
    }
}
