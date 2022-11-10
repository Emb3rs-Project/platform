<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Objects\Sinks\CreatesSinks;
use App\Contracts\Embers\Objects\Sinks\DestroysSinks;
use App\Contracts\Embers\Objects\Sinks\EditsSinks;
use App\Contracts\Embers\Objects\Sinks\IndexesSinks;
use App\Contracts\Embers\Objects\Sinks\ShowsSinks;
use App\Contracts\Embers\Objects\Sinks\StoresSinks;
use App\Contracts\Embers\Objects\Sinks\UpdatesSinks;
use App\Http\Controllers\Controller;
use App\Models\Instance;
use App\Models\Simulation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SinkController extends Controller
{

    /**
     * @return \Inertia\Response
     */
    public function index()
    {

        $sinks = Instance::whereIn('template_id', [14])
            ->orderBy('created_at', 'desc')->get();
        return Inertia::render('Objects/Sinks/SinkIndex',
            ['sinks' => $sinks]);
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
            $locations
        ] = app(CreatesSinks::class)->create($request->user());

        return [
            'slideOver' => 'Objects/Sinks/SinkCreate',
            'props' => [
                'templates' => $templates,
                'locations' => $locations
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
        $sink = app(StoresSinks::class)->store($request->user(), $request->all());

        $sinkName = $sink->name;
        $team = $request->user()->currentTeam;
        $message = 'created a new Sink at';
        $tag = [['name' => "Sink #$sinkName", 'path' => 'objects.sinks.show']];
        app(NotificationContoller::class)->objectNotify($request->user(), $team, $tag, $message, $sink->id);

        return redirect()->route('objects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array<string, mixed>
     */
    public function show(Request $request, int $id)
    {
        [$sink, $templateProperties] = app(ShowsSinks::class)->show($request->user(), $id);

        return [
            'slideOver' => 'Objects/Sinks/SinkDetails',
            'props' => [
                'instance' => $sink,
                'templateProperties' => $templateProperties,
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
    public function edit(Request $request, int $id)
    {
        [
            $templates,
            $locations,
            $instance
        ] = app(EditsSinks::class)->edit($request->user(), $id);

        return [
            'slideOver' => 'Objects/Sinks/SinkEdit',
            'props' => [
                'templates' => $templates,
                'locations' => $locations,
                'instance' => $instance
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
    public function update(Request $request, int $id)
    {
        $sink = app(UpdatesSinks::class)->update($request->user(), $id, $request->all());

        $sinkName = $sink->name;
        $team = $request->user()->currentTeam;
        $message = 'updated a new Sink at';
        $tag = [['name' => "Sink #$sinkName", 'path' => 'objects.sinks.show']];
        app(NotificationContoller::class)->objectNotify($request->user(), $team, $tag, $message, $id);

        return redirect()->route('objects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, int $id)
    {
        $sink = app(DestroysSinks::class)->destroy($request->user(), $id);

        $sinkName = $sink->name;
        $team = $request->user()->currentTeam;
        $message = 'destroyed a Sink at';
        $tag = [['name' => "Sink #$sinkName", 'path' => '']];
        app(NotificationContoller::class)->objectNotify($request->user(), $team, $tag, $message, $id);

        return redirect()->route('objects.index');
    }

    public function export(Request $request)
    {
        $sinks = Instance::whereIn('id', $request->input('ids'))->get();
        $csv = 'sink.csv';
        $file_pointer = fopen($csv, 'w');

        $keys = collect($sinks[0]['values'])->except('characterization')->keys()->toArray();
        $keys[] = 'template';
        $keys[] = 'latitude';
        $keys[] = 'logitude';
        fputcsv($file_pointer, $keys);
        foreach ($sinks as $i) {
            $data = collect($i['values'])->except('characterization')->toArray();
            $data['template'] = $i['template']['name'];
            $data['latitude'] = $i['location']['data']['center'][0];
            $data['longitude'] = $i['location']['data']['center'][1];
            // Write the data to the CSV file
            fputcsv($file_pointer, $data);
        }


        $base = base64_encode(file_get_contents('sink.csv'));
        unlink('sink.csv');
        return $base;
    }
}
