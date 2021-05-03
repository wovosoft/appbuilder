<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BKB In-house Software Management</title>
    <link rel="stylesheet" href="{{mix("css/admin.css")}}"/>
    <script>
        function baseUrl() {
            return "{{url('/')}}";
        }
    </script>
    <script>
        function s(key) {
            let the_s = @json($settings);
            if (key) {
                return the_s[key];
            }
            return the_s;
        }
    </script>
    <script defer src="{{mix("js/admin.js")}}"></script>
</head>
<body>
<div id="app"></div>
<form style="display: none;" action="{{route('logout')}}" id="logout_form" method="post">
    @csrf
</form>
</body>
</html>
