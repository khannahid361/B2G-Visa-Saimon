
<script src="js/app.js" type="text/javascript"></script>
<script src="js/perfect-scrollbar.min.js"></script>
<script src="js/config.js" type="text/javascript"></script>
<script src="js/scripts.bundle.js" type="text/javascript"></script>
<script src="js/custom.js" type="text/javascript"></script>

<script src="{{asset('js/config.js')}}" type="text/javascript"></script>
{{--<script src="{{asset('css/global/plugins.bundle.js')}}" type="text/javascript"></script>--}}

<script src="{{asset('js/scripts.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('js/custom.js')}}" type="text/javascript"></script>
<script>
    var _token = "{{ csrf_token() }}";
    var $window = $(window);

    // :: Preloader Active Code
    $window.on('load', function () {
        $('#preloader').fadeOut('slow', function () {
            $(this).remove();
        });
    });
    $(document).ready(function(){
        <?php
        if (session('status')){
        ?>
        notification("{{session('status')}}","{{session('message')}}");
        <?php
        }
        ?>
        <?php
        if (session('success')){
        ?>
        notification("success","{{session('success')}}");
        <?php
        }
        ?>
        <?php
        if (session('error')){
        ?>
        notification("error","{{session('message')}}");
        <?php
        }
        ?>
    });
</script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    (function (window, document) {
        var loader = function () {
            var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
            script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7);
            tag.parentNode.insertBefore(script, tag);
        };

        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document);
</script>
{{--<script>--}}

{{--    // Enable pusher logging - don't include this in production--}}
{{--    Pusher.logToConsole = true;--}}

{{--    var pusher = new Pusher('3eb86b1d629138656fea', {--}}
{{--        cluster: 'ap2'--}}
{{--    });--}}

{{--    var channel = pusher.subscribe('symon_visa_management');--}}
{{--    channel.bind('ticketNotification', function(data) {--}}
{{--        alert(JSON.stringify(data));--}}
{{--    });--}}
{{--</script>--}}
@stack('scripts') <!-- Load Scripts Dynamically -->
