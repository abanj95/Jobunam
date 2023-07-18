<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use function file_exists;
use function public_path;
use function storage_path;

class JobApplicationController extends Controller
{
    public function index()
    {
        $applicationsWithPostAndUser = null;
        $company = auth()->user()->company;

        if ($company) {
            $ids =  $company->posts()->pluck('id');
            $applications = JobApplication::whereIn('post_id', $ids);
            $applicationsWithPostAndUser = $applications->with('user', 'post', 'file')->latest()->paginate(10);
        }

        return view('job-application.index')->with([
            'applications' => $applicationsWithPostAndUser,
        ]);
    }

    public function show($id)
    {
        $application = JobApplication::find($id);

        $post = $application->post()->first();
        $userId = $application->user_id;
        $applicant = User::find($userId);

        $company = $post->company()->first();
        return view('job-application.show')->with([
            'applicant' => $applicant,
            'post' => $post,
            'company' => $company,
            'application' => $application
        ]);
    }

    public function download($id)
    {
        $file = new File();
        // php artisan storage:link
        $record = $file->where('related_object_id', $id)->first();
        if (empty($record) === false) {
            $headers = array('Content-Type: application/pdf');
            $path = storage_path() .'/'.'app'.'/public/uploads/'.$record->name;
            if (file_exists($path)) {
                return \Illuminate\Support\Facades\Response::download($path, $id.'.pdf', $headers);
            }
        }
    }

    public function destroy(Request $request)
    {
        $application = JobApplication::find($request->application_id);
        $application->delete();
        Alert::toast('Company deleleted', 'warning');
        return redirect()->route('jobApplication.index');
    }
}
