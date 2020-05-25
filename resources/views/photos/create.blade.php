@extends('layouts.layout')
@section('title')
  {{$title}}
@endsection

@section('header')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2>{{$title}}</h2>
      </div>
    </div>
  </div>
@endsection

@section('main')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <form action="{{route('photos.store')}}" method="POST">
          @method('POST')
          @csrf
            <div class="form-group">
              <label for="title">Title</label>
              <input type="text" name="title" class="form-control" value="{{old('title')}}">
              @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control" name="description" cols="30" rows="10"> {{old('description')}}</textarea>
               @error('description')
                <div class="alert alert-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label for="title">Path</label>
              <input type="text" name="path" class="form-control" value="{{old('path')}}">
              @error('path')
                <div class="alert alert-danger">{{ $message }}</div>
              @enderror
            </div>
            <input class="btn btn-primary" type="submit" value="Save">
        </form>
      </div>
    </div>
  </div>
@endsection

@section('footer')

@endsection
