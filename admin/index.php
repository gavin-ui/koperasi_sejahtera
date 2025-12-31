<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<div class="content">

    <h4 class="fw-bold mb-4">Dashboard</h4>

    <!-- TOP CARDS -->
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card card-dashboard bg-primary p-3">
                <p>Jumlah Pengajuan P2</p>
                <h3>1</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-dashboard" style="background:#008cff">
                <p>Jumlah Pengajuan Q2CP</p>
                <h3>0</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-dashboard" style="background:#ff3b6f">
                <p>Jumlah Pengajuan P3GB</p>
                <h3>0</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-dashboard bg-dark p-3">
                <p>Jumlah Pengajuan P4</p>
                <h3>1</h3>
            </div>
        </div>
    </div>

    <!-- Q2CP + STATISTIK -->
    <div class="row mt-4 g-3">
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h6>Q2CP Approve</h6>
                <h1 class="mt-3">0%</h1>
                <p class="text-muted">Presentase Q2CP yang disetujui.</p>
                <button class="btn btn-success">Proses Q2CP</button>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-3">
                <h6>Statistik Transaksi Penjualan</h6>
                <canvas id="chart"></canvas>
            </div>
        </div>
    </div>

    <!-- BAGIAN BAWAH (SCROLL) -->
    <div class="row mt-5 g-3">
        <div class="col-md-5">
            <div class="card p-4">
                <h6>Varietas Per Kelompok</h6>
                <canvas id="donut"></canvas>

                <div class="mt-3">
                    <p>🌿 Pertanian - 39 varietas</p>
                    <p>🐄 Peternakan - 7 varietas</p>
                    <p>🌴 Perkebunan - 5 varietas</p>
                    <p>🐟 Perikanan - 4 varietas</p>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card p-3">
                <div class="d-flex justify-content-between">
                    <h6>Transaksi Penjualan</h6>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#lihatModal">Lihat Lebih</button>
                </div>

                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Perusahaan</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>MAA-1762264139-8</td>
                            <td>04 November 2025</td>
                            <td>Koperasi Produsen Agro Lumintu</td>
                            <td>Rp7.000</td>
                            <td><span class="badge bg-warning">Menunggu Konfirmasi</span></td>
                        </tr>

                        <tr>
                            <td>MAA-1760970569-8</td>
                            <td>20 Oktober 2025</td>
                            <td>Koperasi Produsen Agro Lumintu</td>
                            <td>Rp22.000</td>
                            <td><span class="badge bg-danger">Dibatalkan</span></td>
                        </tr>

                        <tr>
                            <td>MAA-1756458912-4</td>
                            <td>29 Agustus 2025</td>
                            <td>PT SinarMas</td>
                            <td>Rp27.000</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>


<!-- MODAL LIHAT LEBIH -->
<div class="modal fade" id="lihatModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-3">
        <h5>Detail Transaksi Penjualan</h5>
        <p>Di sini nanti bisa isi tabel lengkap / detail transaksi.</p>
    </div>
  </div>
</div>

<script>
// Chart Line
new Chart(document.getElementById('chart'),{
    type:'line',
    data:{
        labels:["1","2","3","4","5"],
        datasets:[{
            data:[0,1,2,1,0],
            borderWidth:3
        }]
    }
});

// Donut Chart
new Chart(document.getElementById('donut'),{
    type:'doughnut',
    data:{
        labels:["Pertanian","Peternakan","Perkebunan","Perikanan"],
        datasets:[{
            data:[39,7,5,4]
        }]
    }
});
</script>

<?php include "partials/footer.php"; ?>
