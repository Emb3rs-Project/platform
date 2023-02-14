<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Objects\Sources\CreatesSources;
use App\Contracts\Embers\Objects\Sources\DestroysSources;
use App\Contracts\Embers\Objects\Sources\EditsSources;
use App\Contracts\Embers\Objects\Sources\ShowsSources;
use App\Contracts\Embers\Objects\Sources\StoresSources;
use App\Contracts\Embers\Objects\Sources\UpdatesSources;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Rap2hpoutre\FastExcel\FastExcel;

class SourceController extends Controller
{

    public function index()
    {
        $sources = Auth::user()
            ->currentTeam
            ->instances()
            ->whereHas('template', fn($query) => $query->where('category_id', Category::SOURCE))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Objects/Sources/SourceIndex',
            ['sources' => $sources]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function create(Request $request)
    {
        [
            $templates,
            $equipment,
            $equipmentCategories,
            $processes,
            $processesCategories,
            $locations
        ] = app(CreatesSources::class)->create($request->user());

        return [
            "slideOver" => "Objects/Sources/SourceCreate",
            "props" => [
                "templates" => $templates,
                "equipment" => $equipment,
                "equipmentCategories" => $equipmentCategories,
                "processes" => $processes,
                "processesCategories" => $processesCategories,
                "locations" => $locations,
            ]
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $source = app(StoresSources::class)->store($request->user(), $request->all());

        $sourceName = $source->name;
        $team = $request->user()->currentTeam;
        $message = 'created a new Source at';
        $tag = [['name' => "Source #$sourceName", 'path' => 'objects.sources.show']];
        app(NotificationContoller::class)->objectNotify($request->user(), $team, $tag, $message, $source->id);

        return redirect()->route('objects.index');
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
        [
            $instance,
            $equipmentTemplates,
            $processesTemplates
        ] = app(ShowsSources::class)->show($request->user(), $id);

        return [
            "slideOver" => "Objects/Sources/SourceDetails",
            "props" => [
                "instance" => $instance,
                "equipment" => $equipmentTemplates,
                "processes" => $processesTemplates
            ]
        ];
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
            $instance,
            $templates,
            $locations,
            $equipment,
            $equipmentCategories,
            $processes,
            $processesCategories,
        ] = app(EditsSources::class)->edit($request->user(), $id);

        return [
            "slideOver" => "Objects/Sources/SourceEdit",
            "props" => [
                "instance" => $instance,
                "templates" => $templates,
                "locations" => $locations,
                "equipment" => $equipment,
                "equipmentCategories" => $equipmentCategories,
                "processes" => $processes,
                "processesCategories" => $processesCategories,
            ]
        ];
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
        $source = app(UpdatesSources::class)->update($request->user(), $id, $request->all());

        $sourceName = $source->name;
        $team = $request->user()->currentTeam;
        $message = 'updated a Source at';
        $tag = [['name' => "Source #$sourceName", 'path' => 'objects.sources.show']];
        app(NotificationContoller::class)->objectNotify($request->user(), $team, $tag, $message, $source->id);

        // return redirect()->route('objects.sources.show', $updatedSource->id);
        return redirect()->route('objects.index');
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
        $source = app(DestroysSources::class)->destroy($request->user(), $id);

        $sourceName = $source->name;
        $team = $request->user()->currentTeam;
        $message = 'destroyed a Source at';
        $tag = [['name' => "Source #$sourceName", 'path' => 'objects.sources.show']];
        app(NotificationContoller::class)->objectNotify($request->user(), $team, $tag, $message, $source->id);

        return redirect()->route('objects.index');
    }

    public function export(Request $request)
    {
        if (count($request->input('ids')) === 0){
            $sources = Auth::user()
                ->currentTeam
                ->instances()
                ->with('template', 'location')
                ->whereHas('template', fn($query) => $query->where('category_id', Category::SOURCE))->get();
        } else{
            $sources = Instance::with('template', 'location')->whereIn('id', $request->input('ids'))->get();
        }

        $props = Template::with('templateProperties', 'templateProperties.property')->orderBy("order")->where('id', 15)->get();


        $keys = [];
        $props->each(function ($item) use (&$keys) {
            $item->templateProperties->sortBy('order')->each(function ($tempProp) use (&$keys) {
                $keys[$tempProp->property['symbolic_name']] = $tempProp->property['name'];
            });
        });
        $keys['template'] = 'template';
        $keys['latitude'] = 'latitude';
        $keys['longitude'] = 'longitude';

        $alldata = [];

        foreach ($sources as $i) {
            foreach ($keys as $column => $title) {
                $data[$title] = array_key_exists($column, $i['values']['properties']) ? $i['values']['properties'][$column] : '';
            }
            $data['template'] = $i['template']['name'];

            $data['latitude'] = $i['location']['data']['center'][0];
            $data['longitude'] = $i['location']['data']['center'][1];

            $alldata[] = $data;
        }

        return (new FastExcel(collect($alldata)))->download('source.xlsx');
    }
}
