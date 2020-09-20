@extends('dashboard.master.base')
@section('title',$title)
@section('style')
    <link rel="stylesheet" href="{{asset('panel/dropify/dist/css/dropify.min.css')}}">
@endsection
@section('content')
    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-12">
                    <div class="element-wrapper">
                        <div class="element-box">
                            {!! Form::open(['method'=>'post', 'files'=>true, 'enctype' => 'multipart/form-data', 'route'=>[$action], 'class' => 'formValidate']) !!}
                            {!! Form::hidden('add_by', \Illuminate\Support\Facades\Auth::user()->id) !!}
                            <div class="element-info">
                                <div class="element-info-with-icon">
                                    <div class="element-info-icon">
                                        <div class="os-icon os-icon-wallet-loaded"></div>
                                    </div>
                                    <div class="element-info-text">
                                        <h5 class="element-inner-header">
                                            إضافة
                                        </h5>
                                        @if(isset($create_alert))
                                            <div class="element-inner-desc">
                                                {{$create_alert}}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if(isset($create_fields))
                                <fieldset class="form-group">
                                    <div class="row">
                                        @foreach($create_fields as $key=>$value)
                                            @if(isset($languages))
                                                @foreach($settings->languages as $lang)
                                                    @if($value=='note')
                                                        <div class="col-sm-12">
                                                            <div class="form-group" id="{{$value}}">
                                                                <label> {{$key}} </label>
                                                                <textarea name="{{$value}}.'_'.{{$lang}}" class="form-control" cols="80" rows="10" id="ckeditor1"></textarea>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-sm-12">
                                                            <div class="form-group" id="{{$value}}">
                                                                <label for=""> {{$key}}</label>
                                                                <input name="{{$value}}.'_'.{{$lang}}" class="form-control" type="text">
                                                                <div class="help-block form-text with-errors form-control-feedback"></div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                @if($value=='note')
                                                    <div class="col-sm-12">
                                                        <div class="form-group" id="{{$value}}">
                                                            <label> {{$key}} </label>
                                                            <textarea name="{{$value}}" class="form-control" cols="80" rows="5"></textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-sm-12">
                                                        <div class="form-group" id="{{$value}}">
                                                            <label for=""> {{$key}}</label>
                                                            <input name="{{$value}}" class="form-control" type="text">
                                                            <div class="help-block form-text with-errors form-control-feedback"></div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                            @if(isset($password))
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for=""> كلمة المرور</label><input name="password" class="form-control" data-minlength="6" placeholder="كلمة المرور" type="password">
                                                        <div class="help-block form-text text-muted form-control-feedback">
                                                            يجب أﻻ يقل عن 6 خانات على الأقل
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(isset($image))
                                                <div class="col-sm-6">
                                                    <div class="white-box">
                                                        <label for="input-file-now-custom-1">الصورة</label>
                                                        <input name="image" type="file" id="input-file-now-custom-1 image" class="dropify" data-default-file="{{asset('media/images/user/default.jpeg')}}"/>
                                                    </div>
                                                </div>
                                            @endif
                                    </div>
                                </fieldset>
                            @endif
                            <fieldset>
                                <div class="row">
                                    @if(isset($date))
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for=""> Date of Birth</label><input class="single-daterange form-control" placeholder="Date of birth" type="text" value="04/12/1978">
                                            </div>
                                        </div>
                                    @endif
                                    @if(isset($select))
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for=""> Regular select</label>
                                                <select class="form-control">
                                                    <option value="New York">
                                                        New York
                                                    </option>
                                                    <option value="California">
                                                        California
                                                    </option>
                                                    <option value="Boston">
                                                        Boston
                                                    </option>
                                                    <option value="Texas">
                                                        Texas
                                                    </option>
                                                    <option value="Colorado">
                                                        Colorado
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    @if(isset($multi_select))
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for=""> Multiselect</label>
                                                <select class="form-control select2" multiple="true">
                                                    <option selected="true">
                                                        New York
                                                    </option>
                                                    <option selected="true">
                                                        California
                                                    </option>
                                                    <option>
                                                        Boston
                                                    </option>
                                                    <option>
                                                        Texas
                                                    </option>
                                                    <option>
                                                        Colorado
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </fieldset>
                            <div class="form-buttons-w">
                                <button class="btn btn-primary create-submit" type="submit"> إضافة</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('panel/dropify/dist/js/dropify.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            // Basic
            $('.dropify').dropify();
            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });
            // Used events
            var drEvent = $('#input-file-events').dropify();
            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });
            drEvent.on('dropify.afterClear', function(event, element) {
                alert('File deleted');
            });
            drEvent.on('dropify.errors', function(event, element) {
                console.log('Has Errors');
            });
            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function(e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
    </script>
    @if($errors->any())
        <div style="visibility: hidden" id="errors" data-content="{{$errors}}"></div>
        <script type="text/javascript">
            $(document).ready(function () {
                var errors=$('#errors').attr('data-content');
                $.each(JSON.parse(errors), function( index, value ) {
                    // $('input[name="note"]').notify(
                    $('#'+index).notify(
                        value,
                        'error',
                        { position:"top" }
                    );
                });
            })
        </script>
    @endif
@stop
