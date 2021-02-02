<?php

namespace Featica\Http\Controllers;

use Featica\Featica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UpdateModelFeatureFlagsController
{
    /**
     * Get the model that have feature flags.
     *
     * @param Request $request
     * @param string $model
     *
     * @return \Illuminate\Http\Response|\Inertia\Response
     */
    public function __invoke(Request $request, string $model, $modelId)
    {
        $modelClass = Featica::resolveFeatureFlagOwningModel($model);
        abort_if(is_null($modelClass), 404);
        $featureOwningModel = $this->getModel($modelClass, $modelId);
        abort_if(is_null($featureOwningModel), 404);

        $featureFlags = $request->input('feature_flags');
        $featureFlags = collect($featureFlags)->only(array_keys(Featica::$features));
        $featureOwningModel->feature_flags = $featureFlags;
        $featureOwningModel->save();

        return Redirect::back()->with('success', 'Model feature flags updated.');
    }

    protected function getModel($modelClass, $modelId)
    {
        return $modelClass::where('id', $modelId)->first();
    }
}
