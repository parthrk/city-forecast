<!DOCTYPE html>
<html>
<head>
    <title>Weather Forecast</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
</head>
<body>
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
    <div class="container">
        <h1>Weather Forecast</h1>
        <div class="alert alert-success" id="alert-success" style="display: none;" role="alert">
            Forecast data for the city has been added successfully.
        </div>
        <div class="alert alert-danger" id="alert-error" style="display: none;" role="alert">
            Invalid city name!
        </div>
        <form id='add-city-form' action="{{ route('forecasts.add-city') }}" method="POST" >
            @csrf 
            <div class="form-group row mb-3">
                <div class="col-3">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter city name">
                </div>
                <div class="col-4">
                    <button type="button" class="btn btn-primary" onclick="addCity()">Add or refresh forecast data for the city</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>City</th>
                    <th>Forecast Date</th>
                    <th>Weather</th>
                    <th>Weather Description</th>
                    <th>Temperature</th>
                    <th>Humidity</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</body>
     
<script type="text/javascript">
    $(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('forecasts.index') }}",
            columns: [
                {data: 'city', name: 'city.name'},
                {data: 'forecast_datetime', name: 'forecast_datetime'},
                {data: 'weather_main', name: 'weather_main'},
                {data: 'weather_description', name: 'weather_description'},
                {data: 'temperature', name: 'temperature'},
                {data: 'humidity', name: 'humidity'},
            ],
        });
    });

    function addCity() {
        $("#overlay").fadeIn(300);
        var form = $('#add-city-form');
        $.ajax({
            url : form.attr('action'),
            type : form.attr('method'),
            data : form.serialize(),
            success : function(response) {
                $("#overlay").fadeOut(300);
                $('#name').val('');
                $('#alert-success').show();
                setTimeout(function () {
                    $('#alert-success').hide();
                }, 3000);
            },
            error : function(response,error)
            {
                $("#overlay").fadeOut(300);
                if( response.status === 422 ) {
                    var errors = response.responseJSON.errors;
                    $.each(errors, function (key, val) {
                        $('#'+key).parent().append('<span class="invalid-feedback" role="alert"><strong>' + val[0] + '</strong></span>').closest("div.form-group").addClass('has-error');
                        $('#'+key).addClass('is-invalid');
                    });
                } else {
                    $('#alert-error').show();
                    setTimeout(function () {
                        $('#alert-error').hide();
                    }, 3000);
                }
            }
        });
    }
</script>
</html>