<?php

namespace Featica\Http\Controllers;

use Featica\Featica;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ModelViewController
{
    /**
     * Get the model that have feature flags.
     *
     * @param Request $request
     * @param string $model
     *
     * @return \Illuminate\Http\Response|\Inertia\Response
     */
    public function __invoke(Request $request, string $model, $modelId): \Illuminate\Http\Response|\Inertia\Response
    {
        $modelClass = Featica::resolveFeatureFlagOwningModel($model);
        abort_if(is_null($modelClass), 404);
        $featureOwningModel = $this->getModel($modelClass, $modelId);
        abort_if(is_null($featureOwningModel), 404);

        return Inertia::render('Featica/ModelView', [
            'type' => $model,
            'modelClass' => $modelClass,
            'model' => $featureOwningModel
        ]);
    }

    protected function getModel($modelClass, $modelId)
    {
        return $modelClass::where('id', $modelId)->first();
    }
}
