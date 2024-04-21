// $('#sendform').on('submit', function(){

    var opts = {
        url: '/site/receivefeedback',
        type: 'POST',
        data: new FormData($('#sendform')[0]),
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data',
        success: function(res){
            console.log(res);
        },
        error: function(){
            alert('Error!');
        }
    };

    // opts.data.append('voice', '123');

    function checkIsrecordExist() {
            var isrecordfounded = document.getElementById('recordedvoice');
            if (isrecordfounded !== null) {
                var aud = document.getElementById('recordedvoice');
                fetch(aud.src)
                .then(response => response.blob())
                .then(function(e){
                    opts.data.append('voice', e.text());
                    // console.log(e.text())
                })
            }
    }


    $('#submintbutton').on('click', function(){
        // checkIsrecordExist();
        // $( '#sendform' )
        // .submit( function() {
        //     $.ajax(opts);
        //     return false;
        // });
    })
    



  
// });