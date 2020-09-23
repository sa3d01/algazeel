@extends('dashboard.master.base')
@section('title',$title)
@section('style')
    <link rel="stylesheet" href="{{asset('panel/dropify/dist/css/dropify.min.css')}}">
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKhmEeCCFWkzxpDjA7QKjDu4zdLLoqYVw">
    </script>
    <style>
        .image-upload > input {
            visibility:hidden;
            width:0;
            height:0
        }
        .wrapper {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .file-upload {
            height: 200px;
            width: 200px;
            border-radius: 100px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 4px solid #FFFFFF;
            overflow: hidden;
            background-image: linear-gradient(to bottom, #2590EB 50%, #FFFFFF 50%);
            background-size: 100% 200%;
            transition: all 1s;
            color: #FFFFFF;
            font-size: 100px;
        }
        .file-upload input[type='file']{
            height:200px;
            width:200px;
            position:absolute;
            top:0;
            left:0;
            opacity:0;
            cursor:pointer;
        }
    </style>
@endsection
@section('content')
    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-5">
                    <div class="user-profile compact">
                        @include('dashboard.show.first-box')
                    </div>
                    @if(isset($cards))
                        <div class="element-wrapper">
                            @include('dashboard.show.card-box')
                        </div>
                    @endif
                    <div class="element-wrapper">
                        @include('dashboard.show.activity-box')
                    </div>
                </div>
                @if(isset($only_show) && $only_show==true)
                    @include('dashboard.show.show-box')
                @else
                    @include('dashboard.show.edit-box')
                @endif
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>
        $(document).on('click', '.block', function (e) {
            e.preventDefault();
            Swal.fire({
                title: "هل انت متأكد من الحظر ؟",
                text: "تأكد من اجابتك قبل التأكيد!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'نعم , قم بالحظر!',
                cancelButtonText: 'ﻻ , الغى عملية الحظر!',
                closeOnConfirm: false,
                closeOnCancel: false,
                preConfirm: () => {
                    $.ajax({
                        url: $(this).data('href'),
                        type:'GET',
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(() => {
                location.reload(true);
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
    <script type="text/javascript">
        $('#image_preview').html("");
        var files=JSON.parse($("#uploadFile").attr('data-images'));
        for(var i=0;i<files.length;i++)
        {
            $('#image_preview').append("<img style='pointer-events: none;max-height: 100px;max-width: 100px;margin-right: 5px;margin-left: 5px;border-radius: 10px;' src='https://top-auction.com/media/images/sale/"+files[i]+"'>");
        }
        $("#uploadFile").change(function(){
            $('#image_preview').html("");
            var total_file=document.getElementById("uploadFile").files.length;
            for(var i=0;i<total_file;i++)
            {
                $('#image_preview').append("<img style='pointer-events: none;max-height: 100px;max-width: 100px;margin-right: 5px;margin-left: 5px;border-radius: 10px;' src='"+URL.createObjectURL(event.target.files[i])+"'>");
            }
        });
        $("#pdf").change(function(){
            $('#pdf_preview').html("");
            var total_file=document.getElementById("pdf").files.length;
            for(var i=0;i<total_file;i++)
            {
                $('#pdf_preview').append("" +
                    "<iframe src='"+URL.createObjectURL(event.target.files[i])+"' style='width:100%; height:500px;'></iframe>");
            }
        });
    </script>
    <script type="text/javascript">
        var lat_str = $("#map").attr("data-lat");
        var lat_arr = lat_str.split(",");
        var long_str = $("#map").attr("data-long");
        var long_arr = long_str.split(",");
        var uluru = {lat:parseFloat(lat_arr[0]), lng: parseFloat(long_arr[0])};
        var locations=[];
        for (i=0;i<lat_arr.length;i++){
            locations[i]=['hi', lat_arr[i], long_arr[i], i+1];
        }
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 16,
            center: new google.maps.LatLng(uluru),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var infowindow = new google.maps.InfoWindow();
        var marker, i;
        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(parseFloat(locations[i][1]), parseFloat(locations[i][2])),
                map: map
            });
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent('');
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }
    </script>

@endsection
