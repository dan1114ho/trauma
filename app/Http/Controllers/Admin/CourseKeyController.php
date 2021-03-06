<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\CourseKey;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseKeyGenerateRequest;

class CourseKeyController extends AdminController {

    private function generateRandomString($length = 20) {
        return substr(str_shuffle(
                str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)))
            ), 1, $length
        );
    }

    public function create(Course $course, CourseKeyGenerateRequest $request)
    {
        for ($i = 0; $i < $request->count; $i++) {
            $key = new CourseKey;
            $key->course_id = $course->id;
            $key->key = $request->prefix.$this->generateRandomString();
            $key->tag = $request->tag;

            $key->save();
        }

        session()->flash('courseMessage', "$request->count key(s) have been generated.");

        return response()->json([
            'success' => true,
            'redirect' => action('Admin\CourseController@edit', [
                'course' => $course
            ])
        ]);
    }

    public function export(Course $course)
    {
        $this->download_send_headers('course_keys.csv');

        ob_start();

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array(
            'Key',
            'Created At',
            'User Id',
            'Full Name',
            'Email',
            'Redeemed At',
            'Enabled',
        ));
        foreach ($course->keys as $key) {
            fputcsv($output, array(
                $key->key,
                $key->created_at,
                $key->redeemed ? $key->redeemedUser->id : '',
                $key->redeemed ? $key->redeemedUser->first_name.' '.$key->redeemedUser->last_name : '',
                $key->redeemed ? $key->redeemedUser->email : '',
                $key->redeemed ? $key->redeemed_at : '',
                $key->enabled ? 'Yes' : 'No',
            ));
        }

        fclose($output);
        echo ob_get_clean();
        die;
    }

    public function disable(Course $course, CourseKey $courseKey)
    {
        // if (!$courseKey->redeemed) {
        $courseKey->enabled = false;
        $courseKey->save();

        session()->flash('courseMessage', "Course key has been disabled.");
        // }

        return redirect()->action('Admin\CourseController@edit', $course);
    }

    public function enable(Course $course, CourseKey $courseKey)
    {
        $courseKey->enabled = true;
        $courseKey->save();
        session()->flash('courseMessage', "Course key has been enabled.");

        return redirect()->action('Admin\CourseController@edit', $course);
    }
}
