<?php
// Filename: pages/demografi.php
// Version: 2.0 - MySQL Integrated

require_once __DIR__ . '/../config/db.php';
$conn = connect_db();

// Dapatkan tarikh data terkini
$latest_date_result = $conn->query("SELECT MAX(tarikh_id) as latest_date FROM fact_demografi");
$latest_date_id = $latest_date_result->fetch_assoc()['latest_date'];
$data_sehingga = $latest_date_id ? date_create_from_format('Ymd', $latest_date_id)->format('Y-m-d') : date('Y-m-d');

// Fungsi untuk menjalankan query agregat
function get_demographic_data($conn, $dim_table, $dim_id, $dim_column, $fact_table, $latest_date_id) {
    $sql = "SELECT d.{$dim_column} AS label, SUM(f.bilangan_pegawai) AS value
            FROM {$fact_table} f
            JOIN {$dim_table} d ON f.{$dim_id} = d.{$dim_id}
            WHERE f.tarikh_id = ?
            GROUP BY d.{$dim_column}
            ORDER BY value DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $latest_date_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Dapatkan data untuk semua carta
$jantinaData = get_demographic_data($conn, 'dim_jantina', 'jantina_id', 'nama_jantina', 'fact_demografi', $latest_date_id);
$statusData = get_demographic_data($conn, 'dim_status_perkahwinan', 'status_id', 'status_perkahwinan', 'fact_demografi', $latest_date_id);
$kaumData = get_demographic_data($conn, 'dim_kaum', 'kaum_id', 'nama_kaum', 'fact_demografi', $latest_date_id);
$agamaData = get_demographic_data($conn, 'dim_agama', 'agama_id', 'nama_agama', 'fact_demografi', $latest_date_id);
$pendidikanData = get_demographic_data($conn, 'dim_tahap_pendidikan', 'pendidikan_id', 'tahap_pendidikan', 'fact_demografi', $latest_date_id);

// Dapatkan data perjawatan untuk carta pai pertama
$perjawatanSql = "SELECT SUM(bil_perjawatan) as perjawatan, SUM(bil_pengisian) as pengisian FROM fact_perjawatan WHERE tarikh_id = ?";
$stmt_perjawatan = $conn->prepare($perjawatanSql);
// Guna tarikh dari fact_demografi sebagai rujukan
$stmt_perjawatan->bind_param("i", $latest_date_id);
$stmt_perjawatan->execute();
$perjawatanResult = $stmt_perjawatan->get_result()->fetch_assoc();
$perjawatanData = [
    ['label' => 'Pengisian', 'value' => (int)$perjawatanResult['pengisian']],
    ['label' => 'Kekosongan', 'value' => (int)$perjawatanResult['perjawatan'] - (int)$perjawatanResult['pengisian']]
];

// Gabungkan semua data untuk dihantar ke frontend
$data = [
    'data_sehingga' => $data_sehingga,
    'perjawatan' => $perjawatanData,
    'jantina' => $jantinaData,
    'status' => $statusData,
    'kaum' => $kaumData,
    'agama' => $agamaData,
    'pendidikan' => $pendidikanData
];

$frontendData = json_encode($data);
$conn->close();
?>

<section id="page-demografi">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-slate-800">Demografi Pengisian Jawatan KPM</h1>
        <div class="flex items-center gap-4 w-full sm:w-auto">
            <div class="relative">
                <label class="text-sm font-medium text-slate-600 absolute -top-3 left-2 bg-slate-100 px-1">Data Sehingga</label>
                <input type="date" id="date-slicer" value="<?php echo htmlspecialchars($data_sehingga); ?>" class="bg-white border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
            </div>
            <div class="relative w-full sm:w-64">
                <input type="text" id="search-box" placeholder="Cari carta..." class="bg-white border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 pl-10">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grid untuk carta-carta -->
    <div id="charts-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Carta akan dijana oleh JavaScript di sini -->
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const backendData = <?php echo $frontendData; ?>;
    const grid = document.getElementById('charts-grid');

    const chartColors = {
        pie: ['#4f46e5', '#ef4444', '#f59e0b', '#14b8a6', '#6b7280'],
        bar: ['#4f46e5', '#60a5fa', '#34d399', '#facc15', '#fb923c', '#f87171']
    };
    
    // Fungsi untuk mencipta kad carta
    function createChartCard(id, title, type, data) {
        const card = document.createElement('div');
        card.className = 'bg-white p-6 rounded-lg shadow-sm border border-slate-200 chart-card';
        card.dataset.title = title;
        card.innerHTML = `<h3 class="text-lg font-semibold mb-4 text-slate-700 text-center">${title}</h3><div class="h-64"><canvas id="${id}"></canvas></div>`;
        grid.appendChild(card);
        
        const ctx = document.getElementById(id).getContext('2d');
        const labels = data.map(d => `${d.label} (${Number(d.value).toLocaleString()})`);
        const values = data.map(d => d.value);

        if (type === 'pie') {
            new Chart(ctx, {
                type: 'pie',
                data: { 
                    labels: labels, 
                    datasets: [{ data: values, backgroundColor: chartColors.pie, borderWidth: 2, borderColor: '#fff' }] 
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { 
                        legend: { position: 'bottom', labels: { font: { size: 11 } } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label = label.split('(')[0].trim();
                                    }
                                    let value = context.raw || 0;
                                    let sum = context.chart.getDatasetMeta(0).total;
                                    let percentage = sum > 0 ? (value / sum * 100).toFixed(2) + '%' : '0%';
                                    return `${label}: ${value.toLocaleString()} (${percentage})`;
                                }
                            }
                        }
                    }
                }
            });
        } else if (type === 'bar') {
            new Chart(ctx, {
                type: 'bar',
                data: { 
                    labels: data.map(d => d.label), 
                    datasets: [{ 
                        label: 'Jumlah', 
                        data: values, 
                        backgroundColor: chartColors.bar 
                    }] 
                },
                options: { 
                    indexAxis: 'y', // Menjadikan carta bar melintang
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                             callbacks: {
                                label: function(context) {
                                    let value = context.raw || 0;
                                    return `Jumlah: ${value.toLocaleString()}`;
                                }
                            }
                        }
                    },
                     scales: {
                        x: {
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Jana semua carta
    if(backendData && backendData.perjawatan) {
        createChartCard('perjawatanChart', 'Maklumat Perjawatan & Pengisian', 'pie', backendData.perjawatan);
        createChartCard('jantinaChart', 'Komposisi Jantina', 'pie', backendData.jantina);
        createChartCard('statusChart', 'Status Perkahwinan', 'pie', backendData.status);
        createChartCard('kaumChart', 'Pecahan Kaum', 'bar', backendData.kaum);
        createChartCard('agamaChart', 'Komposisi Agama', 'bar', backendData.agama);
        createChartCard('pendidikanChart', 'Tahap Pendidikan', 'bar', backendData.pendidikan);
    }

    // Fungsi carian
    const searchBox = document.getElementById('search-box');
    searchBox.addEventListener('keyup', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.chart-card');
        cards.forEach(card => {
            const title = card.dataset.title.toLowerCase();
            if (title.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>

