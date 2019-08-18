@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2>Update Answer</h2>
                        <div class="ml-auto">
                            <a href="{{route('questions.index',[$question->id,$answer->id])}}" class="btn btn-outline-secondary">Back To All Question</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{route('questions.answers.update',[$question->id,$answer->id])}}" method="post">
                        @csrf
                        @method('PUT')
                       
                        <div class="form-group">
                            <label for="question-body">Explain Your Answer</label>
                            <textarea name="body" class="form-control {{$errors->has('body') ? 'is-invalid': ''}}" rows="10" id="question-body">{{old('body',$answer->body)}}</textarea>
                             @if($errors->has('body'))
                            <div class="invalid-feedback">
                                <strong>{{$errors->first('body')}}</strong>
                            </div>
                            @endif 
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-outline-primary btn-lg">
                               Update Your Answer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
