<?php

namespace Featica;

use Featica\Featica;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

trait HasFeatureFlags
{
    public function initializeHasFeatureFlags()
    {
        $this->casts['feature_flags'] = 'json';
    }

    /**
     * Determines if specified feature is enabled.
     *
     * @param string $featureKey The feature key
     *
     * @return boolean True if enabled, False otherwise.
     */
    public function hasFeature(string $featureKey): bool
    {
        return Featica::modelHasFeature($this, $featureKey);
    }

    /**
     * Search query when used in featica dashboard.
     *
     * @param $query The query
     * @param null|string $searchTerms The search terms
     */
    public function scopeFeaticaDashboardSearch($query, ?string $searchTerms = null)
    {
        $columnsToSearch = $this->searchableColumnsForFeaticaDashboard();
        if (count($columnsToSearch) === 0) return;

        if (in_array(config('database.default'), ['mysql', 'sqlite'])) {
            collect(str_getcsv($searchTerms, ' ', '"'))->filter()->each(function ($term) use ($query, $columnsToSearch) {
                $term = '%'.$term.'%';
                $query->where(function ($query) use ($term, $columnsToSearch) {
                    foreach ($columnsToSearch as $index => $column) {
                        if ($index === 0) {
                            $query->where($column, 'like', $term);
                        } else {
                            $query->orWhere($column, 'like', $term);
                        }
                    }

                    // TODO realationship search e.g. find team via owner/team member
                });
            });
        }

        if (config('database.default') === 'pgsql') {
            collect(str_getcsv($searchTerms, ' ', '"'))->filter()->each(function ($term) use ($query, $columnsToSearch) {
                $term = '%'.$term.'%';
                $query->where(function ($query) use ($term, $columnsToSearch) {
                    foreach ($columnsToSearch as $index => $column) {
                        if ($index === 0) {
                            $query->where($column, 'ilike', $term);
                        } else {
                            $query->orWhere($column, 'ilike', $term);
                        }
                    }

                    // TODO realationship search e.g. find team via owner/team member
                });
            });
        }
    }

    /**
     * Get the columns that can be searched in the featica dashboard.
     *
     * @return array
     */
    public function searchableColumnsForFeaticaDashboard(): array
    {
        $item = Arr::get(Featica::$owningModelDashboardSearch, $this::class);

        $modelColumns = Schema::getColumnListing($this->getTable());
        $columnsToSearch = array_merge(
            Featica::$dashboardModelsDefaultSearchColumns,
            Arr::wrap(Arr::get($item, 'columns'))
        );

        // Filter out non-existant columns to avoid errors
        $columnsToSearch = collect($columnsToSearch)
            ->unique()
            ->filter(fn ($column) => in_array($column, $modelColumns))
            ->values()
            ->toArray();

        return $columnsToSearch;
    }
}
