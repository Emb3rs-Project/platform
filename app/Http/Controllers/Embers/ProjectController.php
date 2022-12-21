<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Projects\CreatesProjects;
use App\Contracts\Embers\Projects\DestroysProjects;
use App\Contracts\Embers\Projects\EditsProjects;
use App\Contracts\Embers\Projects\IndexesProjects;
use App\Contracts\Embers\Projects\ShowsProjects;
use App\Contracts\Embers\Projects\StoresProjects;
use App\Contracts\Embers\Projects\UpdatesProjects;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Link;
use App\Models\Template;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $projects = app(IndexesProjects::class)->index($request->user());

        return Inertia::render('Projects/ProjectIndex', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        app(CreatesProjects::class)->create($request->user());

        return Inertia::render('Projects/ProjectCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        app(StoresProjects::class)->store($request->user(), $request->all());

        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array<string, mixed>
     */
    public function show(Request $request, $id)
    {
        $instances_id = Auth::user()->currentTeam->instances->pluck("id");
        $instances = Instance::with('location', 'template', 'template.category')->whereIn('id', $instances_id)->get();

        $teamLinks = $request->user()->currentTeam->links->pluck('id');

        $links = Link::with([
            'geoSegments'
        ])->whereIn('id', $teamLinks)->get();

        $project = app(ShowsProjects::class)->show($request->user(), $id);

        return Inertia::render('Projects/ProjectDetails', [
            'instances' => $instances,
            'links' => $links,
            'project' => $project,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array<string, mixed>
     */
    public function edit(Request $request, $id)
    {
        [
            $project,
            $locations,
        ] = app(EditsProjects::class)->edit($request->user(), $id);

        return Inertia::render('Projects/ProjectEdit', [
            'project' => $project,
            'locations' => $locations
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $updatedProject = app(UpdatesProjects::class)->update($request->user(), $id, $request->all());

        return redirect()->route('projects.show', $updatedProject->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        app(DestroysProjects::class)->destroy($request->user(), $id);

        return redirect()->route('projects.index');
    }


    public function importFile(Request $request)
    {
        $file = $request->file('file');

        $filePath = $file->store('imports');
        list($path, $filename) = explode('/', $filePath);
        $import = "\App\Jobs\Import{$request->input('action')}::dispatch";

        call_user_func($import, $filename, $request->user());

        return redirect()->back()->with('flash', ['success' => 'The file is being processed in the background. You will receive a notification when finished.']);
        //$import::dispatch($filename, request()->user());
    }

    public function downloadImportFile(Request $request)
    {

        $profile_sample = $this->getProfileSamples($request->query('type'));
        if($profile_sample) {
            return response()->download(public_path('samples/'.$profile_sample));
        }


        $templates = Template::with('templateProperties', 'templateProperties.property')->get();
        $allProps = [];
        if ($request->query('type') === 'Source') {
            $optionsTemplate = Template::where('category_id', Category::SOURCE)->get();
            $allProps['sourceID'] = '';
        } else {
            $optionsTemplate = Template::where('category_id', Category::SINK)->get();
        }
        $helpers = [
            [
                'field' => 'template',
                'options' => $optionsTemplate->pluck('name')->join(' or '),
                'info' => ''
            ]
        ];
        $allProps['template'] = '';
        $allProps['latitude'] = '';
        $allProps['longitude'] = '';
        $templates->each(function ($item) use (&$helpers, &$allProps) {
            $item->templateProperties->each(function ($tempProp) use (&$allProps, &$helpers, $item) {
                // dump($tempProp->property->toArray());
                $field = [
                    'field' => $tempProp->property['name'],
                    'options' => '',
                    'info' => $tempProp->property['description']
                ];
                if ($tempProp->property['inputType'] === 'select' && $tempProp->property['data']) {
                    $field['options'] = collect($tempProp->property['data']['options'])->pluck('key')->join(' or ');
                }
                $helpers[] = $field;
                $allProps[$tempProp->property['name']] = '';
            });
        });
        $sheetName = [
            'import' => collect([$allProps]),
            'helper' => $helpers
        ];
        if ($request->query('type') === 'Source') {
            $sheetName['AdditionalStreams'] = collect([$allProps]);
        }

        $sheets = new SheetCollection($sheetName);

        $header_style = (new StyleBuilder())
            ->setShouldWrapText()->setShouldShrinkToFit()
            ->build();

        return (new FastExcel($sheets))
            ->headerStyle($header_style)
            ->download('import_sample.xlsx');
    }

    private function getProfileSamples($field)
    {
        $profile = null;
        switch ($field) {
            case 'Real Hourly Capacity':
                $profile = 'hourly_profile_sample.xlsx';
                break;
            case 'Real Daily Capacity':
                $profile = 'daily_profile_sample.xlsx';
                break;
            case 'Real Monthly Capacity':
                $profile = 'monthly_profile_sample.xlsx';
                break;
        }

        return $profile;
    }
}


