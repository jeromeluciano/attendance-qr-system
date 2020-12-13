@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            @auth
            @if(auth()->user()->isAdmin())
                <div class="container">
                    <div class="card w-auto">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <div class="mt-1">QrCode Scanner</div>
                                <div class="bg-secondary px-2 py-1 text-white rounded text-monospace" id="statusDisplay">IDLE</div>
                            </div>
                        </div>
                        <div class="card-body mx-auto">
                            <div class="mx-auto">
                                <img style="margin-left: 50px !important;" id="scannerIcon" class="mb-2 ml-2 text-center" src="{{asset('images/camera-scan.gif')}}" width="100" height="100">
                                <div id="reader" class="w-auto"></div>
                            </div>
                            <div class="input-group mb-3 d-none" id="cameraChoicesContainer">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Camera</span>
                                </div>
                                <select class="form-control mb-2" id="cameraChoices">

                                </select>
                            </div>

                            <button id="requestCameraPermission" class="btn btn-success ">Request Camera Permission</button>
                            <button id="startScanning" class="btn btn-primary d-none">Start Scanning</button>
                        </div>
                    </div>
                </div>

                @endif
            @endauth


                <div class="card @auth @if(auth()->user()->isAdmin()) d-none @endif @endauth">
                    <div class="card-header">
                        @if(!Auth::guest() && !auth()->user()->isAdmin())
                            {{ auth()->user()->name }}
                        @else
                            Dashboard
                        @endif
                    </div>

                    <div class="card-body">
                        @if (session('status'))

                        @endif

                        @if(Auth::guest())
                            {{__('You need to logged in.')}}
                        @else
                            @if(!auth()->user()->isAdmin())
                                <div class="" role="">
                                    <img src="{{auth()->user()->qrCodePath()}}" width="300" height="300">
                                </div>
                            @endif
                        @endif
                    </div>
                </div>


        </div>
    </div>
</div>
<script src="{{asset('js/html5-qrcode.min.js')}}"></script>
<script>
    function getCameras() {
        Html5Qrcode.getCameras().then(devices => {
            let html = '';
            for( device of devices ) {
                html += `
                    <option value="${device.id}">${device.label}</option>
                `;
            }
            document.getElementById('cameraChoices').innerHTML = html;
        })
    }

    function sendAttendanceRequest(url) {
        $.ajax({
            url: url,
            method: 'GET',
            success: (result, status, xhr) => {
                if ( status == 'success' ) {
                    if (result.status == 'succesful')
                        toastr.success('Attendance is recorded');
                    else toastr.warning(`${result.name} is already marked`);

                }
            }
        });
    }

    let requestCameraPermission = document.getElementById('requestCameraPermission');
    let cameraChoicesContainer = document.getElementById('cameraChoicesContainer');
    let startScanning = document.getElementById('startScanning');
    let scannerIcon = document.getElementById('scannerIcon');
    let statusDisplay = document.getElementById('statusDisplay');
    let cameraChoices = document.getElementById('cameraChoices');
    let cameraId;
    let toggle = false;
    let lastScan = '';
    requestCameraPermission.addEventListener('click',() => {
        navigator.permissions.query({name: 'camera'})
            .then((permissionObj) => {
                if(permissionObj.state == 'granted') {
                    getCameras();
                    cameraChoicesContainer.classList.remove('d-none');
                    requestCameraPermission.classList.add('d-none');
                    startScanning.classList.remove('d-none');
                }
            })
            .catch((error) => {
                console.log('Got error :', error);
            })
    });
    let counter = 0;
    startScanning.addEventListener('click', () => {
        toggle = !toggle;
        scannerIcon.classList.add('d-none');
        cameraId = cameraChoices.value;
        let html5QrCode = new Html5Qrcode('reader');
        if(toggle) {
            startScanning.textContent = 'Stop Scanning';
            html5QrCode.start(
                cameraId,
                {
                    fps: 10,
                    qrbox: 180,
                },
                qrCodeMessage => {
                    if(qrCodeMessage !== lastScan) {
                        statusDisplay.classList.remove('bg-secondary');
                        statusDisplay.classList.add('bg-success');
                        statusDisplay.textContent = 'SUCCESS';
                        sendAttendanceRequest(qrCodeMessage);

                    }
                    lastScan = qrCodeMessage;
                },
                errorMessage => {
                    statusDisplay.classList.remove('bg-success');
                    statusDisplay.classList.add('bg-secondary');
                    statusDisplay.textContent = 'SCANNING';
                }
            ).catch(err => console.log(err));
        } else {
            html5QrCode.stop().then(ignore => console.log(ignore));
            startScanning.textContent = 'Start Scanning';
        }
    });


</script>
@endsection
