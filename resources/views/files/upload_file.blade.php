@extends('layouts.app')

@section('content')
    <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-md-6">
                <input type="text" name="name" class="form-control">
            </div>
            <div class="col-md-6">
                <input type="file" name="file" class="form-control">
            </div>

            <div class="col-md-6">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>

        </div>
    </form>





    @endsection
