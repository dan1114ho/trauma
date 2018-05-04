@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title'){{ $course->title }} @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Edit Course
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

    {!! Form::model($course, array('url' => url('admin/courses/'.$course->id), 'method' => 'put', 'class' => 'form-course', 'files'=> true)) !!}
    <input type="hidden" name="online_only" value="0">
    <input type="hidden" name="published" value="0">

    <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
        <div class="row">
            <div class="col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6 text-center">
                @if ($course->photo)
                    <img class="img img-course" alt="{{$course->photo}}" src="{!! url('images/courses/'.$course->id.'/'.$course->photo) !!}"/>
                @else
                    <img class="img img-course" alt="no avatar" src="{!! url('images/no_photo.png') !!}"/>
                @endif
                <div class="m-b-5"><span class='label label-info' id="upload-file-info"></span></div>
                <label class="btn btn-sm btn-primary" for="course-photo">
                    <input id="course-photo" name="photo" type="file" value="Upload" style="display:none"
                    onchange="$('#upload-file-info').html(this.files[0].name)">
                    Choose Photo
                </label>
                <span class="help-block">{{ $errors->first('image', ':message') }}</span>
            </div>
        </div>
    </div>

    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
        {!! Form::label('title', 'Title', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('title', $course->title, array('class' => 'form-control', 'placeholder' => 'Title *')) !!}
            <span class="help-block">{{ $errors->first('title', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
        {!! Form::label('location', 'Location', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('location', $course->location, array('class' => 'form-control', 'placeholder' => 'Location *')) !!}
            <span class="help-block">{{ $errors->first('location', ':message') }}</span>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-3">
                <div class="checkbox inline">
                    <label>
                        {!! Form::checkbox('online_only', true, $course->online_only, array('class' => 'check-online-only')) !!} Online Only
                    </label>
                </div>
            </div>
            <div class="col-sm-4 {{ $errors->has('date_start') ? 'has-error' : '' }}">
                {!! Form::label('date_start', 'Start Date', array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('date_start', $course->online_only ? null : $course->date_start, array('class' => 'form-control form-input-date', 'placeholder' => 'Start Date *')) !!}
                    <span class="help-block">{{ $errors->first('date_start', ':message') }}</span>
                </div>
            </div>
            <div class="col-sm-4 {{ $errors->has('date_end') ? 'has-error' : '' }}">
                {!! Form::label('date_end', 'End Date', array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('date_end', $course->online_only ? null : $course->date_end, array('class' => 'form-control form-input-date', 'placeholder' => 'End Date *')) !!}
                    <span class="help-block">{{ $errors->first('date_end', ':message') }}</span>
                </div>
            </div>
        </div>
    </div>

    <h3 class="section-title">
        Instructors
        @if (Auth::user()->role === 'admin')
        <div class="pull-right">
            <a href="{!! url('admin/users/create') !!}" class="btn btn-sm btn-primary">Invite a Faculty</a>
        </div>
        @endif
    </h3>
    <div class="form-group">
        @foreach ($faculties as $faculty)
        <div class="checkbox inline">
            <label>
                {!! Form::checkbox('instructors[]', $faculty->id, is_array($course->instructors) && in_array($faculty->id, $course->instructors)) !!} ID: {{ $faculty->id }}, {{ $faculty->first_name }} {{ $faculty->last_name }} ({{ $faculty->email }})
            </label>
        </div>
        @endforeach
    </div>

    <div class="form-group {{ $errors->has('overview') ? 'has-error' : '' }}">
        {!! Form::label('overview', 'Overview', array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::textarea('overview', $course->overview, array('class' => 'form-control', 'placeholder' => 'Overview *', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('overview', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('objective') ? 'has-error' : '' }}">
        {!! Form::label('objective', 'Objective', array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::textarea('objective', $course->objective, array('class' => 'form-control', 'placeholder' => 'Objective *', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('objective', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('prerequisites') ? 'has-error' : '' }}">
        {!! Form::label('prerequisites', 'Pre-requisites', array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::textarea('prerequisites', $course->prerequisites, array('class' => 'form-control', 'placeholder' => 'Pre-requisites', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('prerequisites', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('resources') ? 'has-error' : '' }}">
        {!! Form::label('resources', 'Resources', array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::textarea('resources',  $course->resources, array('class' => 'form-control', 'placeholder' => 'Textbook or Additonal Resources', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('resources', ':message') }}</span>
        </div>
    </div>

    <h3 class="section-title">
        Course Modules
        <div class="pull-right">
            <a href="{{ url('/admin/courses/'.$course->id.'/modules/create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add</a>
        </div>
    </h3>
    <div>
        @foreach ($course->modules as $module)
            <div class="row">
                <div class="col-sm-8">
                    <strong>{{ $module->title }}</strong>
                </div>
                <div class="col-sm-4 text-right">
                    <a href="{!! url('admin/courses/'.$course->id.'/modules/'.$module->id.'/edit') !!}" class="btn btn-xs btn-primary">Edit</a>
                    <a href="{!! url('admin/courses/'.$course->id.'/modules/'.$module->id.'/delete') !!}" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            </div>
            @foreach ($module->documents as $document)
            <div class="row">
                <div class="col-sm-11 col-sm-offset-1">
                    @if ($document->type === 'url')
                        <a href="{{ $document->url }}"><i class="fa fa-link"></i> {{ $document->url }}</a>
                    @else
                        <a href="{{ url('images/courses/'.$course->id.'/modules/'.$module->id.'/'.$document->file) }}"><i class="fa fa-file-o"></i> {{ $document->filename }}</a>
                    @endif
                </div>
            </div>
            @endforeach
        @endforeach
    </div>

    <hr>

    <div class="form-group">
        <div class="checkbox inline">
            <label>
                {!! Form::checkbox('published', true, $course->published) !!} Published
            </label>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-6">
                <a class="btn btn-danger" href="{{ url('admin/courses').'/'.$course->id.'/delete' }}" onclick="return confirm('Are you sure?')">
                    Delete Course
                </a>
            </div>
            <div class="col-xs-6 text-right">
                <button type="submit" class="btn btn-primary">
                    Update Course
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
