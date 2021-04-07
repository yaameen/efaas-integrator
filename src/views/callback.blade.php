<html>
    <body>
        <script>
        window.onload = (function(){
            return function(){
                var url = '{{ route('efaas.callback.internal') .'?' }}' + document.location.hash.substr(1)
                document.location = url;
            };
        })();
        </script>
    </body>
</html>