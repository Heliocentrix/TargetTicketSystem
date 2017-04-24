<?php

namespace TargetInk\Http\Controllers;

use Illuminate\Http\Request;
use TargetInk\Http\Requests;
use TargetInk\Http\Controllers\Controller;
use TargetInk\User;
use JsValidator;
use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;
use Debugbar;

class AppController extends Controller
{
    public function __construct()
    {
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = null;
        if(auth()->user()->admin) {
            $clients = User::where('admin', 0)->orderBy('company')->get();
        }
        return view('dashboard', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.advertEdit');
    }

    public function showMaintenance(Request $request)
    {
        $clients = User::with('openTickets')->where('admin', 0)->orderBy('company')->get();

        $maintenanceList = view('dashboard.tickets.tickets', compact('clients'));
        if($request->ajax()) {
            return $maintenanceList;
        } else {
            return view('dashboard', compact('maintenanceList'));
        }
    }

    public function js() {
        $adminValidators = [
            'TargetInk\Http\Requests\ClientCreateRequest' => '#new-client-form',
            'TargetInk\Http\Requests\ClientUpdateRequest' => '#update-client-form',

            'TargetInk\Http\Requests\AdvertCreateRequest' => '#new-advert-form',
            'TargetInk\Http\Requests\AdvertUpdateRequest' => '#update-advert-form',

            'TargetInk\Http\Requests\ServiceCreateRequest' => '#new-service-form',
            'TargetInk\Http\Requests\ServiceUpdateRequest' => '#update-service-form',

            'TargetInk\Http\Requests\DocumentSeoCreateRequest' => '#new-seo-form',
            'TargetInk\Http\Requests\DocumentSeoUpdateRequest' => '#update-seo-form',

            'TargetInk\Http\Requests\DocumentInfoCreateRequest' => '#new-info-form',
            'TargetInk\Http\Requests\DocumentInfoUpdateRequest' => '#update-info-form',
        ];

        $userValidators = [
        ];
        
        if(auth()->check() && auth()->user()->admin) {
            $v = $adminValidators;
        } else {
            $v = $userValidators;
        }

        $content = '';
        foreach($v as $requestClass => $formSelector) {
            $content .= JsValidator::formRequest($requestClass, $formSelector);
        }

        return response($content)->header('Content-Type', 'text/javascript');
    }

    public function glide(Request $request, $path)
    {
        Debugbar::disable();

        // Image builder for glide
        $filesystem = config('filesystems.cloud');
        $client = \Aws\S3\S3Client::factory([
            'credentials' => [
                'key'    => config('filesystems.disks.' . $filesystem . '.key'),
                'secret' => config('filesystems.disks.' . $filesystem . '.secret'),
            ],
            'region' => config('filesystems.disks.' . $filesystem . '.region'),
            'version' => 'latest',
        ]);
        $server = ServerFactory::create([
            'source' => new \League\Flysystem\Filesystem(new \League\Flysystem\AwsS3v3\AwsS3Adapter($client, config('filesystems.disks.' . $filesystem . '.bucket'))),
            'cache' => new \League\Flysystem\Filesystem(new \League\Flysystem\Adapter\Local(storage_path() . '/app')),
            'cache_path_prefix' => 'cache',
            'response' => new LaravelResponseFactory(),
        ]);

        return $server->getImageResponse($path, $request->all());
    }
}
