@extends('layouts.app')

@section('content')
    <div class="container">
        <br class="row justify-content-center">

            @if($errors->has('file'))
                <div class="alert alert-danger" role="alert">
                    {{ $errors->first('file') }}
                </div>
            @endif

      <!-- Form insert file -->
            <form style="margin-bottom: 20px" action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control" placeholder="nome file facoltativo">
                    </div>
                    <div class="col-md-6">
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success">Invia</button>
                    </div>
                </div>
            </form>
        <!-- end -->


        <!-- Search form -->
        <p>
            <form action="{{ route('search') }}" method ="get" role="search">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class = "input-group">
                            <input type = "text" class = "form-control" name="name" placeholder="Cerca file">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-10">
                            <input class="form-control" name="data"  type="date" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type ="submit" class="search btn btn-primary" id="search">Cerca <i class="fas fa-search"> </i></button>
                    </div>
                </div>
            </form>
        </p>

        <!-- End  -->

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">DOCUMENTO</th>
                    <th scope="col">CREATO IL</th>
                    <th scope="col">DOWNLAOD</th>
                </tr>
                </thead>
                <tbody>

                @foreach($documents as $document)
                    <tr>
                        <th>{{ $document->name }}</th>
                        <th>{{ $document->data_insert->format('d/m/Y') }}</th>
                        <th>
                            <a href="{{ asset($document->url) }}" target="_blank" title="apri">
                                <i class="fas fa-clipboard" style='font-size:20px'></i>
                            </a> &nbsp;
                            <a href="{{ route('download', $document) }}" target="_blank" title="scarica">
                                <i class="fas fa-download" style='font-size:20px'></i>
                            </a>
                            <a href="{{ route('delete.files', $document) }}" title="delete">
                                <i class="fas fa-trash" style='font-size:20px'></i>
                            </a> &nbsp;
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $documents->links() }}
        </div>
    </div>
@endsection






