var mediaStream;
let visID = document.getElementById("visID");
let docInp = document.getElementById("docInp");

var visFaceOK = true;

visID.onchange = evt => {
    const idPreview = document.getElementById("IDPrev");
    const [file] = visID.files
    if (file) {
        idPreview.style.display = "block";
        idPreview.src = URL.createObjectURL(file)
    }
}

docInp.onchange = evt => {
    const idPreview = document.getElementById("docPrev");
    const [file] = docInp.files
    if (file) {
        idPreview.style.display = "block";
        idPreview.src = URL.createObjectURL(file)
    }
}

function initCamera() {
    var video = document.querySelector("#videoElement");
    const screenshotButton = document.querySelector("#screenshot-button");
    const retryButton = document.querySelector("#retry-button");
    const img = document.querySelector("#faceCanvas img");
    const canvas = document.querySelector("#canvas");
    const faceInput = document.querySelector("#visInpFace");

    if (navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({
            video: true
        })
            .then(function (stream) {
                mediaStream = stream.getTracks();
                video.srcObject = stream;

                screenshotButton.onclick = video.onclick = function () {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext("2d").drawImage(video, 0, 0);
                    // Other browsers will fall back to image/png
                    img.src = canvas.toDataURL();
                    faceInput.value = canvas.toDataURL();

                    document.getElementById("faceVideo").style.display = "none";
                    document.getElementById("faceCanvas").style.display = "block";
                    document.getElementById("camNext").style.display = "inline";

                    mediaStream.forEach(track => track.stop());
                };

                retryButton.onclick = function () {
                    document.getElementById("faceVideo").style.display = "block";
                    document.getElementById("faceCanvas").style.display = "none";
                    document.getElementById("camNext").style.display = "none";
                    visFaceOK = false;
                    faceInput.value = "";

                    initCamera();
                }

            })
            .catch(function (err0r) {
                console.log("Something went wrong!");
            });
    }
}

function select(radio) {
    var vist = document.getElementById('typeVisitor');
    var est = document.getElementById('typeEst');
    var next = document.getElementById('s1Next');

    if (radio == "typeVisitor") {
        vist.style.boxShadow = "0px 0px 12px 1px rgba(0, 0, 0, 0.75)";
        vist.style.border = "2px solid #0C112D";
        est.style.boxShadow = "0px 0px 3px 0px rgba(0, 0, 0, 0.75)";
        est.style.border = "none";
        next.setAttribute('name', 'visInfo')
    } else {
        est.style.boxShadow = "0px 0px 12px 1px rgba(0, 0, 0, 0.75)";
        est.style.border = "2px solid #0C112D";
        vist.style.boxShadow = "0px 0px 3px 0px rgba(0, 0, 0, 0.75)";
        vist.style.border = "none";
        next.setAttribute('name', 'estInfo')
    }
}

function changeForm(evt, form, currForm) {
    console.log("TO: " + form);
    console.log("FROM: " + currForm);
    // Check which form to go to [Visitor/Establishment]
    if (form == "step1") {
        var form = document.getElementById("s1Next").name;
        var currForm = "userType";
        if (form == "") {
            return;
        }
    }

    // Check if all form data is filled before going to next form 
    else if (currForm != '') {
        if (checkData(currForm)) {
            alert("Please input all required data!");
            return;
        }
    }

    //From VS2->3
    if (form == 'visFace' && currForm == 'visInfo') {
        console.log("initCam");
        initCamera();
    }
    // From VS3->2
    if (form == 'visInfo' && currForm == '') {
        // stop cam
        mediaStream.forEach(track => track.stop());
    }

    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(form).style.display = "block";
    evt.currentTarget.className += " active";
}

function checkData(formID) {
    let allAreFilled = true;
    document.getElementById(formID).querySelectorAll("[required]").forEach(function (i) {
        if (!allAreFilled) return;
        if (!i.value) allAreFilled = false;
        if (i.type === "radio") {
            let radioValueCheck = false;
            document.getElementById(formID).querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                if (r.checked) radioValueCheck = true;
            })
            allAreFilled = radioValueCheck;
        }
    })
    return (!allAreFilled);
}

function checkPassword(type) {
    var pass = document.getElementById(type + "Pass").value;
    var confPass = document.getElementById(type + "CPass").value;
    if (pass == confPass && pass != "") {
        document.getElementById(type + "Submit").disabled = false;
    } else
        document.getElementById(type + "Submit").disabled = true;
}

function verifyFace() {
    if (visFaceOK) {
        changeForm(event, 'visId', 'visFace');
    } else {
        $('#loading').modal('show');
        $.ajax({
            type: "POST",
            url: "../functions/recogFace.php",
            data: {
                "img": $('#visInpFace').val(),
            },
            // success: function(data) {},
            // error: function(data) {}
        }).done(function (data) {
            console.log(data);
            $('#loading').modal('hide');
            $('#feedback').modal('show');
            $('#feedback-container').html(data);
            const jsonData = JSON.parse(data);
            console.log(jsonData);
            if (data.includes('gallery name not found')) {
                changeForm(event, 'visId', 'visFace');
            } else if (jsonData.images.at(0).transaction.confidence * 100 >= 75) {
                if (confirm('A match has been found with a confidence level of 75% and above. Do you still wish to proceed with the registration?\n\nPlease be aware that registering multiple accounts is against the system\'s terms of service.')) {
                    changeForm(event, 'visId', 'visFace');
                }
            } else {
                changeForm(event, 'visId', 'visFace');
            }
        });
    }
}
