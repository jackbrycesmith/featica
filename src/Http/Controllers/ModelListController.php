<?php

namespace Featica\Http\Controllers;

use Featica\Featica;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ModelListController
{
    /**
     * Get the list of models that have feature flags.
     *
     * @param Request $request
     * @param string $model
     *
     * @return \Illuminate\Http\Response|\Inertia\Response
     */
    public function __invoke(Request $request, string $model)
    {
        $modelClass = Featica::resolveFeatureFlagOwningModel($model);
        abort_if(is_null($modelClass), 404);

        return Inertia::render('Featica/ModelList', [
            'type' => $model,
            'modelClass' => $modelClass,
            'filters' => fn () => $request->all('search'),
            'models' => fn () => $this->getModels($modelClass, $request->input('search'))
        ]);
    }

    protected function getModels($modelClass, ?string $searchTerms = null)
    {
        return $modelClass::query()
            ->featicaDashboardSearch($searchTerms)
            ->paginate(7);
    }
}
