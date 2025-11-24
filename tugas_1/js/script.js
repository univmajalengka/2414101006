// --- 1. DATA MANAGER ---
const defaultData = [
  {
    id: 1,
    nama: "Terasering Panyaweuyan",
    img: "assets/img/panyaweuyan.jpg",
    deskripsi: "Keindahan bukit hijau di Argapura.",
    detailLengkap:
      "Terasering Panyaweuyan menawarkan pemandangan ladang bawang yang menakjubkan di kaki Gunung Ciremai. Waktu terbaik berkunjung adalah pagi hari.",
    harga: "Rp 15.000",
    galeriFoto: "panyaweuyan1.jpg,panyaweuyan2.jpg",
  },
  {
    id: 2,
    nama: "Situ Cipanten",
    img: "assets/img/cipanten.jpg",
    deskripsi: "Danau air jernih dengan ribuan ikan.",
    detailLengkap:
      "Situ Cipanten viral karena airnya yang sangat jernih. Anda bisa berfoto di ayunan atau menaiki perahu transparan.",
    harga: "Rp 10.000",
    galeriFoto: "cipanten1.jpg,cipanten2.jpg",
  },
  {
    id: 3,
    nama: "Curug Cipeuteuy",
    img: "assets/img/curug.jpg", // Pastikan file ini ada atau ganti nama file
    deskripsi: "Air terjun segar di tengah hutan pinus.",
    detailLengkap:
      "Curug Cipeuteuy adalah destinasi wisata air terjun yang asri. Cocok untuk camping dan berenang.",
    harga: "Rp 20.000",
    galeriFoto: "curug1.jpg,curug2.jpg",
  },
];

let pesananSementara = {};

function getWisataData() {
  // PERBAIKAN: Kode 'removeItem' DIHAPUS agar data Admin tidak hilang terus
  let data = localStorage.getItem("wisataMajalengka");
  if (!data) {
    localStorage.setItem("wisataMajalengka", JSON.stringify(defaultData));
    return defaultData;
  }
  return JSON.parse(data);
}

// --- 2. HELPER FUNGSI ---
function createCard(item) {
  return `
        <div class="card">
            <img src="${item.img}" alt="${item.nama}">
            <div class="card-body">
                <h3>${item.nama}</h3>
                <p>${item.deskripsi.substring(0, 80)}...</p>
                <span class="price">${item.harga}</span>
                <a href="detail_wisata.html?id=${
                  item.id
                }" class="btn-pesan">Lihat Detail & Pesan</a>
            </div>
        </div>
    `;
}

// --- 3. RENDER HALAMAN ---
function renderBeranda() {
  const container = document.getElementById("container-featured-wisata");
  if (!container) return;
  const data = getWisataData();
  const featured = data.slice(0, 2); // Ambil 2 Unggulan
  container.innerHTML = featured.map((item) => createCard(item)).join("");
}

function renderHalamanWisata() {
  const container = document.getElementById("container-all-wisata");
  if (!container) return;
  const data = getWisataData();
  container.innerHTML = data.map((item) => createCard(item)).join("");
}

function renderDetailWisata() {
  const container = document.getElementById("detail-container");
  if (!container) return;

  const urlParams = new URLSearchParams(window.location.search);
  const idDicari = urlParams.get("id");
  const data = getWisataData();
  const item = data.find((x) => x.id == idDicari);

  if (!item) {
    container.innerHTML =
      "<h2 style='text-align:center'>Wisata tidak ditemukan!</h2>";
    return;
  }

  container.innerHTML = `
        <div class="card" style="max-width: 900px; margin: 0 auto;">
            <img src="${item.img}" alt="${
    item.nama
  }" style="height: 400px; object-fit: cover;">
            <div class="card-body">
                <h1 style="color:#2c3e50; font-size: 2.2rem; margin-bottom:10px;">${
                  item.nama
                }</h1>
                <p class="price" style="font-size:1.3rem;">Tiket: ${
                  item.harga
                }</p>
                <h3 style="margin-top:20px;">Tentang Wisata Ini:</h3>
                <p style="line-height: 1.8; margin-bottom: 30px;">${
                  item.detailLengkap || item.deskripsi
                }</p>
                <hr style="margin:20px 0; border-top:1px solid #ddd;">
                <h3>Form Pemesanan Tiket</h3>
                <form onsubmit="panggilNota(event, '${
                  item.nama
                }')" style="margin-top:15px; background:#f9f9f9; padding:20px; border-radius:8px;">
                    <label>Nama Pemesan:</label>
                    <input type="text" id="namaPemesan" style="width:100%; padding:10px; margin:5px 0 15px; border:1px solid #ddd;" required>
                    <label>Jumlah Tiket:</label>
                    <input type="number" id="jumlahTiket" min="1" style="width:100%; padding:10px; margin:5px 0 15px; border:1px solid #ddd;" required>
                    <label>Tanggal Berkunjung:</label>
                    <input type="date" id="tglTiket" style="width:100%; padding:10px; margin:5px 0 15px; border:1px solid #ddd;" required>
                    <button type="submit" class="btn-pesan">Pesan via WhatsApp</button>
                </form>
            </div>
        </div>
        <div style="text-align:center; margin-top:20px;">
            <a href="wisata.html" style="color:#2c3e50; text-decoration:none;">&larr; Kembali ke Daftar Wisata</a>
        </div>
    `;

  // Galeri
  if (item.galeriFoto) {
    const galleryWrapper = document.getElementById("gallery-wisata-wrapper");
    const galleryGrid = document.getElementById("detail-gallery-grid");
    galleryWrapper.style.display = "block";
    galleryGrid.innerHTML = "";
    const fotoArray = item.galeriFoto.split(",");
    fotoArray.forEach((foto) => {
      let namaFile = foto.trim();
      if (namaFile) {
        let srcPath = namaFile.includes("assets/img")
          ? namaFile
          : `assets/img/${namaFile}`;
        galleryGrid.innerHTML += `<img src="${srcPath}" alt="Galeri ${item.nama}" style="cursor:pointer;" onclick="window.open(this.src)">`;
      }
    });
  }
}

// --- 4. LOGIKA NOTA & WA ---
function panggilNota(e, namaWisata) {
  e.preventDefault();
  const nama = document.getElementById("namaPemesan").value;
  const jumlah = document.getElementById("jumlahTiket").value;
  const tgl = document.getElementById("tglTiket").value;

  if (jumlah < 1) {
    alert("Mohon maaf Tuan, jumlah tiket minimal harus 1.");
    return;
  }

  const urlParams = new URLSearchParams(window.location.search);
  const idDicari = urlParams.get("id");
  const data = getWisataData();
  const item = data.find((x) => x.id == idDicari);

  let hargaSatuan = parseInt(item.harga.replace(/[^0-9]/g, ""));
  let totalHarga = hargaSatuan * jumlah;
  let totalFormat = "Rp " + totalHarga.toLocaleString("id-ID");

  pesananSementara = {
    nama,
    wisata: namaWisata,
    jumlah,
    tgl,
    total: totalFormat,
  };

  const notaKonten = document.getElementById("nota-konten");
  if (notaKonten) {
    notaKonten.innerHTML = `
            <div class="nota-row"><span>Nama:</span> <strong>${nama}</strong></div>
            <div class="nota-row"><span>Wisata:</span> <span>${namaWisata}</span></div>
            <div class="nota-row"><span>Tanggal:</span> <span>${tgl}</span></div>
            <hr style="border:1px dashed #ccc; margin:5px 0;">
            <div class="nota-row"><span>Harga:</span> <span>${item.harga}</span></div>
            <div class="nota-row"><span>Qty:</span> <span>x ${jumlah}</span></div>
            <div class="nota-row nota-total"><span>TOTAL:</span> <span>${totalFormat}</span></div>
        `;
    document.getElementById("nota-overlay").style.display = "flex";
  } else {
    lanjutKeWA();
  }
}

function lanjutKeWA() {
  const p = pesananSementara;
  const nomorWA = "6285932016451";
  const salamPembuka = "saya mau memesan tiket wisata mohon responnya";
  const detailPesanan = `\n\n--------------------------\n*DETAIL UPETI (NOTA)*\n--------------------------\nNama: ${p.nama}\nWisata: *${p.wisata}*\nTanggal: ${p.tgl}\nJumlah: ${p.jumlah} Orang\n*Total Bayar: ${p.total}*`;
  const finalPesan = encodeURIComponent(salamPembuka + detailPesanan);
  window.open(`https://wa.me/${nomorWA}?text=${finalPesan}`, "_blank");
  tutupNota();
}

function tutupNota() {
  const overlay = document.getElementById("nota-overlay");
  if (overlay) overlay.style.display = "none";
}

// --- 5. INITIALIZATION ---
const menuToggle = document.getElementById("mobile-menu");
const navList = document.getElementById("nav-list");
if (menuToggle) {
  menuToggle.addEventListener("click", () =>
    navList.classList.toggle("active")
  );
}

document.addEventListener("DOMContentLoaded", () => {
  renderBeranda();
  renderHalamanWisata(); // INI SUDAH DIPANGGIL DISINI, JADI DI HTML TIDAK PERLU LAGI
  renderDetailWisata();
});
