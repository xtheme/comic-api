<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class BuilderMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('whereLike', function ($columns, string $value) {
            $this->where(function (Builder $query) use ($columns, $value) {
                foreach (Arr::wrap($columns) as $column) {
                    $query->when(Str::contains($column, '.'),

                        // Relational searches
                        function (Builder $query) use ($column, $value) {
                            $parts = explode('.', $column);
                            $relationColumn = array_pop($parts);
                            $relationName = join('.', $parts);

                            return $query->orWhereHas($relationName, function (Builder $query) use ($relationColumn, $value) {
                                $query->where($relationColumn, 'LIKE', "%{$value}%");
                            });
                        },

                        // Default searches
                        function (Builder $query) use ($column, $value) {
                            return $query->orWhere($column, 'LIKE', "%{$value}%");
                        });
                }
            });

            return $this;
        });
    }
}
