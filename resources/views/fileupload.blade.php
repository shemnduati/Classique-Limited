@extends('main')
@section('content')
<div class="container">
    <div class="pagetitle">
        <h1>File Upload</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">FileUpload</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->
      <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">File Upload</h5>
        
                  <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                
                    <div class="form-group row">
                        <label for="file" class="col-lg-2 col-form-label">Choose Excel File</label>
                        <div class="col-lg-6">
                            <input type="file" name="file" id="file" class="form-control">
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <div class="offset-lg-2 col-lg-6">
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
        
                </div>
              </div>
            
        </div>
        </div>
       
</div>

@endsection