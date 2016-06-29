$(document).ready(function ()
{

  //-----------------------------------------------------------------------
  // 2) Send a http request with AJAX http://api.jquery.com/jQuery.ajax/
  //-----------------------------------------------------------------------

  $(function worker() 
  {
    $.ajax({                                      
      url: 'statistics.php',                  //the script to call to get data          
      data: "",                        //you can insert url argumnets here to pass to api.php
                                       //for example "id=5&parent=6"
      dataType: 'json',                //data format      
      success: function(data)          //on recieve of reply
      {
        var total_reports = data;              //get id
        // console.log("data is : " + data['open_reports']);
        //--------------------------------------------------------------------
        // 3) Update html content
        //--------------------------------------------------------------------
        // $('#total_reports').html(total_reports); //Set output element html
        $('#total_tickets').html(data['total_tickets']);
        $('#open_tickets').html(data['open_tickets']);
        $('#closed_tickets').html(data['closed_tickets']);
        $('#avg_ticket_time').html(data['avg_ticket_time']);
        
        //recommend reading up on jquery selectors they are awesome 
        // http://api.jquery.com/category/selectors/
      },
      complete: function() {
        setTimeout(worker, 2000);
      }
    });
  });
}); 