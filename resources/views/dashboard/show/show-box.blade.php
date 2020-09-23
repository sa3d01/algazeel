<div class="col-sm-7">
    <div class="element-wrapper">
        <div class="element-box">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif
                @if(isset($edit_fields))
                    <div class="element-info">
                        <div class="element-info-with-icon">
                            <div class="element-info-icon">
                                <div class="os-icon os-icon-wallet-loaded"></div>
                            </div>
                            <div class="element-info-text">
                                <h5 class="element-inner-header">
                                     البيانات
                                </h5>
                                @if(isset($edit_alert))
                                    <div class="element-inner-desc">
                                        {{$edit_alert}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <fieldset class="form-group">
                        <div class="row">
                            @foreach($edit_fields as $key=>$value)
                                @if(isset($languages) && $languages==true)
                                    @foreach($settings->languages as $lang)
                                        @if($value=='note')
                                            <div class="col-sm-12">
                                                <div class="form-group" id="{{$value}}.{{$lang}}">
                                                    <label> {{$key}} </label>
                                                    <textarea disabled name="{{$value}}.{{$lang}}" class="form-control" cols="80" rows="10" id="ckeditor1">
                                                        {{$row->$value[$lang]}}
                                                    </textarea>
                                                </div>
                                            </div>
                                        @elseif(strpos($value, 'price'))
                                            <div class="col-sm-12">
                                                <div class="form-group" id="{{$value}}">
                                                    <label for=""> {{$key}}</label>
                                                    <input disabled name="{{$value}}" class="form-control" value="{{$row->$value}}" type="number" min="1">
                                                    <div class="help-block form-text with-errors form-control-feedback"></div>
                                                </div>
                                            </div>
                                        @elseif($value=='start_date' || $value=='end_date')
                                            <div class="col-sm-12" id="{{$value}}">
                                                <div class="form-group row">
                                                    <label for="{{$value}}" class="col-2 col-form-label">{{$key}}</label>
                                                    <p>{{$row->showTimeStampDate($row->$value)}}</p>
                                                </div>
                                            </div>
                                        @elseif($value=='images')
                                            <div class="col-sm-12" id="{{$value}}">
                                                <div class="form-group row hidden">
                                                    <label for="{{$value}}" class="col-2 col-form-label">{{$key}}</label>
                                                    <input hidden disabled class="upload form-control" id="uploadFile" type="file" data-images="{{$row->imagesArray()}}" accept="image/*" name="images[]" multiple />
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="form-group" id="image_preview"></div>
                                        @else
                                            <div class="col-sm-12">
                                                <div class="form-group" id="{{$value}}.{{$lang}}">
                                                    <label for=""> {{$key}}</label>
                                                    <input disabled name="{{$value}}.{{$lang}}" class="form-control" value="{{$row->$value[$lang]}}" type="text">
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
                                                <textarea disabled name="{{$value}}" class="form-control" cols="80" rows="10">
                                                        {{$row->$value}}
                                                    </textarea>
                                            </div>
                                        </div>
                                    @elseif(strpos($value, 'price'))
                                        <div class="col-sm-12">
                                            <div class="form-group" id="{{$value}}">
                                                <label for=""> {{$key}}</label>
                                                <input disabled name="{{$value}}" value="{{$row->$value}}" class="form-control" type="number" min="1">
                                                <div class="help-block form-text with-errors form-control-feedback"></div>
                                            </div>
                                        </div>
                                    @elseif($value=='start_date' || $value=='end_date')
                                        <div class="col-sm-12" id="{{$value}}">
                                            <div class="form-group row">
                                                <label for="{{$value}}" class="col-2 col-form-label">{{$key}}</label>
                                                <p>{{$row->showTimeStampDate($row->$value)}}</p>
                                            </div>
                                        </div>
                                    @elseif($value=='images')
                                        <div class="col-sm-12" id="{{$value}}">
                                            <div class="form-group row">
                                                <label for="{{$value}}" class="col-2 col-form-label">{{$key}}</label>
                                                <input class="upload form-control" id="uploadFile" type="file" data-images="{{$row->imagesArray()}}" accept="image/*" name="images[]" multiple />
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="form-group" id="image_preview"></div>
                                    @else
                                        <div class="col-sm-12">
                                            <div class="form-group" id="{{$value}}">
                                                <label for=""> {{$key}}</label><input disabled name="{{$value}}" class="form-control" value="{{$row->$value}}" type="text">
                                                <div class="help-block form-text with-errors form-control-feedback"></div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                                @if(isset($select))
                                    @php
                                        $drop_down_rows=\App\DropDown::where(['class'=>$select['class'],'status'=>1])->get();
                                        $marks=\App\DropDown::where(['class'=>'Mark','status'=>1])->get();
                                        $models=\App\DropDown::where(['class'=>'Model','parent_id'=>$marks->first()->id,'status'=>1])->get();
                                        $related_model=substr_replace($select['input_name'], "", -3);
                                        try {
                                            $related_model_id=$row->$related_model->id;
                                            $related_model_val=$row->$related_model->name['ar'];
                                        }catch (Exception $e){
                                            $related_model_id=$row->mark->id;
                                            $related_model_val=$row->mark->name['ar'];
                                        }
                                    @endphp
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for=""> {{$select['name']}} </label>
                                            <select disabled id="{{$select['input_name']}}" name="{{$select['input_name']}}" class="form-control">
                                                <option value="{{$related_model_id}}">
                                                    {{$related_model_val}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" id="marks" hidden>
                                        <div class="form-group">
                                            <label for=""> الماركة </label>
                                            <select disabled id="mark_id" name="mark_id" class="form-control">
                                                <option value="{{$row->model ? $row->model->mark->id : null}}">
                                                    {{$row->model ? $row->model->mark->name['ar'] : ''}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" id="models" hidden>
                                        <div class="form-group">
                                            <label for=""> الموديل </label>
                                            <select disabled id="model_id" name="model_id" class="form-control">
                                                <option value="{{$row->model ? $row->model->id : null}}">
                                                    {{$row->model ? $row->model->name['ar'] : ''}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="color" class="col-sm-12" hidden>
                                        <div class="form-group">
                                            <label for="">اللون</label>
                                            <select disabled id="color" name="color" class="form-control">
                                                @php($selected_color=\App\DropDown::where('name->ar',$row->color)->first())
                                                <option style="background-color:{{$selected_color->more_details['hexa']?? '#FFF'}}" value="{{$row->color}}">
                                                    {{$row->color}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="year" class="col-sm-12" hidden>
                                        <div class="form-group">
                                            <label for="">سنة الصنع</label>
                                            <select id="year" name="year" class="form-control">
                                                <option value="{{$row->year}}">
                                                    {{$row->year}}
                                                </option>
                                                @for($year=1980;$year<=2040;$year++)
                                                    <option value="{{$year}}">
                                                        {{$year}}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($image))
                                    <div class="col-sm-6">
                                        <div class="white-box">
                                            <label for="input-file-now-custom-1">الصورة</label>
                                            <input disabled name="image" type="file" id="input-file-now-custom-1" class="dropify" data-default-file="{{$row->image}}"/>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($pdf) && $row->pdf!=null)
                                    <div class="col-sm-12">
                                        <label for="pdf">تقرير الفحص</label>
                                        <div>
                                            <iframe id="iframe" src="https://top-auction.com/media/files/{{$row->pdf}}" style="width:100%; height:500px;" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                @endif
                                @if(isset($address) && $row->address!=null)
                                    <div class="col-sm-12">
                                        <div class="card-img" style="height: 400px">
                                            <label for="show_sale_map">الموقع</label>
                                            <div id="show_sale_map" data-lat="{{$row->address['lat']}}" data-lng="{{$row->address['lng']}}" class="sale_map"></div>
                                        </div>
                                    </div>
                                @endif
                        </div>
                    </fieldset>
                @endif
        </div>
    </div>
</div>

