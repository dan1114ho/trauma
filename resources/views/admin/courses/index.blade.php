@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Courses @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Courses
    </h2>

    @if (Session::has('courseMessage'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('courseMessage') }}
        </div>
    @endif
    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    {!! Form::open(array('url' => url('admin/courses'), 'method' => 'get', 'id' => 'form-courses-search')) !!}
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2">
                    {!! Form::label('id', 'Id', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('id', Request::get('id'), array('class' => 'form-control', 'placeholder' => 'Course Id')) !!}
                        <span class="help-block">{{ $errors->first('id', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    {!! Form::label('title', 'Title', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('title', Request::get('title'), array('class' => 'form-control', 'placeholder' => 'Title')) !!}
                        <span class="help-block">{{ $errors->first('title', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    {!! Form::label('location', 'Objectives', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('location', Request::get('location'), array('class' => 'form-control', 'placeholder' => 'Location')) !!}
                        <span class="help-block">{{ $errors->first('location', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-3 text-right">
                     <button type="submit" class="btn btn-sm btn-primary">
                        Search Courses
                    </button>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

    <h3 class="section-title">
        Search Results
        <div class="pull-right">
            <a href="{!! url('admin/courses/create') !!}" class="btn btn-sm btn-primary">Create New Course</a>
        </div>
    </h3>
    <div class="table-responsive table-container">
        <table class="table table-hover">
            @foreach ($courses as $course)
            <tr>
                <td><a href="{!! url('admin/courses/'.$course->id.'/edit') !!}">#{{ $course->id }}</a></td>
                <td>
                    <a href="{!! url('admin/courses/'.$course->id.'/edit') !!}">{{ $course->title }}</a><br>
                    {{ $course->online_only ? 'Online' : $course->date_start . '-' . $course->date_end }}
                </td>
                <td>{{ $course->location }}</td>
                <td>
                    @if (is_array($course->instructors) && count($course->instructors) > 0)
                        @for ($i = 0; $i < count($course->instructors); $i++)
                        {{ $users[$course->instructors[$i]]['first_name'] }} {{ $users[$course->instructors[$i]]['last_name'] }}
                            @if ($i < count($course->instructors)-1)<br> @endif
                        @endfor
                    @else
                        -
                    @endif
                </td>
                <td class="text-right">
                    <a
                        href="{!! url('admin/courses/'.$course->id.'/edit') !!}"
                        class="btn btn-primary"
                    >
                         <i class="fa fa-pencil"></i> Edit
                    </a>
                    <a
                        class="btn btn-primary"
                        href="{{ url('admin/courses').'/'.$course->id.'/copy' }}"
                        onclick="return confirm('Are you sure?')"
                    >
                        <i class="fa fa-copy"></i> Copy
                    </a>
                    <a
                        class="btn btn-primary"
                        href="{{ route('course.browse', $course->id) }}"
                    >
                        <i class="fa fa-search"></i> Browse
                    </a>
                </td>
            </tr>
            @endforeach
        </table>
        @if (!count($courses))
        <h4>No courses found for given search.</h4>
        @endif
    </div>
@endsection
