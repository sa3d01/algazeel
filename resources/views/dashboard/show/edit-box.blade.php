<div class="col-sm-7">
    <div class="element-wrapper">
        <div class="element-box">
            {!! Form::model($row, ['method'=>'POST','name'=>'update', 'files'=>true, 'route'=>[$action, $row->id], 'id' => 'formValidate']) !!}
            {!! Form::hidden('id', $row->id) !!}
            {!! Form::hidden('edit_by', \Illuminate\Support\Facades\Auth::user()->id) !!}
                <div class="element-info">
                    <div class="element-info-with-icon">
                        <div class="element-info-icon">
                            <div class="os-icon os-icon-wallet-loaded"></div>
                        </div>
                        <div class="element-info-text">
                            <h5 class="element-inner-header">
                                تعديل البيانات
                            </h5>
                            @if(isset($edit_alert))
                                <div class="element-inner-desc">
                                    {{$edit_alert}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @if(isset($edit_fields))
                    <fieldset class="form-group">
                        <div class="row">
                            @foreach($edit_fields as $key=>$value)
                                @if($value=='note')
                                    <div class="col-sm-12">
                                        <div class="form-group" id="{{$value}}">
                                            <label> {{$key}} </label>
                                            <textarea name="{{$value}}" class="form-control" cols="80" rows="10" @if(isset($languages)) id="ckeditor1" @endif>{{$row->$value}}</textarea>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-sm-12">
                                        <div class="form-group" id="{{$value}}">
                                            <label for=""> {{$key}}</label><input name="{{$value}}" class="form-control" value="{{$row->$value}}" type="text">
                                            <div class="help-block form-text with-errors form-control-feedback"></div>
                                        </div>
                                    </div>
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
                                            <input name="image" type="file" id="input-file-now-custom-1" class="dropify" data-default-file="{{$row->image}}"/>
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

                        @if(isset($note))
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label> Example textarea</label><textarea class="form-control" rows="3"></textarea>
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
                    <button class="btn btn-primary edit-submit" type="submit"> تعديل</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

