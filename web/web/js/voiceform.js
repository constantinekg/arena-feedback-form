var sendurl = '/site/receivefeedback';

var voiceBlob = new Blob([], {
    type: 'audio/wav; codecs=vorbis'
});
var navmediaDevices = navigator.mediaDevices;
var stream;
var maxduration = 180;
var bitrate = 16000;

function destroyVoice(stream) {
    stream.getTracks().forEach(function(track) {
        track.stop();
    });
}

$("#collapseVoice").on('shown.bs.collapse', function(){
    document.getElementById('audiocolapse').innerText = 'Убрать аудио сообщение';
    document.querySelector('#stop').disabled = true;
        document.querySelector('#start').addEventListener('click', function(){
            stream = navmediaDevices.getUserMedia({ audio: true})
            .then(stream => {
                const mediaRecorder = new MediaRecorder(stream, {audioBitsPerSecond : bitrate});
                var chunks = [];
                var countDownTimer;
                document.querySelector('#start').disabled = true;
                document.querySelector('#stop').disabled = false;
                document.getElementById('recordstatus').innerHTML = `<button class="btn btn-danger btn-block" type="button" disabled>
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Идёт запись...
              </button>`;
              function countdownstop() {
                countDownTimer = setInterval(() => {
                    maxduration-=1;
                    document.getElementById('recordstatus').innerHTML = `<button class="btn btn-danger btn-block" type="button" disabled>
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Идёт запись, запись окончится сама по себе через ` + maxduration + ` секунд. Вы можете получить результат её записи, нажав на кнопку "Стоп" прямо сейчас.
                  </button>`;
                    if (maxduration<=0) {
                        clearInterval(countDownTimer);
                        mediaRecorder.stop();
                    }
                    console.log(maxduration);
                    }, 1000);
            }
              countdownstop();
            chunks = [];
            if (mediaRecorder.state === 'inactive' || mediaRecorder.state === 'paused') {
                mediaRecorder.start();
                console.log(mediaRecorder.state);
            }
            else if (mediaRecorder.state === 'recording') {
                console.log("захват звука в процессе...");
            }
            
        mediaRecorder.addEventListener("dataavailable",function(event) {
            chunks.push(event.data);
        });

        document.querySelector('#stop').addEventListener('click', function(){
            mediaRecorder.stop();
        });

        mediaRecorder.addEventListener("stop", function() {
            voiceBlob = new Blob(chunks, {
                type: 'audio/wav; codecs=vorbis',
            });
            console.log(voiceBlob.size);
            destroyVoice(stream);
            document.querySelector('#start').disabled = false;
            document.querySelector('#stop').disabled = true;
            clearInterval(countDownTimer);
            const audioURL = window.URL.createObjectURL(voiceBlob);
            document.getElementById('recordstatus').innerHTML = '<audio controls style="width: 100%; height: 100%;"><source src="' + audioURL + '" type="audio/wav">Your browser does not support the audio element.</audio>';
        });

    });
});
    
});


$("#collapseVoice").on('hidden.bs.collapse', function(){
    document.getElementById('audiocolapse').innerText = 'Записать аудио сообщение';
    if (typeof voiceBlob !== 'undefined') {
        console.log(voiceBlob.size);
        voiceBlob = ([], {
            type: 'audio/wav; codecs=vorbis'
        });
        // console.log(voiceBlob.size);
        // stream.getTracks().forEach(function(track) {
        //     track.stop();
        // });
    }
});


$('#testsend').on('click', function (){
    var fd = new FormData($('#sendform')[0]);
    let dataavailable = 0;
    if (voiceBlob.size > 0) {
        console.log('voice we have'); // Вот тут мы можем голос прислонить к отправляемой форме
        dataavailable = 1;
        fd.append('voice', voiceBlob);
        console.log(voiceBlob.stream());
    }
    else{
        console.log('voice we not have');
        if ($('#body').val().length < 3) {
            // alert('Надо ввести хотя бы какое то сообщение или записать голосовое послание');
            document.getElementById("erm").innerHTML = '<font color="red">Надо ввести хотя бы какое то сообщение или записать голосовое послание</font>';
            $('#erm').slideDown(function() {
                setTimeout(function() {
                    $('#erm').slideUp();
                }, 5000);
            });
            $('#body').focus();
        }
        else{
            dataavailable = 1;
        }
    }
    if (dataavailable === 1) {
        sendVoiceForm(fd);
    }
})

async function sendVoiceForm(form) {
    let promise = await fetch(sendurl, {
        method: 'POST',
        data: form,
        body: form});
    Promise.resolve(await promise.json()).then(function(value){
        let resptext = '';
        for (const [key, val] of Object.entries(value)) {
            // console.log(`${key}: ${val}`);
            if (val == 'Необходимо заполнить «Ваше имя».' 
            || val == 'Необходимо заполнить «Номер телефона».' || val == 'Необходимо заполнить «Тип сообщения».'
            || val == 'Неправильный проверочный код.') {
                resptext += '<font color="red">' + val + "</font><br>";
            }
            else if (val == 'Invalid recaptcha verify response.') {
                resptext += '<font color="red">Неправильно прошла проверка на бота. Пожалуйста обновите страницу или подождите загрузки нового запроса кода проверки.';
            }
          }
        document.getElementById("erm").innerHTML = resptext;
        $('#erm').slideDown(function() {
            setTimeout(function() {
                $('#erm').slideUp();
            }, 5000);
        });
        resptext = '';
        if(value == true) {
                window.location.assign("/thankyou"); 
            }
    });
    return false;
}
