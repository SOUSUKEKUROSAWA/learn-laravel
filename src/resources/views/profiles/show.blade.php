@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-3 p-5">
            <img src="\svg\freeCodeCampLogo.svg" class="rounded-circle">
        </div>
        <div class="col-9 pt-5">
            <div class="d-flex justify-content-between align-items-baseline">
                <h1>{{ $user->username }}</h1>
                <a href="#">Add New Post</a>
            </div>
            <div class="d-flex">
                <div class="pr-5"><strong>540</strong> posts</div>
                <div class="pr-5"><strong>134K</strong> followers</div>
                <div class="pr-5"><strong>393</strong> following</div>
            </div>
            <div class="pt-4 font-weight-bold">{{ $user->profile->title }}</div>
            <div>{{ $user->profile->description }}</div>
            <div><a href="#">{{ $user->profile->url }}</a></div>
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
