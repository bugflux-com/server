<?php


namespace App\Services\Models;


use App\Models\Mapping;
use App\Models\Project;
use App\Models\System;
use App\Providers\AppServiceProvider;
use Config;
use Illuminate\Http\Request;

class ProjectService
{
    public function create(Request $request) {

        while (!empty(Project::where('code', $code = Project::random_code())->first()));

        $request->merge(['code' => $code]);

        $project = Project::create($request->only('name', 'code'));
        $this->addDefaultMappings($project);

        return $project;
    }

    private function addDefaultMappings($project) {
        $default_data = [
            'mappable_type' => array_search(System::class, AppServiceProvider::$morphMap),
            'project_id' => $project->id,
            'is_default' => true,
        ];

        // Retrieve default system mappings
        $default_system_mappings = Config::get('mappings.systems');

        // Get distinct systems list to retrieve from DB
        $distinct_systems = array_unique(array_values($default_system_mappings));

        // Get from DB id with name
        $systems_with_ids = System::whereIn('name',$distinct_systems)->get(['id', 'name']);

        // Replace systems names with their id
        foreach ($systems_with_ids as $systems_with_id)
        {
            $default_system_mappings = array_replace($default_system_mappings,
                array_fill_keys(
                    array_keys($default_system_mappings, $systems_with_id->name), //value to be replaced
                    $systems_with_id->id // replacement
                )
            );
        }

        // Inserting mappings to DB
        foreach ($default_system_mappings as $value=>$mappable_id)
        {
            $default_data['mappable_id'] = $mappable_id;
            $default_data['value'] = $value;
            Mapping::create($default_data);
        }
    }
}