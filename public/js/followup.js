
$( document ).ready(function() {
  console.log( "ready!" );
  var chart
  let data

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  if (typeof chart == 'undefined' ){
    $("#facility_title").html("Please click a health facility to see the results")
  }

  $('#facilities li').hover(function() {
    $(this).css('cursor','pointer');
  });

  $('#facilities').on('click', 'li', function() {
    let list_id = $(this).attr('id')
    let count = 0
    makeDrawing(list_id,count)
    count =count + 1
  });

  function makeDrawing(list_id,count){
    console.log("fetching the data")

    $.ajax({
      type: "GET",
      url: "followUp/show/"+String(list_id),
    })
    .done(function( result ) {
        data=result.data
        let facility = document.getElementById('facility').getContext('2d')
        if (typeof chart != 'undefined' ){
          chart.destroy()
        }
        chart = new Chart (facility,{
            type:'bar',
            data:{
              labels:["In redcap", "Not in Redcap"],
              datasets:[{
                label:'Medical Cases',
                data:[
                  data.redcap_sent,
                  data.redcap_unsent,
                  // data.fake_cases
                ],
                backgroundColor: ['#d41d0d','#3489f7']
              }]
            },
            options:{ responsive: true,
              maintainAspectRatio: false
            }
          })
          $("#sent").html(`<h2> Cases sent To Redcap Are : ${data.redcap_sent} </h2>`)
          $("#not_sent").html(`<h2> Cases Not sent To Redcap Are : ${data.redcap_unsent} </h2>`)
          chart.canvas.parentNode.style.height = '228px';
          chart.canvas.parentNode.style.width = '100px';
          $("#facility_title").hide()
    });

  }



});
