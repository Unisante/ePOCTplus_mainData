<?php

namespace App\Http\Controllers;

use DateTime;
use SplFileInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class FailedJsonController extends Controller
{
    /**
     * To block any non-authorized user
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Access_Export_Panel');
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return;
        }

        $path = storage_path('app/' . Config::get('medal.storage.json_failure_dir') . '/');
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 50;

        $jsons = collect(File::files($path))
            ->sortBy(function (SplFileInfo $file) {
                return $file->getCTime();
            });

        $currentPageItems = $jsons->slice(($currentPage * $perPage) - $perPage, $perPage)
            ->map(function (SplFileInfo $file) {
                $json_content = json_decode(File::get($file->getRealPath()), true);
                $file->group_id = $json_content['patient']['group_id'] ?? '';
                $file->name = $file->getBaseName();
                $file->date = Carbon::createFromTimestampMs($json_content['patient']['createdAt'])->format('Y-m-d');
                $file->version_id = $json_content['version_id'] ?? '';
                $file->json_version = $json_content['json_version'] ?? '';
                return $file;
            });

        $paginatedJsons = new LengthAwarePaginator($currentPageItems, $jsons->count(), $perPage);
        $paginatedJsons->setPath($request->url());

        return view('failed.index', [
            'jsons' => $paginatedJsons,
        ]);
    }
}
