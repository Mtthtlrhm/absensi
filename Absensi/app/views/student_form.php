<div class="content-header">
    <h1>Daftarkan Siswa Baru</h1>
    <p>Posisikan wajah siswa di depan kamera untuk memulai pemindaian.</p>
</div>

<div class="card"> 
    
    <div style="text-align: center;">
        
        <div id="camera-container" style="position: relative; width: 640px; height: 480px; border: 2px solid #ccc; margin: auto; background-color: #eee;">
            <video id="webcam" autoplay muted playsinline style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></video>
            <canvas id="overlay" style="position: absolute; top: 0; left: 0;"></canvas>
        </div>
        
        <div class="camera-controls" style="display: flex; gap: 10px; justify-content: center; margin-top: 10px;">
            <button id="toggleCameraBtn" type="button" class="button">Hentikan Kamera</button> 
        </div>
        <h2 id="instructions" style="font-size: 1.5em; font-weight: bold; color: #333; margin-top: 20px; height: 50px;">Memuat Model AI, mohon tunggu...</h2>
    </div>

    <div id="biodata-form" style="display: none; margin-top: 2em;">
        <h2>✅ Pemindaian Selesai!</h2>
        <p>Silakan lengkapi biodata siswa di bawah ini.</p>
        <form action="?action=add_student" method="post">
            <div class="form-group"><label for="name">Nama Lengkap:</label><input type="text" id="name" name="name" required></div>
            <div class="form-group"><label for="nisn">NISN:</label><input type="text" id="nisn" name="nisn" required></div>
            <div class="form-group">
                <label for="class_id">Pilih Kelas:</label>
                <select id="class_id" name="class_id" required>
                    <option value="" disabled selected>-- Memuat kelas... --</option>
                </select>
            </div>
            <div class="form-group"><label for="gender">Jenis Kelamin:</label><select id="gender" name="gender" required><option value="male">Laki-laki</option><option value="female">Perempuan</option></select></div>
            <input type="hidden" name="photo_front" id="photo_front">
            <button type="submit" class="button">Daftarkan Siswa</button>
        </form>
    </div>
</div>

<script>
    const video = document.getElementById('webcam'); // Sekarang ini akan berhasil
    const instructions = document.getElementById('instructions');
    const canvas = document.getElementById('overlay');
    const cameraContainer = document.getElementById('camera-container');
    const biodataForm = document.getElementById('biodata-form');
    const toggleCameraBtn = document.getElementById('toggleCameraBtn');

    // Path sudah benar (tanpa / di depan)
    const MODEL_URL = 'Absensi/models';
    
    let currentStream;
    let scanInterval;
    let captureTimeout;
    let isCaptureDone = false; 

    async function loadModels() {
        try {
            await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);
            instructions.textContent = "Model AI berhasil dimuat.";
        } catch (err) {
            console.error("Kesalahan saat memuat model:", err);
            instructions.textContent = "Error: Gagal memuat model AI.";
        }
    }

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
            video.srcObject = stream;
            currentStream = stream;
            toggleCameraBtn.textContent = 'Hentikan Kamera';
        } catch (err) {
            console.error("Kesalahan saat memulai kamera:", err);
            instructions.textContent = "Error: Tidak bisa mengakses kamera.";
        }
    }

    function stopCamera() {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
            video.srcObject = null;
            toggleCameraBtn.textContent = 'Nyalakan Kamera';
            clearInterval(scanInterval);
        }
    }

    function toggleCamera() {
        if (video.srcObject) {
            stopCamera();
        } else {
            startCamera();
        }
    }
    
    function checkPosition(box) {
        // Cek jika video sudah punya dimensi, jika belum, jangan lakukan apa-apa
        if (video.videoWidth === 0) return false; 
        
        const { videoWidth } = video;
        const boxCenter = box.x + box.width / 2;
        const leftBoundary = videoWidth / 3;
        const rightBoundary = (videoWidth * 2) / 3;
        return boxCenter > leftBoundary && boxCenter < rightBoundary;
    }

    async function populateClassesDropdown() {
        try {
            const urlParams = new URLSearchParams(window.location.search);
            const preselectedClassId = urlParams.get('class_id');

            // Pastikan URL fetch ini benar
            const response = await fetch('?action=add_student&fetch_classes=true');
            if (!response.ok) {
                throw new Error('Gagal mengambil data dari server');
            }
            const classes = await response.json();
            
            const classSelect = document.getElementById('class_id');
            classSelect.innerHTML = '<option value="" disabled>-- Pilih Kelas --</option>'; 

            if (classes.length > 0) {
                classes.forEach(cls => {
                    const option = document.createElement('option');
                    option.value = cls.id;
                    option.textContent = cls.name;
                    if (preselectedClassId && cls.id == preselectedClassId) {
                        option.selected = true;
                    }
                    classSelect.appendChild(option);
                });
                
                // Jika hanya ada satu kelas, atau sudah pre-selected,
                // set default selection jika belum ada
                if (preselectedClassId) {
                     classSelect.value = preselectedClassId;
                } else if (classes.length === 1) {
                     classSelect.value = classes[0].id;
                } else {
                     classSelect.selectedIndex = 0; // "-- Pilih Kelas --"
                }
                
            } else {
                classSelect.innerHTML = '<option value="" disabled>Belum ada kelas.</option>';
            }
        } catch (error) {
            console.error('Gagal mengambil data kelas:', error);
            const classSelect = document.getElementById('class_id');
            classSelect.innerHTML = '<option value="" disabled>Error memuat kelas.</option>';
        }
    }

    function onPlay() {
        // Cek jika model sudah dimuat
        if (video.paused || video.ended || !faceapi.nets.ssdMobilenetv1.params) {
            return setTimeout(() => onPlay(), 100);
        }

        const displaySize = { width: video.videoWidth, height: video.videoHeight }; 
        faceapi.matchDimensions(canvas, displaySize);
        instructions.textContent = "Posisikan wajah di TENGAH...";

        scanInterval = setInterval(async () => {
            if (isCaptureDone || !video.srcObject) return;

            // --- PERBAIKAN: Turunkan 'minConfidence' dari 0.5 menjadi 0.3 ---
            const options = new faceapi.SsdMobilenetv1Options({ minConfidence: 0.3 });
            const detections = await faceapi.detectAllFaces(video, options);
            
            // --- DEBUGGING: Tampilkan apa yang dideteksi AI di console ---
            console.log("Wajah terdeteksi:", detections.length); // Kita perlu lihat ini
            
            const resizedDetections = faceapi.resizeResults(detections, displaySize);
            
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
            faceapi.draw.drawDetections(canvas, resizedDetections); // Gambar semua yg terdeteksi

            const isFaceDetected = detections.length === 1; // Hanya jika 1 wajah
            if (!isFaceDetected) {
                if (detections.length > 1) {
                    instructions.textContent = "Terdeteksi lebih dari 1 wajah!";
                } else {
                    // Ini akan tampil jika detections.length adalah 0
                    instructions.textContent = "Tidak ada wajah terdeteksi...";
                }
                clearTimeout(captureTimeout);
                captureTimeout = null;
                return;
            }
            
            // Cek jika box ada (untuk menghindari error 'TypeError' sebelumnya)
            if (!detections[0] || !detections[0].box) {
                clearTimeout(captureTimeout);
                captureTimeout = null;
                return; 
            }

            // Gunakan fungsi checkPosition yang sudah kita perbaiki (zona 50%)
            const isPositionCorrect = checkPosition(detections[0].box);

            if (isPositionCorrect) {
                instructions.textContent = "Tahan Posisi!";
                faceapi.draw.drawDetections(canvas, resizedDetections, { boxColor: 'lightgreen' });
                
                if (!captureTimeout) {
                    captureTimeout = setTimeout(captureImageAndFinish, 1500); 
                }
            } else {
                instructions.textContent = "Posisikan wajah di TENGAH...";
                clearTimeout(captureTimeout);
                captureTimeout = null;
            }
        }, 300); // Interval 300ms agar lebih stabil
    }

    // PERBAIKAN #2: Ini adalah fungsi yang bersih, tidak bersarang
    function captureImageAndFinish() {
        if (isCaptureDone) return;
        isCaptureDone = true;

        // 1. Ambil snapshot gambar
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = video.videoWidth;
        tempCanvas.height = video.videoHeight;
        tempCanvas.getContext('2d').drawImage(video, 0, 0);
        const dataUrl = tempCanvas.toDataURL('image/png');
        
        console.log(`Gambar berhasil diambil!`);

        // 2. Masukkan data gambar ke input hidden yang SUDAH ADA
        document.getElementById('photo_front').value = dataUrl;
        
        // 3. Hentikan semua proses kamera
        clearInterval(scanInterval);
        stopCamera();

        // 4. Atur tampilan UI
        cameraContainer.style.display = 'none';
        document.querySelector('.camera-controls').style.display = 'none';
        instructions.style.display = 'none';
        biodataForm.style.display = 'block'; // Tampilkan form yang sudah ada

        // 5. Isi dropdown kelas
        populateClassesDropdown();
    }

    // --- EVENT LISTENERS (PEMANGGIL FUNGSI) ---
    toggleCameraBtn.addEventListener('click', toggleCamera);
    video.addEventListener('play', onPlay); // Memindahkan 'onplay' dari HTML

    // Fungsi utama untuk menjalankan aplikasi
    async function main() {
        await loadModels(); // Tunggu model dimuat
        
        // Cek jika model gagal dimuat, jangan jalankan kamera
        if (instructions.textContent.includes("Error:")) {
            return; 
        }
        
        await startCamera(); // Baru jalankan kamera
    }
    
    // Mulai aplikasi saat halaman selesai dimuat
    document.addEventListener("DOMContentLoaded", () => {
        main();
    });
</script>