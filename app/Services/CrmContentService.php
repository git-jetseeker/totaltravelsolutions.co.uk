<?php

namespace App\Services;

use App\Models\CrmContent;
use App\Models\CrmPage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class CrmContentService
{
    public function get(string $key, $default = '', ?int $agentId = null)
    {
        [$slug, $fieldKey] = $this->splitKey($key);
        if (!$slug || !$fieldKey) {
            return $default;
        }

        $page = $this->pageMap($slug, $agentId);

        return $page[$fieldKey] ?? $default;
    }

    public function pageMeta(string $slug, ?int $agentId = null): object
    {
        $slug = trim($slug);
        $agentId = $agentId ?? $this->currentAgentId();
        $empty = (object) [
            'title' => '',
            'meta_title' => '',
            'meta_keyword' => '',
            'meta_description' => '',
            'path' => '',
        ];

        try {
            if (!Schema::hasTable('crm_pages')) {
                return $empty;
            }
        } catch (\Throwable $e) {
            return $empty;
        }

        return Cache::remember($this->metaCacheKey($slug, $agentId), 3600, function () use ($slug, $agentId, $empty) {
            $hasAgent = Schema::hasColumn('crm_pages', 'agent_id');

            $query = CrmPage::where('slug', $slug)->where('status', 'Yes');
            if ($hasAgent) {
                $query->where('agent_id', $agentId);
            }

            $page = $query->first();

            if (!$page && $hasAgent && $agentId !== 0) {
                $page = CrmPage::where('slug', $slug)
                    ->where('agent_id', 0)
                    ->where('status', 'Yes')
                    ->first();
            }

            if (!$page) {
                return $empty;
            }

            return (object) [
                'title' => (string) ($page->title ?? ''),
                'meta_title' => (string) ($page->meta_title ?? ''),
                'meta_keyword' => (string) ($page->meta_keyword ?? ''),
                'meta_description' => (string) ($page->meta_description ?? ''),
                'path' => (string) ($page->path ?? ''),
            ];
        });
    }

    public function pageMap(string $slug, ?int $agentId = null): array
    {
        $slug = trim($slug);
        $agentId = $agentId ?? $this->currentAgentId();

        try {
            if (!Schema::hasTable('crm_contents') || !Schema::hasTable('crm_pages')) {
                return [];
            }
        } catch (\Throwable $e) {
            return [];
        }

        return Cache::remember($this->cacheKey($slug, $agentId), 3600, function () use ($slug, $agentId) {
            $hasAgent = Schema::hasColumn('crm_pages', 'agent_id');

            $query = CrmPage::where('slug', $slug)->where('status', 'Yes');
            if ($hasAgent) {
                $query->where('agent_id', $agentId);
            }

            $page = $query->first();

            if (!$page && $hasAgent && $agentId !== 0) {
                $page = CrmPage::where('slug', $slug)
                    ->where('agent_id', 0)
                    ->where('status', 'Yes')
                    ->first();
            }

            if (!$page) {
                return [];
            }

            return CrmContent::where('page_id', $page->id)
                ->pluck('value', 'field_key')
                ->toArray();
        });
    }

    public function forgetPage(string $slug, ?int $agentId = null): void
    {
        $agentId = $agentId ?? $this->currentAgentId();
        Cache::forget($this->cacheKey($slug, $agentId));
        Cache::forget($this->metaCacheKey($slug, $agentId));
        Cache::forget('crm.page.' . $slug);
        if ($agentId !== 0) {
            Cache::forget($this->cacheKey($slug, 0));
            Cache::forget($this->metaCacheKey($slug, 0));
        }
    }

    public function currentAgentId(): int
    {
        if (app()->bound('crm.agent_id')) {
            return (int) app('crm.agent_id');
        }

        if (function_exists('current_agent_id')) {
            try {
                return (int) current_agent_id();
            } catch (\Throwable $e) {
                // fall through
            }
        }

        return (int) config('crm.default_agent_id', 1);
    }

    private function cacheKey(string $slug, int $agentId): string
    {
        return 'crm.page.' . $agentId . '.' . $slug;
    }

    private function metaCacheKey(string $slug, int $agentId): string
    {
        return 'crm.meta.' . $agentId . '.' . $slug;
    }

    private function splitKey(string $key): array
    {
        $key = trim($key);
        if ($key === '' || !str_contains($key, '.')) {
            return [null, null];
        }

        $parts = explode('.', $key, 2);

        return [$parts[0], $parts[1]];
    }
}
