<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
   
    <link rel="stylesheet" href="<?php url('assets/css/HoldOn.min.css') ?>">
    <title>Contacts!</title>
  </head>
  <body>
    <div class="container" style= "margin-top:60px"> 
        <div class="row" id = "contactContainer">
                      
        </div>
        <div class="offset-1" style="margin-top:15px">        
            <ul class="pagination" id = "pagination-links">
              
            </ul>
        </div>
        
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="<?php url('assets/js/HoldOn.min.js') ?>"></script>
    <script>
   
        $(document).ready(function(){
            var url = "contacts"
            paginate('contacts');
        });

        var holdOnOptions = {
            theme:"sk-cube-grid",
            message:'Loading Contacts...',
            backgroundColor:'rgba(0,0,0,0.5)',
            textColor:"white"
        };
        function paginate(request_uri){
            $.ajax({
                type: "GET",
                url: request_uri,
                beforeSend:function(){ HoldOn.open(holdOnOptions); },               
                success: function(response){
                    setTimeout(function(){
                        response = JSON.parse(response);
                        $('#pagination-links').html(response.pagination_links);
                        console.log(response.results);
                        contactsHtml = generateContactHtml(response.results);     
                        $('#contactContainer').html(contactsHtml);            
                        HoldOn.close();
                    }, 500)
                },
                error: function(jq,status,message) {
                    alert('A jQuery error has occurred. Status: ' + status + ' - Message: ' + message);
                }
            })};

            function generateContactHtml(contactsArray){
                var html = '';
                    for(var i = 0; i < contactsArray.length; i++) {
                        
                        var obj = contactsArray[i];
                        if(obj.note !== null) { $note = obj.note.substring(1, 100) }else{ $note =obj.note }
                        html += '<div class="col-sm" style ="margin-top:10px">'+
                        '<div class="card" style="width: 18rem;">'+
                        '<img class="card-img-top" src="'+obj.image+'" alt="Card image cap">'+
                        '<div class="card-body">'+
                                    '<h5 class="card-title">'+obj.first_name+' '+obj.last_name+'</h5>'+
                                        '<p class="card-text">'+$note+'</p>'+
                         '</div>'+
                             '</div>'+
                                '</div>';                       
                    }
                return  html;
            }             
    </script>
  </body>
</html>