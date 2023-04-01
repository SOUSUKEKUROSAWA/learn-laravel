@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-3 p-5">
            <img src="\svg\freeCodeCampLogo.svg" class="rounded-circle" style="width: 100%;">
        </div>
        <div class="col-9 pt-5">
            <div>
                <h1>{{ $user->username }}</h1>
            </div>
            <div class="d-flex">
                <div class="pr-5"><strong>540</strong> posts</div>
                <div class="pr-5"><strong>134K</strong> followers</div>
                <div class="pr-5"><strong>393</strong> following</div>
            </div>
            <div class="pt-4 font-weight-bold">freeCodeCamp.org</div>
            <div>
                We're a global community of millions of people learning to code together.
                LearnToCodeRPG: https://www.freecodecamp.org/news/learn-to-code-rpg/
            </div>
            <div><a href="#">www.freecodecamp.org</a></div>
        </div>
    </div>
    <div class="row pt-5">
        <div class="col-4">
            <img src="https://storage.googleapis.com/zenn-user-upload/b082d9f31837-20230330.jpg" class="w-100">
        </div>
        <div class="col-4">
            <img src="https://storage.googleapis.com/zenn-user-upload/510a2578683c-20230330.jpg" class="w-100">
        </div>
        <div class="col-4">
            <img src="https://storage.googleapis.com/zenn-user-upload/726b7e9e1d51-20230330.jpg" class="w-100">
        </div>
    </div>
</div>
@endsection
