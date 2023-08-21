<!DOCTYPE html>
<html>
    <head>
        <title>Primobox Test</title>
        
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    </head>
    <body>
    
        <div class="container">
            <br />
            <h1 class="text-center text-primary"><u>Primobox Test</u></h1>
            <br />

            <div id="calendar"></div>
        </div>
    
        <script>
            
            $(document).ready(function () {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var calendar = $('#calendar').fullCalendar({
                    header:{
                        left:'prev,next today',
                        center:'title',
                        right:'month,agendaWeek,agendaDay'
                    },
                    selectable:true,
                    select: function(start, end, jsEvent, view) {
                        var startDate = moment(start).format();
                        var endDate = moment(end).format();

                        // minus one day
                        var newEndDate = new Date(endDate);
                        newEndDate.setDate(newEndDate.getDate() - 1);
                        var formattednewEndDate = newEndDate.toISOString().split('T')[0];

                        $.ajax({
                            url: '/api/split-vacation', 
                            method: 'POST',
                            data: {
                                token: "PrimoBoxToken",
                                start_date : startDate,
                                end_date : formattednewEndDate
                            },
                            success: (result) => {
                                if (result.code != 200) {
                                    alert(result.message);
                                } else {

                                    var getresults = "";
                                    var dataArray = result.data;
                                    $.each(dataArray, function(index, array) { // This each iterates over the arrays.
                                        getresults += array[0] + "Jours en " + array[1] + " ";

                                    });                                    
                                    alert(getresults);
                                }
                            },
                            error: (error) => {
                                alert(error);
                            }
                        });
                    }
                });
            });
        </script>
    
    </body>
</html>