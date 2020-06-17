@extends('layouts.clock')

    @section('content')
    
    <div class="container-fluid">
        <div class="fixedcenter">
            <div class="clockwrapper">
                <div class="clockinout">
                    <button class="btnclock timein active" data-type="timein">{{ __("Time In") }}</button>
                    <button class="btnclock timeout" data-type="timeout">{{ __("Time Out") }}</button>
                </div>
            </div>
            <div class="clockwrapper">
                <div class="timeclock">
                    <span id="show_day" class="clock-text"></span>
                    <span id="show_time" class="clock-time"></span>
                    <span id="show_date" class="clock-day"></span>
                </div>
            </div>

            <div class="clockwrapper">
                <div class="userinput">
                    <form action="" method="get" accept-charset="utf-8" class="ui form">
                        @isset($cc)
                            @if($cc == "on") 
                                <div class="inline field comment">
                                    <textarea name="comment" class="uppercase lightblue" rows="1" placeholder="Enter comment" value=""></textarea>
                                </div> 
                            @endif
                        @endisset
                        <div class="inline field">
                            <input @if($rfid == 'on') id="rfid" @endif class="enter_idno uppercase @if($rfid == 'on') mr-0 @endif" name="idno" value="" type="text" placeholder="{{ __("ENTER YOUR ID") }}" required autofocus>

                            @if($rfid !== "on")
                                <button id="btnclockin" type="button" class="ui positive large icon button">{{ __("Confirm") }}</button>
                            @endif
                            <input type="hidden" id="_url" value="{{url('/')}}">
                        </div>
                    </form>
                </div>
            </div>

            <div class="message-after">
                <p> 
                    <span id="greetings">{{ __("Welcome!") }}</span> 
                    <span id="fullname"></span>
                </p>
                <p id="messagewrap">
                    <span id="type"></span>
                    <span id="message"></span> 
                    <span id="time"></span>
                </p>
            </div>
        </div>

    </div>

    @endsection

    @section('scripts')
    <script type="text/javascript">
    // elements day, time, date
    var elTime = document.getElementById('show_time');
    var elDate = document.getElementById('show_date');
    var elDay = document.getElementById('show_day');

    // time function to prevent the 1s delay
    var setTime = function() {
        // initialize clock with timezone
        var time = moment().tz(timezone);

        // set time in html
        @if($tf == 1) 
            elTime.innerHTML= time.format("hh:mm:ss A");
        @else
            elTime.innerHTML= time.format("kk:mm:ss");
        @endif

        // set date in html
        elDate.innerHTML = time.format('MMMM D, YYYY');

        // set day in html
        elDay.innerHTML = time.format('dddd');
    }

    setTime();
    setInterval(setTime, 1000);

    $('.btnclock').click(function(event) {
        var is_comment = $(this).data("type");
        if (is_comment == "timein") {
            $('.comment').slideDown('200').show();
        } else {
            $('.comment').slideUp('200');
        }
        $('input[name="idno"]').focus();
        $('.btnclock').removeClass('active animated fadeIn')
        $(this).toggleClass('active animated fadeIn');
    });

    $("#rfid").on("input", function(){
        var url, type, idno, comment;
        url = $("#_url").val();
        type = $('.btnclock.active').data("type");
        idno = $('input[name="idno"]').val();
        idno.toUpperCase();
        comment = $('textarea[name="comment"]').val();

        setTimeout(() => {
            $(this).val("");
        }, 600);

        $.ajax({ url: url + '/attendance/add', type: 'post', dataType: 'json', data: {idno: idno, type: type, clockin_comment: comment}, headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },

            success: function(response) {
                if(response['error'] != null) 
                {
                    $('.message-after').addClass('notok').hide();
                    $('#type, #fullname').text("").hide();
                    $('#time').html("").hide();
                    $('.message-after').removeClass("ok");
                    $('#message').text(response['error']);
                    $('#fullname').text(response['employee']);
                    $('.message-after').slideToggle().slideDown('400');
                } else {
                    function type(clocktype) {
                        if (clocktype == "timein") {
                            return "{{ __('Time In at') }}";
                        } else {
                            return "{{ __('Time Out at') }}";
                        }
                    }
                    $('.message-after').addClass('ok').hide();
                    $('.message-after').removeClass("notok");
                    $('#type, #fullname, #message').text("").show();
                    $('#time').html("").show();
                    $('#type').text(type(response['type']));
                    $('#fullname').text(response['firstname'] + ' ' + response['lastname']);
                    $('#time').html('<span id=clocktime>' + response['time'] + '</span>' + '.' + '<span id=clockstatus> {{ __("Success!") }}</span>');
                    $('.message-after').slideToggle().slideDown('400');
                }
            }
        })
    });

    $('#btnclockin').click(function(event) {
        var url, type, idno, comment;
        url = $("#_url").val();
        type = $('.btnclock.active').data("type");
        idno = $('input[name="idno"]').val();
        idno.toUpperCase();
        comment = $('textarea[name="comment"]').val();

        $.ajax({
            url: url + '/attendance/add',type: 'post',dataType: 'json',data: {idno: idno, type: type, clockin_comment: comment},headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },

            success: function(response) {
                if(response['error'] != null) 
                {
                    $('.message-after').addClass('notok').hide();
                    $('#type, #fullname').text("").hide();
                    $('#time').html("").hide();
                    $('.message-after').removeClass("ok");
                    $('#message').text(response['error']);
                    $('#fullname').text(response['employee']);
                    $('.message-after').slideToggle().slideDown('400');
                } else {
                    function type(clocktype) {
                        if (clocktype == "timein") {
                            return "{{ __('Time In at') }}";
                        } else {
                            return "{{ __('Time Out at') }}";
                        }
                    }
                    $('.message-after').addClass('ok').hide();
                    $('.message-after').removeClass("notok");
                    $('#type, #fullname, #message').text("").show();
                    $('#time').html("").show();
                    $('#type').text(type(response['type']));
                    $('#fullname').text(response['firstname'] + ' ' + response['lastname']);
                    $('#time').html('<span id=clocktime>' + response['time'] + '</span>' + '.' + '<span id=clockstatus> {{ __("Success!") }}</span>');
                    $('.message-after').slideToggle().slideDown('400');
                }
            }
        })
    });
    </script> 

    @endsection