@props([
    'var' => 'image',
    'col' => 12,
    'canvasWidth' => 640,
    'canvasHeight' => 480,
    'videoWidth' => 640,
    'videoHeight' => 480,
])

<div x-data="cameraComponent()" x-init="init()">
    <h6>Take a Photo with Your Camera</h6>
    <video id="video" width="{{ $videoWidth }}" height="{{ $videoHeight }}" autoplay></video>
    <button id="snap" @click="capturePhoto">Capture Photo</button>
    <canvas id="canvas" width="{{ $canvasWidth }}" height="{{ $canvasHeight }}" style="display:none;"></canvas>
    <input type="hidden" x-model="imageData">

    @push('scripts')
        <script>
            snapButton.addEventListener('click', () => {
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
            });

            function cameraComponent() {
                return {
                    imageData: @entangle($var),
                    // Trigger photo capture
                    init() {
                        const video = document.getElementById('video');
                        const canvas = document.getElementById('canvas');
                        const context = canvas.getContext('2d');

                        navigator.mediaDevices.getUserMedia({
                                video: true
                            })
                            .then((stream) => {
                                video.srcObject = stream;
                                video.play();
                            })
                            .catch((err) => {
                                console.error(`Error accessing the camera: ${err}`);
                            });
                    },
                    capturePhoto() {
                        const video = document.getElementById('video');
                        const canvas = document.getElementById('canvas');
                        const context = canvas.getContext('2d');
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        this.imageData = canvas.toDataURL('image/png');
                    }
                };
            }
        </script>
    @endpush
</div>
