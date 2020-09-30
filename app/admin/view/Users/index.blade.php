@extends("layouts.body")
@section("content")
 这里是content{{$id}}   {{$name}}
 </br>

 @forelse ($data as $k => $v)
  // $data有值
  {{ $k }} <br />
  @empty
   // $data没有值
@endforelse


 @if($id>1)
  id大于1
  @else
  id小于1
 @endif


 <img src='@asset("img/resource.jpg")' />


 @php
 echo \framework\facade\Cache::get("name");
 @endphp

 @inject('Cache',framework\facade)
 <div>
  Monthly Revenue: {{ $Cache::get('name') }}.
 </div>


@endsection

@section("footer")
 这里是footer</br>

 @include("floder.template")

 @component('component.item',array('color'=>"red"))
  @slot('title')
   COMPONENT #2
  @endslot
  <strong>Whoops!</strong> Something went wrong! (the code is right btw)
 @endcomponent

@endsection




